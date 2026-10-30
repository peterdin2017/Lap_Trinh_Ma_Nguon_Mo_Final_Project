@extends('store.layouts.app')

@section('title', 'Trung tâm hỗ trợ')

@section('content')
@php
    $topicIcons = [
        'account' => 'bi-person-circle',
        'order' => 'bi-receipt',
        'payment' => 'bi-credit-card',
        'shipping' => 'bi-truck',
        'return' => 'bi-arrow-repeat',
        'product' => 'bi-box-seam',
        'other' => 'bi-chat-dots',
    ];
@endphp

<div class="support-page">
    <section class="support-hero">
        <div class="container support-container">
            <span class="support-hero__eyebrow">
                <i class="bi bi-headset me-1" aria-hidden="true"></i>
                TRUNG TÂM HỖ TRỢ
            </span>

            <h1>Chúng tôi có thể giúp gì cho bạn?</h1>
            <p>Tìm câu trả lời nhanh hoặc gửi yêu cầu để đội ngũ hỗ trợ tiếp nhận.</p>

            <form action="{{ route('store.support.index') }}" method="GET" class="support-search">
                <i class="bi bi-search support-search__icon" aria-hidden="true"></i>

                <input
                    type="search"
                    name="q"
                    value="{{ $keyword }}"
                    placeholder="Tìm theo đơn hàng, thanh toán, tài khoản..."
                    aria-label="Tìm câu hỏi hỗ trợ"
                >

                @if ($keyword !== '')
                    <a href="{{ route('store.support.index') }}" class="support-search__clear" aria-label="Xóa từ khóa">
                        <i class="bi bi-x-circle" aria-hidden="true"></i>
                    </a>
                @endif

                <button type="submit">Tìm kiếm</button>
            </form>

            <div class="support-hero__note">
                <i class="bi bi-lightning-charge-fill" aria-hidden="true"></i>
                Chọn câu hỏi có sẵn để xem hướng dẫn ngay
            </div>
        </div>
    </section>

    <main class="container support-container support-content">
        @if (session('support_success'))
            <div class="alert alert-success alert-dismissible fade show support-alert" role="status">
                <i class="bi bi-check-circle-fill me-2" aria-hidden="true"></i>
                {{ session('support_success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger support-alert" role="alert">
                Có lỗi khi gửi yêu cầu hỗ trợ. Vui lòng thử lại.
            </div>
        @endif

        <section class="support-section">
            <div class="support-section__heading">
                <div>
                    <span class="support-kicker">BẮT ĐẦU TỪ ĐÂY</span>
                    <h2>Danh mục hỗ trợ</h2>
                    <p>Chọn chủ đề gần nhất với vấn đề bạn đang gặp.</p>
                </div>
            </div>

            <div class="support-topics">
                @foreach ($topics as $key => $label)
                    <a
                        href="{{ route('store.support.create', ['topic' => $key]) }}"
                        class="support-topic-card"
                    >
                        <span class="support-topic-card__icon">
                            <i class="bi {{ $topicIcons[$key] ?? 'bi-chat-dots' }}" aria-hidden="true"></i>
                        </span>
                        <span class="support-topic-card__text">{{ $label }}</span>
                        <i class="bi bi-arrow-up-right support-topic-card__arrow" aria-hidden="true"></i>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="support-section support-faq-section">
            <div class="support-section__heading support-section__heading--row">
                <div>
                    <span class="support-kicker">GIẢI ĐÁP NHANH</span>
                    <h2>Câu hỏi thường gặp</h2>
                    <p>Các hướng dẫn phổ biến được tổng hợp tại đây.</p>
                </div>

                <span class="support-count">
                    {{ count($faqs) }} câu hỏi
                </span>
            </div>

            <div class="support-faqs">
                @forelse ($faqs as $index => $faq)
                    <details class="support-faq">
                        <summary>
                            <span class="support-faq__number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="support-faq__question">{{ $faq['question'] }}</span>
                            <i class="bi bi-chevron-down support-faq__chevron" aria-hidden="true"></i>
                        </summary>

                        <div class="support-faq__answer">
                            <p>{{ $faq['answer'] }}</p>

                            <a href="{{ route('store.support.create', ['topic' => $faq['topic']]) }}">
                                Vẫn cần trợ giúp?
                                <span>Gửi yêu cầu hỗ trợ</span>
                                <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    </details>
                @empty
                    <div class="support-empty">
                        <span class="support-empty__icon">
                            <i class="bi bi-search" aria-hidden="true"></i>
                        </span>
                        <h3>Chưa tìm thấy câu trả lời phù hợp</h3>
                        <p>Thử từ khóa khác hoặc gửi yêu cầu để chúng tôi hỗ trợ bạn.</p>
                        <a href="{{ route('store.support.create') }}" class="btn btn-primary">
                            Gửi yêu cầu hỗ trợ
                        </a>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="support-contact">
            <div class="support-contact__icon">
                <i class="bi bi-headset" aria-hidden="true"></i>
            </div>

            <div class="support-contact__text">
                <h2>Bạn vẫn cần hỗ trợ?</h2>
                <p>Gửi thông tin chi tiết để bộ phận chăm sóc khách hàng tiếp nhận và xử lý.</p>
            </div>

            <a href="{{ route('store.support.create') }}" class="btn support-contact__button">
                <i class="bi bi-send me-2" aria-hidden="true"></i>
                Gửi yêu cầu hỗ trợ
            </a>
        </section>
    </main>
</div>

<style>
    .support-page {
        min-height: 70vh;
        padding-bottom: 48px;
        background: #f5f9ff;
        color: #183153;
    }

    .support-container {
        max-width: 1120px;
    }

    .support-hero {
        position: relative;
        overflow: hidden;
        padding: 54px 0 48px;
        background:
            radial-gradient(circle at 85% 20%, rgba(255,255,255,.18), transparent 22%),
            linear-gradient(120deg, #0878e8, #26a0ff);
        color: #fff;
        text-align: center;
    }

    .support-hero::after {
        position: absolute;
        right: 8%;
        bottom: -110px;
        width: 260px;
        height: 260px;
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 50%;
        content: "";
    }

    .support-hero__eyebrow,
    .support-kicker {
        display: inline-block;
        color: #1687f8;
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .12em;
    }

    .support-hero__eyebrow {
        margin-bottom: 12px;
        color: #d9efff;
    }

    .support-hero h1 {
        margin: 0;
        font-size: clamp(1.8rem, 4vw, 2.6rem);
        font-weight: 800;
    }

    .support-hero > .container > p {
        margin: 10px 0 24px;
        color: rgba(255,255,255,.88);
    }

    .support-search {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        max-width: 760px;
        min-height: 60px;
        margin: 0 auto;
        padding: 6px;
        border: 1px solid rgba(255,255,255,.7);
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 12px 28px rgba(0, 49, 108, .18);
    }

    .support-search__icon {
        margin: 0 12px;
        color: #7890aa;
        font-size: 1.1rem;
    }

    .support-search input {
        flex: 1;
        min-width: 0;
        border: 0;
        outline: 0;
        color: #183153;
    }

    .support-search input::placeholder {
        color: #91a1b5;
    }

    .support-search__clear {
        padding: 8px;
        color: #7d8da2;
    }

    .support-search button {
        align-self: stretch;
        padding: 0 22px;
        border: 0;
        border-radius: 8px;
        background: #1687f8;
        color: #fff;
        font-weight: 700;
    }

    .support-search button:hover {
        background: #0874df;
    }

    .support-hero__note {
        margin-top: 16px;
        color: rgba(255,255,255,.88);
        font-size: .88rem;
    }

    .support-content {
        padding-top: 34px;
    }

    .support-alert {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(22, 54, 92, .06);
    }

    .support-section {
        margin-bottom: 34px;
    }

    .support-section__heading {
        margin-bottom: 18px;
    }

    .support-section__heading h2 {
        margin: 5px 0;
        color: #183153;
        font-size: 1.45rem;
        font-weight: 800;
    }

    .support-section__heading p {
        margin: 0;
        color: #70839c;
    }

    .support-section__heading--row {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 16px;
    }

    .support-count {
        padding: 7px 12px;
        border: 1px solid #d9e9fb;
        border-radius: 999px;
        background: #fff;
        color: #53708f;
        font-size: .82rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .support-topics {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .support-topic-card {
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 82px;
        padding: 14px;
        border: 1px solid #e0eaf5;
        border-radius: 12px;
        background: #fff;
        color: #244363;
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(32, 73, 119, .035);
        transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
    }

    .support-topic-card:hover {
        transform: translateY(-2px);
        border-color: #9bcfff;
        color: #0878e8;
        box-shadow: 0 10px 22px rgba(30, 112, 196, .1);
    }

    .support-topic-card__icon {
        display: grid;
        width: 44px;
        height: 44px;
        flex: 0 0 44px;
        place-items: center;
        border-radius: 12px;
        background: #eaf5ff;
        color: #1687f8;
        font-size: 1.15rem;
    }

    .support-topic-card__text {
        flex: 1;
        font-weight: 700;
        line-height: 1.35;
    }

    .support-topic-card__arrow {
        color: #9aacc0;
        font-size: .9rem;
    }

    .support-faqs {
        overflow: hidden;
        border: 1px solid #e0eaf5;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 5px 18px rgba(32, 73, 119, .04);
    }

    .support-faq + .support-faq {
        border-top: 1px solid #edf2f8;
    }

    .support-faq summary {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 20px;
        cursor: pointer;
        list-style: none;
    }

    .support-faq summary::-webkit-details-marker {
        display: none;
    }

    .support-faq__number {
        color: #91a9c3;
        font-size: .78rem;
        font-weight: 800;
    }

    .support-faq__question {
        flex: 1;
        color: #284664;
        font-weight: 600;
    }

    .support-faq__chevron {
        color: #7c91a9;
        transition: transform .2s ease;
    }

    .support-faq[open] .support-faq__chevron {
        transform: rotate(180deg);
    }

    .support-faq[open] summary {
        color: #0878e8;
    }

    .support-faq__answer {
        padding: 0 20px 18px 54px;
        color: #647991;
        line-height: 1.7;
    }

    .support-faq__answer p {
        margin-bottom: 10px;
    }

    .support-faq__answer a {
        color: #0878e8;
        font-size: .92rem;
        font-weight: 700;
        text-decoration: none;
    }

    .support-faq__answer a span {
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    .support-faq__answer a i {
        margin-left: 5px;
    }

    .support-empty {
        padding: 42px 20px;
        text-align: center;
    }

    .support-empty__icon {
        display: inline-grid;
        width: 54px;
        height: 54px;
        margin-bottom: 12px;
        place-items: center;
        border-radius: 50%;
        background: #edf6ff;
        color: #1687f8;
        font-size: 1.25rem;
    }

    .support-empty h3 {
        font-size: 1.1rem;
        font-weight: 800;
    }

    .support-empty p {
        color: #70839c;
    }

    .support-contact {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 24px;
        border: 1px solid #cfe6ff;
        border-radius: 16px;
        background: linear-gradient(110deg, #eaf5ff, #f8fbff);
    }

    .support-contact__icon {
        display: grid;
        width: 56px;
        height: 56px;
        flex: 0 0 56px;
        place-items: center;
        border-radius: 15px;
        background: #1687f8;
        color: #fff;
        font-size: 1.4rem;
    }

    .support-contact__text {
        flex: 1;
    }

    .support-contact h2 {
        margin: 0 0 4px;
        font-size: 1.15rem;
        font-weight: 800;
    }

    .support-contact p {
        margin: 0;
        color: #70839c;
    }

    .support-contact__button {
        padding: 11px 16px;
        border: 0;
        border-radius: 9px;
        background: #1687f8;
        color: #fff;
        font-weight: 700;
        white-space: nowrap;
    }

    .support-contact__button:hover {
        background: #0874df;
        color: #fff;
    }

    @media (max-width: 767.98px) {
        .support-hero {
            padding: 38px 0;
        }

        .support-search {
            min-height: 52px;
        }

        .support-search button {
            padding: 0 13px;
        }

        .support-topics {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .support-topic-card {
            min-height: 74px;
            gap: 9px;
            padding: 10px;
        }

        .support-topic-card__icon {
            width: 38px;
            height: 38px;
            flex-basis: 38px;
        }

        .support-section__heading--row {
            align-items: start;
        }

        .support-faq summary {
            gap: 10px;
            padding: 16px 14px;
        }

        .support-faq__answer {
            padding: 0 14px 16px 42px;
        }

        .support-contact {
            align-items: flex-start;
            flex-wrap: wrap;
            padding: 18px;
        }

        .support-contact__text {
            flex-basis: calc(100% - 76px);
        }

        .support-contact__button {
            width: 100%;
        }
    }

    @media (max-width: 380px) {
        .support-topics {
            grid-template-columns: 1fr;
        }

        .support-search__icon {
            margin: 0 8px;
        }

        .support-search button {
            padding: 0 10px;
            font-size: .85rem;
        }
    }
</style>
@endsection
