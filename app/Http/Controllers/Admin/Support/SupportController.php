<?php

namespace App\Http\Controllers\Admin\Support;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Support\SupportIndexRequest;
use App\Http\Requests\Admin\Support\UpdateSupportRequest;
use App\Models\CustomerNotification;
use App\Models\CustomerSupportRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SupportController extends Controller
{
    public function index(SupportIndexRequest $request): View
    {
        $filters = $request->validated();
        $statuses = [
            'pending' => 'Chờ tiếp nhận',
            'resolved' => 'Đã xử lý',
        ];

        $query = CustomerSupportRequest::query()
            ->with(['account', 'handler']);

        $status = $filters['status'] ?? null;

        if ($status === 'pending') {
            // Bao gồm cả yêu cầu cũ đang có trạng thái in_progress.
            $query->whereIn('status', ['pending', 'in_progress']);
        } elseif ($status === 'resolved') {
            $query->where('status', 'resolved');
        }

        $keyword = trim((string) ($filters['q'] ?? ''));

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

    public function updates(): JsonResponse
    {
        $openStatuses = ['pending', 'in_progress'];

        $requests = CustomerSupportRequest::query()
            ->whereIn('status', $openStatuses)
            ->with('account')
            ->orderByDesc('created_at')
            ->orderByDesc('request_id')
            ->limit(8)
            ->get();

        return response()->json([
            'open_total' => CustomerSupportRequest::query()
                ->whereIn('status', $openStatuses)
                ->count(),
            'latest_request_id' => (int) (CustomerSupportRequest::query()->max('request_id') ?? 0),
            'requests' => $requests->map(fn (CustomerSupportRequest $supportRequest): array => [
                'id' => $supportRequest->request_id,
                'customer' => $supportRequest->account?->email
                    ?? 'Tài khoản không còn tồn tại',
                'requester_role' => $supportRequest->requester_role,
                'topic' => $supportRequest->topic,
                'message' => $supportRequest->message,
                'created_at' => $supportRequest->created_at?->toIso8601String(),
                'url' => route('admin.support.index', ['status' => 'pending'])
                    . '#support-request-' . $supportRequest->request_id,
            ])->values(),
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
