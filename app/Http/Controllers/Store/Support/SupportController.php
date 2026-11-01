<?php

namespace App\Http\Controllers\Store\Support;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Support\SupportSearchRequest;
use App\Http\Requests\Store\Support\StoreSupportRequest;
use App\Models\Account;
use App\Models\CustomerSupportRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SupportController extends Controller
{
    private const TOPICS = [
        'account' => 'Tài khoản',
        'order' => 'Đơn hàng',
        'payment' => 'Thanh toán',
        'shipping' => 'Vận chuyển',
        'return' => 'Đổi trả và hoàn tiền',
        'product' => 'Sản phẩm',
        'other' => 'Vấn đề khác',
    ];

    /**
     * Lấy tài khoản khách hoặc seller đang đăng nhập bằng session của Store.
     */
    private function customerAccount(Request $request): ?Account
    {
        $accountId = $request->session()->get('account_id');

        if (! is_numeric($accountId)) {
            return null;
        }

        return Account::query()
            ->whereKey((int) $accountId)
            ->whereIn('role', ['user', 'seller'])
            ->where('status', 'active')
            ->first();
    }

    /**
     * Trang trung tâm hỗ trợ và câu hỏi thường gặp.
     */
    public function index(SupportSearchRequest $request): View
    {
        $faqs = [
            [
                'topic' => 'account',
                'question' => 'Làm thế nào để cập nhật thông tin tài khoản?',
                'answer' => 'Vào mục Tài khoản, chọn Hồ sơ của tôi để kiểm tra và cập nhật thông tin.',
            ],
            [
                'topic' => 'order',
                'question' => 'Tôi xem trạng thái đơn hàng ở đâu?',
                'answer' => 'Mở Tài khoản → Đơn hàng của tôi, chọn đơn hàng cần xem để kiểm tra trạng thái.',
            ],
            [
                'topic' => 'payment',
                'question' => 'Tôi có thể thanh toán bằng phương thức nào?',
                'answer' => 'Các phương thức thanh toán khả dụng sẽ được hiển thị ở bước thanh toán.',
            ],
            [
                'topic' => 'shipping',
                'question' => 'Làm sao để kiểm tra tình trạng giao hàng?',
                'answer' => 'Mở chi tiết đơn hàng để xem trạng thái giao hàng mới nhất.',
            ],
            [
                'topic' => 'return',
                'question' => 'Tôi muốn yêu cầu đổi trả hoặc hoàn tiền thì làm thế nào?',
                'answer' => 'Mở đơn hàng liên quan và gửi yêu cầu đổi trả. Nếu cần hỗ trợ thêm, hãy gửi biểu mẫu hỗ trợ.',
            ],
            [
                'topic' => 'product',
                'question' => 'Làm sao để đánh giá sản phẩm?',
                'answer' => 'Bạn có thể đánh giá sản phẩm sau khi đơn hàng được xác nhận đã hoàn thành.',
            ],
        ];

        $keyword = trim((string) ($request->validated('q') ?? ''));

        if ($keyword !== '') {
            $normalizedKeyword = mb_strtolower($keyword);

            $faqs = array_values(array_filter($faqs, function (array $faq) use ($normalizedKeyword): bool {
                return str_contains(mb_strtolower($faq['question']), $normalizedKeyword)
                    || str_contains(mb_strtolower($faq['answer']), $normalizedKeyword);
            }));
        }

        return view('store.support.index', [
            'faqs' => $faqs,
            'topics' => self::TOPICS,
            'keyword' => $keyword,
        ]);
    }

    /**
     * Hiển thị biểu mẫu gửi yêu cầu hỗ trợ.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $account = $this->customerAccount($request);

        if (! $account) {
            return redirect()
                ->route('login.form')
                ->with('error', 'Vui lòng đăng nhập tài khoản khách hàng hoặc người bán để gửi yêu cầu hỗ trợ.');
        }

        return view('store.support.create', [
            'account' => $account,
            'topics' => self::TOPICS,
        ]);
    }

    /**
     * Lưu yêu cầu hỗ trợ và các tệp đính kèm.
     */
    public function store(StoreSupportRequest $request): RedirectResponse
    {
        $account = $this->customerAccount($request);

        if (! $account) {
            return redirect()
                ->route('login.form')
                ->with('error', 'Vui lòng đăng nhập trước khi gửi yêu cầu hỗ trợ.');
        }

        $validated = $request->validated();
        $paths = [];

        foreach ($request->file('attachments', []) as $file) {
            $paths[] = $file->store('support-requests', 'local');
        }

        CustomerSupportRequest::query()->create([
            'account_id' => $account->account_id,
            'requester_role' => $account->role,
            'topic' => $validated['topic'],
            'message' => $validated['message'],
            'attachments' => $paths ?: null,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('store.support.index')
            ->with(
                'support_success',
                'Đã gửi yêu cầu hỗ trợ. Bộ phận chăm sóc khách hàng sẽ tiếp nhận và xử lý.'
            );
    }
}
