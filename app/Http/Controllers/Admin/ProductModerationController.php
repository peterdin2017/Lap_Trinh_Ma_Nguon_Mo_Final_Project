<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
class ProductModerationController extends Controller {
 public function index(Request $request) {
  $query=Product::with(['seller','category']);
  if($request->filled('q')) $query->where('product_name','like','%'.$request->q.'%');
  if(in_array($request->input('status'),['active','hidden','locked'],true)) $query->where('status',$request->status);
  return view('admin.products.index',['products'=>$query->orderByDesc('product_id')->paginate(12)->withQueryString()]);
 }
 public function lock(Request $request,Product $product) {
  $data=$request->validate(['locked_reason'=>['required','string','max:255']]);
  if($product->status==='locked')return back()->with('error','Sản phẩm đã bị khóa.');
  $product->forceFill(['status'=>'locked','locked_reason'=>$data['locked_reason'],'locked_at'=>now()])->save();
  return back()->with('success','Đã khóa sản phẩm.');
 }
 public function unlock(Product $product) {
  if($product->status!=='locked')return back()->with('error','Sản phẩm không ở trạng thái khóa.');
  // Không có cột trạng thái trước khi khóa: mở về hidden để seller chủ động đăng bán.
  $product->forceFill(['status'=>'hidden','locked_reason'=>null,'locked_at'=>null])->save();
  return back()->with('success','Đã mở khóa; sản phẩm chuyển sang ẩn để seller tự mở bán.');
 }
}
