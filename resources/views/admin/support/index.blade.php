@extends('admin.layouts.app')

@section('title', 'Hỗ trợ khách hàng')
@section('page-title', 'Hỗ trợ khách hàng')

@section('content')
    @include('admin.partials.flash')

    @php
        $topicLabels = [
            'account' => 'Tài khoản',
            'order' => 'Đơn hàng',
            'payment' => 'Thanh toán',
            'shipping' => 'Vận chuyển',
            'return' => 'Đổi trả và hoàn tiền',
            'product' => 'Sản phẩm',
            'other' => 'Vấn đề khác',
        ];

        $statusLabels = [
            'pending' => 'Chờ tiếp nhận',
            'in_progress' => 'Đang xử lý',
            'resolved' => 'Đã xử lý',
        ];

        $statusClasses = [
            'pending' => 'warning',
            'in_progress' => 'muted',
            'resolved' => 'success',
        ];
    @endphp

    <div class="admin-section-head">
        <div>
            <h2>Yêu cầu hỗ trợ</h2>
            <p>Xem nội dung, phản hồi khách hàng và cập nhật trạng thái xử lý.</p>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.support.index') }}" class="admin-filter mb-3">
        <input
            type="search"
            name="q"
            value="{{ request('q') }}"
            class="form-control"
            placeholder="Tìm theo mã yêu cầu, email hoặc nội dung"
            aria-label="Tìm yêu cầu hỗ trợ"
        >

        <select name="status" class="form-select" aria-label="Lọc trạng thái">
            <option value="">Tất cả trạng thái</option>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">Lọc</button>
        <a href="{{ route('admin.support.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
    </form>

    <div class="support-request-list">
        @forelse ($requests as $supportRequest)
            <article class="admin-panel support-request-card">
                <header class="support-request-card__header">
                    <div>
                        <div class="support-request-card__id">
                            Yêu cầu #{{ $supportRequest->request_id }}
                        </div>

                        <div class="small text-secondary">
                            Gửi lúc {{ $supportRequest->created_at?->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <span class="admin-pill {{ $statusClasses[$supportRequest->status] ?? 'muted' }}">
                        {{ $statusLabels[$supportRequest->status] ?? $supportRequest->status }}
                    </span>
                </header>

                <div class="support-request-card__body">
                    <div class="support-request-meta">
                        <div>
                            <span class="support-request-label">Người gửi</span>
                            <strong>
                                {{ $supportRequest->account?->email ?? 'Tài khoản không còn tồn tại' }}
                            </strong>
                            <span class="small text-secondary">
                                {{ $supportRequest->requester_role === 'seller' ? 'Người bán' : 'Người mua' }}
                            </span>
                        </div>

                        <div>
                            <span class="support-request-label">Chủ đề</span>
                            <strong>
                                {{ $topicLabels[$supportRequest->topic] ?? $supportRequest->topic }}
                            </strong>
                        </div>
                    </div>

                    <div class="support-request-message">
                        <span class="support-request-label">Nội dung yêu cầu</span>
                        <p>{{ $supportRequest->message }}</p>
                    </div>

                    @if ($supportRequest->attachments)
                        <div class="support-attachments">
                            <span class="support-request-label">Tệp đính kèm</span>

                            <div class="support-attachments__list">
                                @foreach ($supportRequest->attachments as $index => $path)
                                    @php
                                        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                                        $previewUrl = route('admin.support.attachments.preview', [
                                            $supportRequest->request_id,
                                            $index,
                                        ]);
                                    @endphp

                                    <details class="support-attachment">
                                        <summary>
                                            <i class="bi bi-paperclip" aria-hidden="true"></i>
                                            <span>{{ basename($path) }}</span>
                                            <i
                                                class="bi bi-chevron-down support-attachment__chevron"
                                                aria-hidden="true"
                                            ></i>
                                        </summary>

                                        <div class="support-attachment__preview">
                                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true))
                                                <img
                                                    src="{{ $previewUrl }}"
                                                    alt="Ảnh khách đính kèm"
                                                    loading="lazy"
                                                >
                                            @elseif ($extension === 'pdf')
                                                <iframe
                                                    src="{{ $previewUrl }}"
                                                    title="Xem trước PDF đính kèm"
                                                    loading="lazy"
                                                ></iframe>
                                            @elseif ($extension === 'mp4')
                                                <video controls preload="metadata">
                                                    <source src="{{ $previewUrl }}" type="video/mp4">
                                                    Trình duyệt không hỗ trợ phát video.
                                                </video>
                                            @else
                                                <div class="support-attachment__unsupported">
                                                    <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                                                    <span>
                                                        Trình duyệt không hỗ trợ xem trước định dạng
                                                        {{ strtoupper($extension) }}.
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </details>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('admin.support.update', $supportRequest->request_id) }}"
                        class="support-process-form"
                    >
                        @csrf
                        @method('PATCH')

                        <label
                            class="form-label fw-semibold"
                            for="reply-{{ $supportRequest->request_id }}"
                        >
                            Phản hồi khách hàng
                        </label>

                        <textarea
                            id="reply-{{ $supportRequest->request_id }}"
                            name="admin_reply"
                            rows="3"
                            maxlength="5000"
                            class="form-control @error('admin_reply') is-invalid @enderror"
                            placeholder="Nhập nội dung phản hồi..."
                        >{{ old('admin_reply', $supportRequest->admin_reply) }}</textarea>

                        @error('admin_reply')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="support-process-form__actions">
                            <select name="status" class="form-select" required>
                                @foreach ($statuses as $value => $label)
                                    <option
                                        value="{{ $value }}"
                                        @selected(old('status', $supportRequest->status) === $value)
                                    >
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>

                            <button
                                type="submit"
                                class="btn btn-outline-primary"
                                name="notify_customer"
                                value="0"
                            >
                                <i class="bi bi-save me-1" aria-hidden="true"></i>
                                Lưu xử lý
                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary"
                                name="notify_customer"
                                value="1"
                            >
                                <i class="bi bi-bell me-1" aria-hidden="true"></i>
                                Lưu và thông báo khách hàng
                            </button>
                        </div>

                        @if ($supportRequest->handler)
                            <div class="small text-secondary mt-2">
                                Cập nhật bởi {{ $supportRequest->handler->email }}
                            </div>
                        @endif
                    </form>
                </div>
            </article>
        @empty
            <div class="admin-panel p-5 text-center text-secondary">
                Chưa có yêu cầu hỗ trợ phù hợp.
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $requests->links() }}
    </div>

    <style>
        .support-request-list {
            display: grid;
            gap: 18px;
        }

        .support-request-card {
            overflow: hidden;
            border: 1px solid var(--app-border, #e2e8f0);
            border-radius: 14px;
        }

        .support-request-card__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 20px;
            border-bottom: 1px solid var(--app-border, #e2e8f0);
        }

        .support-request-card__id {
            font-size: 1.05rem;
            font-weight: 800;
        }

        .support-request-card__body {
            padding: 20px;
        }

        .support-request-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 28px;
            margin-bottom: 20px;
        }

        .support-request-meta > div {
            display: grid;
            gap: 4px;
        }

        .support-request-label {
            display: block;
            margin-bottom: 6px;
            color: var(--app-text-muted, #64748b);
            font-size: .82rem;
            font-weight: 700;
        }

        .support-request-message {
            padding: 14px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #f5f8fc;
            color: #24364b;
        }

        .support-request-message .support-request-label {
            color: #64748b;
        }

        .support-request-message p {
            margin: 0;
            color: #24364b;
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        .support-attachments {
            margin-top: 18px;
        }

        .support-attachments__list {
            display: grid;
            gap: 10px;
        }

        .support-attachment {
            overflow: hidden;
            border: 1px solid var(--app-border, #e2e8f0);
            border-radius: 10px;
            background: var(--app-card-bg, #fff);
        }

        .support-attachment summary {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 11px 14px;
            color: var(--app-text, #183153);
            cursor: pointer;
            font-weight: 600;
            list-style: none;
        }

        .support-attachment summary::-webkit-details-marker {
            display: none;
        }

        .support-attachment summary > span {
            flex: 1;
            overflow-wrap: anywhere;
        }

        .support-attachment summary > i:first-child {
            color: var(--app-primary, #1687f8);
        }

        .support-attachment__chevron {
            transition: transform .2s ease;
        }

        .support-attachment[open] .support-attachment__chevron {
            transform: rotate(180deg);
        }

        .support-attachment__preview {
            padding: 14px;
            border-top: 1px solid var(--app-border, #e2e8f0);
            background: #f5f8fc;
            text-align: center;
        }

        .support-attachment__preview img,
        .support-attachment__preview video {
            display: block;
            max-width: 100%;
            max-height: 620px;
            margin: 0 auto;
            border-radius: 8px;
        }

        .support-attachment__preview iframe {
            width: 100%;
            height: 620px;
            border: 0;
            border-radius: 8px;
            background: #fff;
        }

        .support-attachment__unsupported {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 24px;
            color: #64748b;
        }

        .support-attachment__unsupported i {
            color: var(--app-primary, #1687f8);
            font-size: 1.4rem;
        }

        .support-process-form {
            margin-top: 22px;
            padding-top: 20px;
            border-top: 1px solid var(--app-border, #e2e8f0);
        }

        .support-process-form__actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 12px;
        }

        .support-process-form__actions select {
            max-width: 220px;
        }

        [data-bs-theme="dark"] .support-request-message {
            border-color: #34445b;
            background: #111d30;
            color: #e2eaf4;
        }

        [data-bs-theme="dark"] .support-request-message .support-request-label {
            color: #a9bdd4;
        }

        [data-bs-theme="dark"] .support-request-message p {
            color: #e2eaf4;
        }

        [data-bs-theme="dark"] .support-attachment {
            border-color: #34445b;
            background: #1e293b;
        }

        [data-bs-theme="dark"] .support-attachment summary {
            color: #e2eaf4;
        }

        [data-bs-theme="dark"] .support-attachment__preview {
            border-color: #34445b;
            background: #111d30;
        }

        [data-bs-theme="dark"] .support-attachment__unsupported {
            color: #c4d1e0;
        }

        @media (max-width: 575.98px) {
            .support-request-card__header,
            .support-request-card__body {
                padding: 14px;
            }

            .support-process-form__actions {
                align-items: stretch;
                flex-direction: column;
            }

            .support-process-form__actions select {
                max-width: none;
            }

            .support-attachment__preview iframe {
                height: 420px;
            }
        }
    </style>
@endsection
