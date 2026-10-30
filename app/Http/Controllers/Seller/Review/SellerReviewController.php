<?php

namespace App\Http\Controllers\Seller\Review;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Review\SellerReviewReplyRequest;
use App\Models\Review;
use App\Models\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerReviewController extends Controller
{
    public function index(Request $request): View
    {
        $seller = $this->currentSeller($request);
        $keyword = trim((string) $request->query('keyword', ''));
        $status = (string) $request->query('status', 'all');
        $rating = $request->query('rating');

        if (! in_array($status, ['all', 'unanswered', 'answered'], true)) {
            $status = 'all';
        }

        if (! in_array((string) $rating, ['1', '2', '3', '4', '5'], true)) {
            $rating = '';
        }

        $reviews = Review::query()
            ->with(['product', 'orderItem', 'images'])
            ->whereHas(
                'product',
                fn ($query) => $query->where('seller_id', $seller->seller_id)
            )
            ->when($keyword !== '', function ($query) use ($keyword): void {
                $query->whereHas(
                    'product',
                    fn ($productQuery) => $productQuery->where(
                        'product_name',
                        'like',
                        "%{$keyword}%"
                    )
                );
            })
            ->when(
                $rating !== '',
                fn ($query) => $query->where('rating', (int) $rating)
            )
            ->when(
                $status === 'answered',
                fn ($query) => $query->whereNotNull('seller_reply')
            )
            ->when(
                $status === 'unanswered',
                fn ($query) => $query->whereNull('seller_reply')
            )
            ->latest('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('seller.reviews.index', compact(
            'reviews',
            'keyword',
            'status',
            'rating'
        ));
    }

    public function update(
        SellerReviewReplyRequest $request,
        int $review
    ): RedirectResponse {
        $seller = $this->currentSeller($request);
        $data = $request->validated();

        // Chỉ cho phép sửa đánh giá của sản phẩm thuộc Shop hiện đăng nhập.
        $reviewModel = Review::query()
            ->whereHas(
                'product',
                fn ($query) => $query->where('seller_id', $seller->seller_id)
            )
            ->findOrFail($review);

        $reviewModel->seller_reply = trim($data['seller_reply']);
        $reviewModel->seller_replied_at = now();
        $reviewModel->save();

        return back()->with('success', 'Đã lưu phản hồi đánh giá.');
    }

    private function currentSeller(Request $request): Seller
    {
        $seller = $request->user()?->seller;

        abort_unless(
            $seller instanceof Seller,
            403,
            'Không tìm thấy hồ sơ cửa hàng.'
        );

        return $seller;
    }
}
