<?php
namespace App\Http\Controllers\Admin\Account;
use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
class AccountManagementController extends Controller {
 public function index(Request $request) {
  $query=Account::query();
  if($request->filled('q')) $query->where('email','like','%'.$request->q.'%');
  if(in_array($request->input('role'),['user','seller','admin'],true)) $query->where('role',$request->role);
  if(in_array($request->input('status'),['active','locked'],true)) $query->where('status',$request->status);
  return view('admin.accounts.index',['accounts'=>$query->orderByDesc('account_id')->paginate(12)->withQueryString()]);
 }
 public function toggle(Request $request, Account $account) {
  $data=$request->validate(['status'=>['required','in:active,locked']]);
  if($account->account_id === $request->user()->account_id && $data['status']==='locked') return back()->with('error','Không thể tự khóa tài khoản đang đăng nhập.');
  $account->update(['status'=>$data['status']]);
  return back()->with('success','Đã cập nhật trạng thái tài khoản.');
 }
}
