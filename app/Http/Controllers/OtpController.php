<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OtpController extends Controller
{
    // Hiển thị trang nhập OTP
    public function showForm(Request $request)
    {
        // Lấy account_id được gửi kèm qua url
        $accountId = $request->query('account_id');

        // Chuẩn bị dữ liệu để gửi sang view
        $duLieuChoView = ['account_id' => $accountId];

        return view('otp', $duLieuChoView);
    }

    public function verify(Request $request)
    {
        // Kiểm tra dữ liệu
        $request->validate([
            'account_id' => 'required|exists:accounts,account_id',
            'otp_code' => 'required|digits:6',
        ], [
            'otp_code.required' => 'Vui lòng nhập mã OTP',
            'otp_code.digits' => 'Mã OTP phải gồm đúng 6 chữ số',
        ]);

        //Lấy account_id và OTP người dùng nhập ra biến riêng
        $accountId = $request->account_id;
        $maOtpNguoiDungNhap = $request->otp_code;

        // Truy vấn tìm mã OTP mới nhất, dùng account
        $ketQua = DB::select(
            'SELECT * FROM otps
            WHERE account_id = :accId AND purpose=:purpose AND is_used =:used
            ORDER BY otp_id DESC
            LIMIT 1',
            [
                'accId' => $accountId,
                'purpose' => 'register',
                'used' => 0,
            ]
        );

        // Nếu tìm thấy thì gán otp
        if (isset($ketQua[0])) {
            $otp = $ketQua[0];
        } else {
            $otp = null;
        }

        // Không tìm thấy
        if (!$otp) {
            return back()->with('error', 'Không tìm thấy mã OTP, vui lòng đăng ký lại');
        }

        // Kiểm tra mã đã hết hạn chưa
        $thoiGianHienTai = now();
        $thoiGianHetHan = $otp->expires_at;
        $daHetHan = $thoiGianHienTai->greaterThan($thoiGianHetHan);

        if ($daHetHan) {
            return back()->with('error', 'Mã OTP đã hết hạn, vui lòng yêu cầu gửi lại');
        }

        // So sánh mã trong DB với mã người dùng nhập
        $maOtpTrongDb = $otp->otp_code;
        $nhapSai = ($maOtpTrongDb != $maOtpNguoiDungNhap);

        if ($nhapSai) {
            // Truy vấn UPDATE để tăng số lần nhập sai lên 1
            DB::update(
                'UPDATE otps SET attempts = attempts + 1 WHERE otp_id = :otpId',
                ['otpId' => $otp->otp_id]
            );

            return back()->with('error', 'Mã OTP không đúng, vui lòng thử lại');
        }

        // Mã đúng -> đánh dấu mã OTP này đã được dùng
        DB::update(
            'UPDATE otps SET is_used = 1 WHERE otp_id = :otpId',
            ['otpId' => $otp->otp_id]
        );

        // Đánh dấu tài khoản đã xác thực thành công
        DB::update(
            'UPDATE accounts SET is_verified = 1 WHERE account_id = :accId',
            ['accId' => $accountId]
        );

        // Chuyển hướng, kèm thông báo thành công
        return redirect()->route('register.form')
            ->with('success', 'Xác thực thành công! Bạn có thể đăng nhập ngay bây giờ');
    }
}
