<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

class CategoryManagementController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with('parent')->withCount(['products', 'children'])->orderBy('category_name')->paginate(15);
        $parents = Category::orderBy('category_name')->get(['category_id', 'category_name', 'parent_id']);
        return view('admin.categories.index', compact('categories', 'parents'));
    }
    public function store(Request $request)
    {
        $data = $request->validate(['category_name' => ['required', 'string', 'max:100'], 'parent_id' => ['nullable', 'exists:categories,category_id']]);
        Category::create($data);
        return back()->with('success', 'Đã tạo danh mục.');
    }
    public function update(Request $request, Category $category)
    {
        $data = $request->validate(['category_name' => ['required', 'string', 'max:100'], 'parent_id' => ['nullable', 'exists:categories,category_id']]);
        $ancestor = $data['parent_id'] ?? null;
        while ($ancestor !== null) {
            if ((int)$ancestor === (int)$category->category_id) return back()->with('error', 'Không thể tạo vòng lặp danh mục.');
            $ancestor = Category::whereKey($ancestor)->value('parent_id');
        }
        $category->update($data);
        return back()->with('success', 'Đã cập nhật danh mục.');
    }
    public function destroy(Category $category)
    {
        if ($category->products()->exists() || $category->children()->exists()) return back()->with('error', 'Danh mục còn sản phẩm hoặc danh mục con, không thể xóa.');
        $category->delete();
        return back()->with('success', 'Đã xóa danh mục.');
    }
}
