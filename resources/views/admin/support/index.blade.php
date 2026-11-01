@extends('admin.layouts.app')

@section('title', 'Hỗ trợ khách hàng')
@section('page-title', 'Hỗ trợ khách hàng')

@section('content')
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
            'in_progress' => 'Chờ tiếp nhận',
            'resolved' => 'Đã xử lý',
        ];

        $statusClasses = [
            'pending' => 'warning',
            'in_progress' => 'warning',
            'resolved' => 'success',
        ];
    @endphp

    <main class="admin-support">
        <header class="admin-section-head">
            <div>
                <h2>Yêu cầu hỗ trợ</h2>
                <p>Đọc yêu cầu, nhập phản hồi và gửi cho khách hàng để hoàn tất xử lý.</p>
            </div>
        </header>

        <section class="admin-panel support-filter-panel">
            <form method="GET" action="{{ route('admin.support.index') }}" class="support-filter">
                <div class="support-filter__keyword">
                    <label for="support-search" class="form-label">Tìm yêu cầu</label>
                    <input
                        id="support-search"
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                        class="form-control"
                        placeholder="Mã yêu cầu, email hoặc nội dung"
                    >
                </div>

                <div class="support-filter__status">
                    <label for="support-status" class="form-label">Trạng thái</label>
                    <select id="support-status" name="status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="support-filter__actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1" aria-hidden="true"></i>
                        Lọc
                    </button>

                    <a href="{{ route('admin.support.index') }}" class="btn btn-outline-secondary">
                        Xóa lọc
                    </a>
                </div>
            </form>
        </section>

        <div class="support-results-heading">
            <div>
                <h3>Danh sách yêu cầu</h3>
                <p>{{ number_format($requests->total()) }} yêu cầu phù hợp</p>
            </div>
        </div>

        <div class="support-request-list">
            @forelse ($requests as $supportRequest)
                @php
                    $requestId = $supportRequest->request_id;
                    $isThisRequestOldInput =
                        (string) old('support_request_id') === (string) $requestId;

                    $replyValue = $isThisRequestOldInput
                        ? old('admin_reply', $supportRequest->admin_reply)
                        : $supportRequest->admin_reply;

                    $displayStatus = $supportRequest->status === 'resolved'
                        ? 'resolved'
                        : 'pending';
                @endphp

                <article class="admin-panel support-card" id="support-request-{{ $requestId }}">
                    <header class="support-card__header">
                        <div class="support-card__heading">
                            <div class="support-card__id">Yêu cầu #{{ $requestId }}</div>
                            <div class="support-card__submitted">
                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                Gửi lúc {{ $supportRequest->created_at?->format('d/m/Y H:i') ?? '—' }}
                            </div>
                        </div>

                        <span class="admin-pill {{ $statusClasses[$displayStatus] }}">
                            {{ $statusLabels[$displayStatus] }}
                        </span>
                    </header>

                    <div class="support-card__body">
                        <div class="support-card__meta">
                            <div class="support-card__meta-item">
                                <span class="support-label">Người gửi</span>
                                <strong class="support-card__email">
                                    {{ $supportRequest->account?->email ?? 'Tài khoản không còn tồn tại' }}
                                </strong>
                                <span class="support-muted">
                                    {{ $supportRequest->requester_role === 'seller' ? 'Người bán' : 'Người mua' }}
                                </span>
                            </div>

                            <div class="support-card__meta-item">
                                <span class="support-label">Chủ đề</span>
                                <strong>
                                    {{ $topicLabels[$supportRequest->topic] ?? $supportRequest->topic }}
                                </strong>
                            </div>

                            @if ($supportRequest->handler)
                                <div class="support-card__meta-item">
                                    <span class="support-label">Người xử lý gần nhất</span>
                                    <strong>{{ $supportRequest->handler->email }}</strong>
                                    @if ($supportRequest->handled_at)
                                        <span class="support-muted">
                                            Cập nhật {{ $supportRequest->handled_at->format('d/m/Y H:i') }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <section class="support-message">
                            <h4 class="support-label">Nội dung khách gửi</h4>
                            <p>{{ $supportRequest->message }}</p>
                        </section>

                        @if (!empty($supportRequest->attachments))
                            <section class="support-attachments">
                                <h4 class="support-label">
                                    Tệp đính kèm
                                    <span class="support-muted">
                                        ({{ count($supportRequest->attachments) }})
                                    </span>
                                </h4>

                                <div class="support-attachments__list">
                                    @foreach ($supportRequest->attachments as $index => $path)
                                        @php
                                            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                            $previewUrl = route('admin.support.attachments.preview', [
                                                $requestId,
                                                $index,
                                            ]);
                                            $fileName = basename($path);
                                        @endphp

                                        <details class="support-attachment">
                                            <summary class="support-attachment__summary">
                                                <i class="bi bi-paperclip" aria-hidden="true"></i>
                                                <span class="support-attachment__name">{{ $fileName }}</span>
                                                <span class="support-attachment__action">Xem</span>
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
                                                        title="Xem trước tệp PDF"
                                                        loading="lazy"
                                                    ></iframe>
                                                @elseif ($extension === 'mp4')
                                                    <video controls preload="metadata">
                                                        <source src="{{ $previewUrl }}" type="video/mp4">
                                                        Trình duyệt không hỗ trợ phát video.
                                                    </video>
                                                @else
                                                    <div class="support-attachment__unsupported">
                                                        <i
                                                            class="bi bi-file-earmark-text"
                                                            aria-hidden="true"
                                                        ></i>
                                                        <span>
                                                            Không thể xem trước tệp
                                                            {{ strtoupper($extension ?: 'không rõ định dạng') }}
                                                            trên trang này.
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </details>
                                    @endforeach
                                </div>
                            </section>
                        @endif

                        @if (filled($supportRequest->admin_reply))
                            <section class="support-previous-reply">
                                <div>
                                    <h4 class="support-label mb-1">Phản hồi đã gửi</h4>
                                    @if ($supportRequest->handled_at)
                                        <span class="support-muted">
                                            Gửi lúc {{ $supportRequest->handled_at->format('d/m/Y H:i') }}
                                        </span>
                                    @endif
                                </div>
                                <p>{{ $supportRequest->admin_reply }}</p>
                            </section>
                        @endif

                        @if ($displayStatus === 'resolved')
                            <div class="support-completed">
                                <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                                Yêu cầu này đã được phản hồi và hoàn tất.
                            </div>
                        @else
                            <form
                                method="POST"
                                action="{{ route('admin.support.update', $requestId) }}"
                                class="support-process"
                            >
                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="support_request_id"
                                    value="{{ $requestId }}"
                                >

                                <div class="support-process__title">
                                    <div>
                                        <h4>Phản hồi khách hàng</h4>
                                        <p>
                                            Gửi phản hồi sẽ tạo thông báo cho khách và chuyển yêu cầu sang
                                            “Đã xử lý”.
                                        </p>
                                    </div>
                                </div>

                                <div class="support-process__reply">
                                    <label for="reply-{{ $requestId }}" class="form-label">
                                        Nội dung phản hồi
                                    </label>
                                    <textarea
                                        id="reply-{{ $requestId }}"
                                        name="admin_reply"
                                        rows="4"
                                        maxlength="5000"
                                        required
                                        class="form-control @error('admin_reply') is-invalid @enderror"
                                        placeholder="Nhập câu trả lời hoặc hướng xử lý cho khách..."
                                    >{{ $replyValue }}</textarea>

                                    @error('admin_reply')
                                        @if ($isThisRequestOldInput)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @endif
                                    @enderror
                                </div>

                                <div class="support-process__actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send me-1" aria-hidden="true"></i>
                                        Gửi phản hồi và hoàn tất
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </article>
            @empty
                <section class="admin-panel support-empty">
                    <span class="support-empty__icon">
                        <i class="bi bi-inbox" aria-hidden="true"></i>
                    </span>
                    <h3>Không tìm thấy yêu cầu hỗ trợ</h3>
                    <p>Thử đổi từ khóa hoặc trạng thái lọc.</p>
                    <a href="{{ route('admin.support.index') }}" class="btn btn-outline-secondary">
                        Xóa bộ lọc
                    </a>
                </section>
            @endforelse
        </div>

        <div class="support-pagination">
            {{ $requests->links() }}
        </div>
    </main>

    <style>
        .admin-support {
            display: grid;
            gap: 1rem;
        }

        .support-filter-panel {
            padding: 1rem;
            border-radius: 14px;
        }

        .support-filter {
            display: grid;
            grid-template-columns: minmax(260px, 1fr) minmax(190px, 260px) auto;
            align-items: end;
            gap: .85rem;
        }

        .support-filter .form-label,
        .support-process .form-label {
            margin-bottom: .45rem;
            font-weight: 650;
        }

        .support-filter .form-control,
        .support-filter .form-select,
        .support-process .form-control {
            min-height: 42px;
        }

        .support-filter__actions {
            display: flex;
            gap: .5rem;
        }

        .support-filter__actions .btn {
            min-height: 42px;
            white-space: nowrap;
        }

        .support-results-heading h3 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 750;
        }

        .support-results-heading p {
            margin: .25rem 0 0;
            color: var(--app-text-muted, #64748b);
            font-size: .88rem;
        }

        .support-request-list {
            display: grid;
            gap: 1rem;
        }

        .support-card {
            overflow: hidden;
            border: 1px solid var(--app-border, #e2e8f0);
            border-radius: 14px;
        }

        .support-card__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.2rem;
            border-bottom: 1px solid var(--app-border, #e2e8f0);
            background: color-mix(
                in srgb,
                var(--app-card-bg, #fff) 94%,
                var(--app-primary, #1687f8)
            );
        }

        .support-card__heading,
        .support-card__meta-item {
            display: grid;
            gap: .3rem;
        }

        .support-card__id {
            font-size: 1rem;
            font-weight: 750;
        }

        .support-card__submitted {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            color: var(--app-text-muted, #64748b);
            font-size: .84rem;
        }

        .support-card__body {
            display: grid;
            gap: 1rem;
            padding: 1.2rem;
        }

        .support-card__meta {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }

        .support-card__meta-item {
            align-content: start;
            min-width: 0;
        }

        .support-card__email {
            overflow-wrap: anywhere;
        }

        .support-label {
            display: block;
            margin: 0;
            color: var(--app-text-muted, #64748b);
            font-size: .8rem;
            font-weight: 700;
        }

        .support-muted {
            color: var(--app-text-muted, #64748b);
            font-size: .84rem;
        }

        .support-message,
        .support-previous-reply {
            padding: 1rem;
            border: 1px solid var(--app-border, #e2e8f0);
            border-radius: 12px;
            background: var(--app-page-bg, #f4f7fa);
            color: var(--app-text, #0f172a);
        }

        .support-previous-reply {
            border-left: 3px solid var(--app-primary, #1687f8);
        }

        .support-message .support-label,
        .support-previous-reply .support-label {
            color: var(--app-text-muted, #64748b);
        }

        .support-message p,
        .support-previous-reply p {
            margin: .45rem 0 0;
            color: var(--app-text, #0f172a);
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        .support-attachments {
            display: grid;
            gap: .55rem;
        }

        .support-attachments__list {
            display: grid;
            gap: .5rem;
        }

        .support-attachment {
            overflow: hidden;
            border: 1px solid var(--app-border, #e2e8f0);
            border-radius: 10px;
            background: var(--app-card-bg, #fff);
            color: var(--app-text, #0f172a);
        }

        .support-attachment__summary {
            display: flex;
            align-items: center;
            gap: .65rem;
            min-height: 46px;
            padding: .65rem .85rem;
            color: var(--app-text, #0f172a);
            cursor: pointer;
            list-style: none;
        }

        .support-attachment__summary::-webkit-details-marker {
            display: none;
        }

        .support-attachment__summary > i:first-child {
            color: var(--app-primary, #1687f8);
        }

        .support-attachment__name {
            flex: 1;
            overflow-wrap: anywhere;
            font-weight: 600;
        }

        .support-attachment__action {
            color: var(--app-primary, #1687f8);
            font-size: .82rem;
            font-weight: 650;
        }

        .support-attachment__chevron {
            color: var(--app-text-muted, #64748b);
            transition: transform .15s ease;
        }

        .support-attachment[open] .support-attachment__chevron {
            transform: rotate(180deg);
        }

        .support-attachment__preview {
            padding: .85rem;
            border-top: 1px solid var(--app-border, #e2e8f0);
            background: var(--app-page-bg, #f4f7fa);
            color: var(--app-text, #0f172a);
            text-align: center;
        }

        .support-attachment__preview img,
        .support-attachment__preview video {
            display: block;
            max-width: 100%;
            max-height: 560px;
            margin: 0 auto;
            border-radius: 8px;
        }

        .support-attachment__preview iframe {
            width: 100%;
            height: 560px;
            border: 0;
            border-radius: 8px;
            background: #fff;
        }

        .support-attachment__unsupported {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            padding: 1.5rem;
            color: var(--app-text-muted, #64748b);
        }

        .support-attachment__unsupported i {
            color: var(--app-primary, #1687f8);
            font-size: 1.25rem;
        }

        .support-process {
            display: grid;
            gap: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--app-border, #e2e8f0);
        }

        .support-process__title h4 {
            margin: 0;
            font-size: 1rem;
            font-weight: 750;
        }

        .support-process__title p {
            margin: .25rem 0 0;
            color: var(--app-text-muted, #64748b);
            font-size: .86rem;
        }

        .support-process__reply textarea {
            min-height: 112px;
            resize: vertical;
        }

        .support-process__actions {
            display: flex;
            justify-content: flex-end;
            gap: .6rem;
        }

        .support-process__actions .btn {
            min-height: 42px;
        }

        .support-completed {
            display: flex;
            align-items: center;
            gap: .55rem;
            padding: .9rem 1rem;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            background: #f0fdf4;
            color: #166534;
            font-weight: 650;
        }

        .support-completed i {
            color: #16a34a;
        }

        .support-empty {
            padding: 3rem 1.25rem;
            text-align: center;
        }

        .support-empty__icon {
            display: inline-grid;
            width: 54px;
            height: 54px;
            margin-bottom: .9rem;
            place-items: center;
            border-radius: 16px;
            color: var(--app-primary, #1687f8);
            background: var(--app-primary-light, #ebf5ff);
            font-size: 1.4rem;
        }

        .support-empty h3 {
            font-size: 1.05rem;
            font-weight: 750;
        }

        .support-empty p {
            color: var(--app-text-muted, #64748b);
        }

        .support-pagination {
            margin-top: .25rem;
        }

        [data-bs-theme="dark"] .support-message,
        [data-bs-theme="dark"] .support-previous-reply,
        [data-bs-theme="dark"] .support-attachment__preview {
            border-color: #334155 !important;
            background: #172338 !important;
            color: #e2e8f0 !important;
        }

        [data-bs-theme="dark"] .support-message .support-label,
        [data-bs-theme="dark"] .support-previous-reply .support-label {
            color: #a9bdd4 !important;
        }

        [data-bs-theme="dark"] .support-message p,
        [data-bs-theme="dark"] .support-previous-reply p,
        [data-bs-theme="dark"] .support-attachment__unsupported {
            color: #e2e8f0 !important;
        }

        [data-bs-theme="dark"] .support-attachment {
            border-color: #334155;
            background: #1e293b;
            color: #e2e8f0;
        }

        [data-bs-theme="dark"] .support-attachment__summary {
            color: #e2e8f0;
        }

        [data-bs-theme="dark"] .support-completed {
            border-color: #166534;
            background: #132b22;
            color: #bbf7d0;
        }

        @media (max-width: 767.98px) {
            .support-filter {
                grid-template-columns: 1fr;
            }

            .support-filter__actions .btn {
                flex: 1;
            }

            .support-card__meta {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 575.98px) {
            .support-card__header,
            .support-card__body {
                padding: .9rem;
            }

            .support-card__meta {
                grid-template-columns: 1fr;
            }

            .support-process__actions {
                justify-content: stretch;
            }

            .support-process__actions .btn {
                width: 100%;
            }

            .support-attachment__preview iframe {
                height: 400px;
            }
        }
    </style>
@endsection
