<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
class HomeController extends Controller {
 public function index(): View {return view('admin.home',[
  'accountCount'=>Account::count(),'sellerCount'=>Account::where('role','seller')->count(),
  'shopCount'=>Seller::where('status','active')->count(),
  'pendingShopCount'=>Seller::where('status','pending')->count(),
  'productCount'=>Product::count(),'lockedProductCount'=>Product::where('status','locked')->count(),
  'orderCount'=>Order::count(), 'pendingReportCount'=>DB::table('product_reports')->where('status','pending')->count(),
  'revenue'=>Order::where('order_status','completed')->sum('total_amount'),
  'recentShops'=>Seller::where('status','pending')->latest()->limit(5)->get(),
 ]);}
}
