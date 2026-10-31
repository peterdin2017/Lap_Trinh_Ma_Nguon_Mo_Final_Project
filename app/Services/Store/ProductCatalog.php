<?php

namespace App\Services\Store;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Truy vấn sản phẩm phía khách (Store). Gom một chỗ để mọi trang dùng chung
 * đúng một quy tắc "được phép bán".
 *
 * Dựa trên schema hiện có của nhóm: products.price đã là giá thấp nhất của các
 * mẫu và products.stock_quantity là tổng tồn (xem Seller\Product\ProductController).
 */
class ProductCatalog
{
    public const SORTS = ['newest', 'best_selling', 'price_asc', 'price_desc'];

    /** Sản phẩm active VÀ thuộc shop active. */
    public function visible(): Builder
    {
        return Product::query()
            ->where('products.status', 'active')
            ->whereHas('seller', fn (Builder $seller) => $seller->where('sellers.status', 'active'));
    }

    /**
     * @param array{q?: ?string, category?: int|string|null, min_price?: ?int, max_price?: ?int, sort?: ?string} $filters
     */
    public function search(array $filters): Builder
    {
        $query = $this->visible()->with(['category', 'seller']);

        $term = trim((string) ($filters['q'] ?? ''));

        if ($term !== '') {
            // Escape ký tự đặc biệt của LIKE để "50%" hay "a_b" được tìm như chữ thường.
            $like = '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $term) . '%';

            $query->where(function (Builder $q) use ($like) {
                $q->whereRaw("products.product_name LIKE ? ESCAPE '!'", [$like])
                    ->orWhereRaw("products.brand LIKE ? ESCAPE '!'", [$like]);
            });
        }

        if (! empty($filters['category'])) {
            // Quy ước nhóm: chọn danh mục cha thì BAO GỒM sản phẩm của danh mục con/cháu.
            $category = $this->findCategory($filters['category']);
            $categoryIds = $category
                ? $this->descendantIds((int) $category->category_id)
                : [];

            $query->whereIn('products.category_id', $categoryIds);
        }

        if (isset($filters['min_price'])) {
            $query->where('products.price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('products.price', '<=', $filters['max_price']);
        }

        return match ($filters['sort'] ?? 'newest') {
            'price_asc' => $query->orderBy('products.price')->orderByDesc('products.product_id'),
            'price_desc' => $query->orderByDesc('products.price')->orderByDesc('products.product_id'),
            'best_selling' => $query->orderByDesc('products.sold_count')->orderByDesc('products.product_id'),
            default => $query->orderByDesc('products.created_at')->orderByDesc('products.product_id'),
        };
    }

    public function findCategory(int|string $identifier): ?Category
    {
        $value = (string) $identifier;

        if (ctype_digit($value)) {
            return Category::query()->find((int) $value);
        }

        return Category::query()->where('slug', $value)->first();
    }

    /**
     * Id của danh mục + toàn bộ hậu duệ. Có chống vòng lặp nếu dữ liệu sai.
     *
     * @return array<int, int>
     */
    public function descendantIds(int $categoryId): array
    {
        $parentOf = Category::query()->pluck('parent_id', 'category_id');

        $ids = [$categoryId];
        $queue = [$categoryId];

        while ($queue) {
            $current = array_shift($queue);

            foreach ($parentOf as $id => $parentId) {
                if ($parentId === $current && ! in_array($id, $ids, true)) {
                    $ids[] = $id;
                    $queue[] = $id;
                }
            }
        }

        return $ids;
    }

    /**
     * Cây danh mục (1 truy vấn). Mỗi node có relation `children` nạp sẵn.
     *
     * @return Collection<int, Category> các danh mục gốc
     */
    public function categoryTree(): Collection
    {
        $all = Category::query()->orderBy('category_name')->get();
        $byParent = $all->whereNotNull('parent_id')->groupBy('parent_id');

        foreach ($all as $category) {
            $category->setRelation('children', $byParent->get($category->category_id, new Collection));
        }

        return $all->whereNull('parent_id')->values();
    }

    /**
     * Chuỗi danh mục từ gốc đến danh mục của sản phẩm, dùng cho breadcrumb.
     *
     * @return array<int, Category>
     */
    public function breadcrumb(?Category $category): array
    {
        $chain = [];

        // Giới hạn độ sâu để không treo nếu dữ liệu có vòng lặp.
        for ($i = 0; $category && $i < 10; $i++) {
            array_unshift($chain, $category);
            $category = $category->parent;
        }

        return $chain;
    }
}
