<?php

namespace App\Http\Controllers\Admin\Support;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Support\UpdateSupportRequest;
use App\Models\CustomerSupportRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\CustomerNotification;
use Illuminate\Support\Facades\DB;

class SupportController extends Controller
{
    public function index(Request $request): View
    {
        $statuses = [
            'pending' => 'Chờ tiếp nhận',
            'in_progress' => 'Đang xử lý',
            'resolved' => 'Đã xử lý',
        ];

        $query = CustomerSupportRequest::query()
            ->with(['account', 'handler']);

        $status = $request->query('status');

        if (array_key_exists($status, $statuses)) {
            $query->where('status', $status);
        }

        $keyword = trim((string) $request->query('q', ''));

        if ($keyword !== '') {
            $query->where(function ($builder) use ($keyword): void {
                $builder->where(
                    'request_id',
                    is_numeric($keyword) ? (int) $keyword : -1
                )->orWhere('message', 'like', '%' . $keyword . '%')
                    ->orWhereHas('account', function ($accountQuery) use ($keyword): void {
                        $accountQuery->where('email', 'like', '%' . $keyword . '%');
                    });
            });
        }

        $requests = $query
            ->orderByRaw("FIELD(status, 'pending', 'in_progress', 'resolved')")
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.support.index', [
            'requests' => $requests,
            'statuses' => $statuses,
        ]);
    }

    public function update(UpdateSupportRequest $request, int $requestId): RedirectResponse
    {
        $supportRequest = CustomerSupportRequest::query()->findOrFail($requestId);
        $data = $request->validated();

        $notifyCustomer = $request->boolean('notify_customer'); 

        if ($notifyCustomer && ! $supportRequest->account_id) {
            return back()->with('error', 'Không thể thông báo vì tài khoản người gửi không còn tồn tại.');
        }

        DB::transaction(function () use ($supportRequest, $data, $notifyCustomer): void {
            $supportRequest->status = $data['status'];
            $supportRequest->admin_reply = $data['admin_reply'] ?? null;
            $supportRequest->handled_by = Auth::guard('admin')->id();
            $supportRequest->handled_at = $data['status'] === 'resolved'
                ? now()
                : null;
            $supportRequest->save();

            if ($notifyCustomer) {
                CustomerNotification::query()->create([
                    'account_id' => $supportRequest->account_id,
                    'support_request_id' => $supportRequest->request_id,
                    'title' => 'Cập nhật yêu cầu hỗ trợ #' . $supportRequest->request_id,
                    'message' => $data['admin_reply'],
                ]);
            }
        });

        return back()->with(
            'success',
            $notifyCustomer
                ? 'Đã lưu xử lý và gửi thông báo cho khách hàng.'
                : 'Đã lưu xử lý yêu cầu hỗ trợ.'
        );
    }

    /**
     * Trả tệp để trình duyệt xem trực tiếp, không tải xuống.
     */
    public function previewAttachment(int $requestId, int $attachmentIndex)
    {
        $supportRequest = CustomerSupportRequest::query()->findOrFail($requestId);
        $attachments = $supportRequest->attachments ?? [];
        $path = $attachments[$attachmentIndex] ?? null;

        abort_unless(
            is_string($path)
                && str_starts_with($path, 'support-requests/')
                && Storage::disk('local')->exists($path),
            404
        );

        $absolutePath = Storage::disk('local')->path($path);
        $mimeType = (new \finfo(FILEINFO_MIME_TYPE))->file($absolutePath)
            ?: 'application/octet-stream';

        return response()->file($absolutePath, [
            'Content-Type' => $mimeType,
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
