<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // Hiển thị trang hồ sơ
    public function show()
    {
        $account = Account::find(session('account_id'));
        $user = User::where('account_id', $account->account_id)->first();

        return view('account.profile', [
            'account' => $account,
            'user' => $user,
        ]);
    }

    // Xử lý khi bấm nút Lưu
    public function update(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',

            'phone' => ['nullable','regex:/^0[0-9]{9}$/'],

            'gender' => 'nullable|in:male,female,other',

            'birthdate' => [
                'nullable',
                'date',
                'before_or_equal:' . now()->subYears(13)->format('Y-m-d'),
                'after_or_equal:' . now()->subYears(100)->format('Y-m-d'),
            ],
            'avatar' => 'nullable|image|max:1024', // tối đa 1MB,
        ], [
            'full_name.required' => 'Vui lòng nhập tên',

            'phone.regex' => 'Số điện thoại phải gồm 10 chữ số và bắt đầu bằng số 0',

            'birthdate.date' => 'Ngày sinh không hợp lệ',
            'birthdate.before_or_equal' => 'Bạn phải từ 13 tuổi trở lên',
            'birthdate.after_or_equal' => 'Ngày sinh không hợp lệ',

            'avatar.image' => 'File tải lên phải là hình ảnh',
            'avatar.mimes' => 'Ảnh phải có định dạng JPEG hoặc PNG',
            'avatar.max' => 'Ảnh không được vượt quá 1MB',
        ]);

        $user = User::where('account_id', session('account_id'))->first();

        // Nếu người dùng có chọn ảnh mới, lưu ảnh vào storage
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->full_name = $request->full_name;
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->birthdate = $request->birthdate;
        $user->save();

        // Cập nhật lại tên hiển thị trên header
        session([
            'user_name' => $user->full_name,
            'avatar' => $user->avatar,
        ]);

        return back()->with('success', 'Cập nhật hồ sơ thành công');
    }
}
