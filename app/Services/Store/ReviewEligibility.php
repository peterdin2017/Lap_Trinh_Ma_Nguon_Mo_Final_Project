<?php

namespace App\Services\Store;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Quy tắc: chỉ người đã MUA và đơn đã GIAO THÀNH CÔNG mới được đánh giá,
 * mỗi DÒNG HÀNG (order_items) được đánh giá đúng một lần.
 *
 * Giả định schema của Người 2 (đổi 3 hằng số nếu tên khác):
 *   orders(order_id, account_id, status)     status = 'delivered' khi giao xong
 *   order_items(order_item_id, order_id, product_id)
 * Nếu các bảng/cột này chưa tồn tại thì trả null (chưa ai được đánh giá), không báo lỗi.
 */
class ReviewEligibility
{
    private const ORDERS = 'orders';
    private const ORDER_ITEMS = 'order_items';
    private const DELIVERED = 'delivered';

    /** order_item_id còn được phép đánh giá, hoặc null nếu không đủ điều kiện. */
    public function reviewableOrderItemId(?int $accountId, int $productId): ?int
    {
        if (! $accountId || ! $this->orderTablesReady()) {
            return null;
        }

        $id = DB::table(self::ORDER_ITEMS . ' as oi')
            ->join(self::ORDERS . ' as o', 'o.order_id', '=', 'oi.order_id')
            ->where('o.account_id', $accountId)
            ->where('o.status', self::DELIVERED)
            ->where('oi.product_id', $productId)
            ->whereNotExists(function ($sub) {
                $sub->select(DB::raw(1))
                    ->from('reviews as r')
                    ->whereColumn('r.order_item_id', 'oi.order_item_id');
            })
            ->orderBy('oi.order_item_id')
            ->value('oi.order_item_id');

        return $id ? (int) $id : null;
    }

    private function orderTablesReady(): bool
    {
        return Schema::hasTable(self::ORDERS)
            && Schema::hasTable(self::ORDER_ITEMS)
            && Schema::hasColumns(self::ORDERS, ['order_id', 'account_id', 'status'])
            && Schema::hasColumns(self::ORDER_ITEMS, ['order_item_id', 'order_id', 'product_id']);
    }
}
