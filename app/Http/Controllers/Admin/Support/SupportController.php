<?php

namespace App\Http\Controllers\Admin\Support;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Support\UpdateSupportRequest;
use App\Models\CustomerNotification;
use App\Models\CustomerSupportRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SupportController extends Controller
{
    public function index(Request $request): View
    {
        $statuses = [
            'pending' => 'Chờ tiếp nhận',
            'resolved' => 'Đã xử lý',
        ];

        $query = CustomerSupportRequest::query()
            ->with(['account', 'handler']);

        $status = $request->query('status');

        if ($status === 'pending') {
            // Bao gồm cả yêu cầu cũ đang có trạng thái in_progress.
            $query->whereIn('status', ['pending', 'in_progress']);
        } elseif ($status === 'resolved') {
            $query->where('status', 'resolved');
        }

        $keyword = trim((string) $request->query('q', ''));

        if ($keyword !== '') {
            $query->where(function ($builder) use ($keyword): void {
                $builder->where(
                    'request_id',
                    is_numeric($keyword) ? (int) $keyword : -1
                )
                ->orWhere('message', 'like', '%' . $keyword . '%')
                ->orWhereHas('account', function ($accountQuery) use ($keyword): void {
                    $accountQuery->where('email', 'like', '%' . $keyword . '%');
                });
            });
        }

        $requests = $query
            ->orderByRaw(
                "CASE WHEN status IN ('pending', 'in_progress') THEN 0 ELSE 1 END"
            )
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.support.index', [
            'requests' => $requests,
            'statuses' => $statuses,
        ]);
    }

    public function update(
        UpdateSupportRequest $request,
        int $requestId
    ): RedirectResponse {
        $supportRequest = CustomerSupportRequest::query()
            ->findOrFail($requestId);

        if (! $supportRequest->account_id) {
            return back()->with(
                'error',
                'Không thể gửi phản hồi vì tài khoản người gửi không còn tồn tại.'
            );
        }

        $data = $request->validated();
        $adminId = Auth::guard('admin')->id();

        DB::transaction(function () use ($supportRequest, $data, $adminId): void {
            $supportRequest->admin_reply = $data['admin_reply'];
            $supportRequest->status = 'resolved';
            $supportRequest->handled_by = $adminId;
            $supportRequest->handled_at = now();
            $supportRequest->save();

            CustomerNotification::query()->create([
                'account_id' => $supportRequest->account_id,
                'support_request_id' => $supportRequest->request_id,
                'title' => 'Cập nhật yêu cầu hỗ trợ #' . $supportRequest->request_id,
                'message' => $data['admin_reply'],
            ]);
        });

        return back()->with(
            'success',
            'Đã gửi phản hồi cho khách hàng và hoàn tất yêu cầu hỗ trợ.'
        );
    }

    /**
     * Hiển thị tệp đính kèm trực tiếp trên trang, không tải xuống.
     */
    public function previewAttachment(int $requestId, int $attachmentIndex)
    {
        $supportRequest = CustomerSupportRequest::query()
            ->findOrFail($requestId);

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
