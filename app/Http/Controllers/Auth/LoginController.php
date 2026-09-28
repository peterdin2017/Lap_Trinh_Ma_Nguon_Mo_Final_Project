<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    // Hiển thị form đăng nhập
    public function showForm()
    {
        return view('auth.login');
    }

    // Xử lý khi submit form đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tìm tài khoản theo email
        $account = Account::where('email', $request->email)->first();

        // Kiểm tra: tài khoản có tồn tại không, mật khẩu có đúng không
        if (!$account || !password_verify($request->password, $account->password_hash)) {
            return back()->withErrors(['login' => 'Email hoặc mật khẩu không đúng']);
        }

        // Lấy thông tin họ tên để hiển thị lên header sau khi đăng nhập
        $user = User::where('account_id', $account->account_id)->first();

        // Lưu thông tin đăng nhập vào session
        session([
            'account_id' => $account->account_id,
            'account_email' => $account->email,
            'user_name' => $user->full_name,
            'avatar' => $user->avatar,
        ]);

        return redirect('/');
    }

    // Xử lý khi bấm "Đăng xuất"
    public function logout()
    {
        // Xóa hết thông tin đăng nhập khỏi session
        session()->forget(['account_id', 'account_email', 'user_name']);

        return redirect('/');
    }
}
