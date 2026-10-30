@extends('store.layouts.app')

@section('title', 'Thông báo')

@section('content')
<div class="container py-4" style="max-width: 900px">
    <div class="d-flex align-items-center gap-3 mb-4">
        <span class="notification-heading-icon">
            <i class="bi bi-bell" aria-hidden="true"></i>
        </span>
        <div>
            <h1 class="h3 fw-bold mb-1">Thông báo</h1>
            <p class="text-secondary mb-0">Cập nhật từ bộ phận hỗ trợ khách hàng.</p>
        </div>
    </div>

    <div class="notification-list">
        @forelse ($notifications as $notification)
            <article class="notification-item">
                <span class="notification-item__icon">
                    <i class="bi bi-headset" aria-hidden="true"></i>
                </span>

                <div class="notification-item__body">
                    <h2>{{ $notification->title }}</h2>
                    <p>{{ $notification->message }}</p>

                    <div class="notification-item__meta">
                        {{ $notification->created_at?->format('d/m/Y H:i') }}

                        @if ($notification->support_request_id)
                            <span>· Mã yêu cầu #{{ $notification->support_request_id }}</span>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <div class="notification-empty">
                <i class="bi bi-bell-slash" aria-hidden="true"></i>
                <h2>Chưa có thông báo</h2>
                <p>Khi Admin phản hồi yêu cầu hỗ trợ, thông báo sẽ xuất hiện ở đây.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
</div>

<style>
    .notification-heading-icon,
    .notification-item__icon {
        display: grid;
        flex: 0 0 46px;
        width: 46px;
        height: 46px;
        place-items: center;
        border-radius: 13px;
        background: #eaf5ff;
        color: #1687f8;
        font-size: 1.2rem;
    }

    .notification-list {
        display: grid;
        gap: 12px;
    }

    .notification-item {
        display: flex;
        gap: 14px;
        padding: 18px;
        border: 1px solid #e1eaf4;
        border-radius: 13px;
        background: #fff;
        box-shadow: 0 4px 14px rgba(30, 75, 120, .04);
    }

    .notification-item__body {
        min-width: 0;
    }

    .notification-item h2 {
        margin: 0 0 6px;
        color: #183153;
        font-size: 1rem;
        font-weight: 750;
    }

    .notification-item p {
        margin: 0 0 8px;
        color: #405873;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    .notification-item__meta {
        color: #8394a8;
        font-size: .82rem;
    }

    .notification-empty {
        padding: 48px 20px;
        border: 1px dashed #cad8e7;
        border-radius: 14px;
        background: #fff;
        color: #70839a;
        text-align: center;
    }

    .notification-empty > i {
        color: #1687f8;
        font-size: 2rem;
    }

    .notification-empty h2 {
        margin: 12px 0 5px;
        color: #183153;
        font-size: 1.1rem;
    }

    .notification-empty p {
        margin: 0;
    }
</style>
@endsection
