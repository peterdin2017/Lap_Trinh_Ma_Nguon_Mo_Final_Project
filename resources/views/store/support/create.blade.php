@extends('store.layouts.app')

@section('title', 'Gửi yêu cầu hỗ trợ')

@section('content')
<div class="support-form-page">
    <div class="container support-form-container">
        <a href="{{ route('store.support.index') }}" class="support-back-link">
            <i class="bi bi-arrow-left" aria-hidden="true"></i>
            Trung tâm hỗ trợ
        </a>

        <div class="support-form-layout">
            <aside class="support-form-aside">
                <span class="support-form-aside__icon">
                    <i class="bi bi-headset" aria-hidden="true"></i>
                </span>

                <span class="support-form-aside__eyebrow">CHĂM SÓC KHÁCH HÀNG</span>
                <h1>Gửi yêu cầu hỗ trợ</h1>
                <p>Cho chúng tôi biết vấn đề bạn đang gặp. Đội ngũ hỗ trợ sẽ tiếp nhận và xử lý.</p>

                <div class="support-form-aside__account">
                    <span class="support-form-aside__account-icon">
                        <i class="bi bi-person-circle" aria-hidden="true"></i>
                    </span>
                    <span>
                        <small>Gửi yêu cầu với tài khoản</small>
                        <strong>{{ $account->email }}</strong>
                    </span>
                </div>

                <div class="support-form-aside__tip">
                    <i class="bi bi-shield-check" aria-hidden="true"></i>
                    Thông tin của bạn chỉ được dùng để xử lý yêu cầu hỗ trợ.
                </div>
            </aside>

            <section class="support-form-card">
                <div class="support-form-card__heading">
                    <span class="support-form-step">YÊU CẦU HỖ TRỢ</span>
                    <h2>Mô tả vấn đề của bạn</h2>
                    <p>Các mục có dấu <span class="text-danger">*</span> là bắt buộc.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        Có thông tin chưa hợp lệ. Vui lòng kiểm tra các mục được báo lỗi.
                    </div>
                @endif

                <form method="POST" action="{{ route('store.support.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label" for="supportTopic">
                            Bạn cần hỗ trợ với vai trò nào?
                        </label>

                        <div class="support-role-display">
                            <i class="bi {{ $account->role === 'seller' ? 'bi-shop' : 'bi-bag' }}" aria-hidden="true"></i>
                            <span>{{ $account->role === 'seller' ? 'Người bán' : 'Người mua' }}</span>
                            <span class="support-role-display__note">Theo tài khoản đang đăng nhập</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="supportTopic">
                            Bạn thắc mắc về vấn đề nào? <span class="text-danger">*</span>
                        </label>

                        <select
                            id="supportTopic"
                            name="topic"
                            class="form-select @error('topic') is-invalid @enderror"
                            required
                        >
                            <option value="">Chọn vấn đề</option>
                            @foreach ($topics as $key => $label)
                                <option value="{{ $key }}" @selected(old('topic', request('topic')) === $key)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        @error('topic')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="supportMessage">
                            Nội dung cần hỗ trợ <span class="text-danger">*</span>
                        </label>
                        <div class="form-text mb-2">
                            Mô tả tình huống, thời điểm xảy ra và thông tin liên quan để Admin dễ kiểm tra.
                        </div>

                        <textarea
                            id="supportMessage"
                            name="message"
                            rows="6"
                            maxlength="5000"
                            class="form-control @error('message') is-invalid @enderror"
                            placeholder="Nhập nội dung chi tiết..."
                            required
                        >{{ old('message') }}</textarea>

                        <div class="support-character-count">
                            Tối đa 5.000 ký tự
                        </div>

                        @error('message')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="supportFiles">
                            Tải lên hình ảnh hoặc tệp liên quan
                        </label>
                        <div class="form-text mb-2">
                            Không bắt buộc. Tối đa 5 tệp, mỗi tệp không quá 10 MB.
                            Hỗ trợ JPG, PNG, PDF, MP4, XLS, XLSX, CSV, ZIP, DOC và DOCX.
                        </div>

                        <label class="support-upload" for="supportFiles">
                            <i class="bi bi-cloud-arrow-up" aria-hidden="true"></i>
                            <span><strong>Chọn tệp</strong> từ thiết bị của bạn</span>
                            <input
                                id="supportFiles"
                                type="file"
                                name="attachments[]"
                                accept=".jpg,.jpeg,.png,.pdf,.mp4,.xls,.xlsx,.csv,.zip,.doc,.docx"
                                multiple
                            >
                        </label>

                        <div id="supportFileNames" class="support-file-names"></div>

                        @error('attachments')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                        @error('attachments.*')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="support-consent">
                        <input
                            id="supportConsent"
                            name="consent"
                            type="checkbox"
                            value="1"
                            class="form-check-input @error('consent') is-invalid @enderror"
                            @checked(old('consent'))
                            required
                        >
                        <label for="supportConsent">
                            Tôi đồng ý để website sử dụng thông tin đã cung cấp nhằm tiếp nhận và xử lý yêu cầu này.
                            <span class="text-danger">*</span>
                        </label>
                    </div>

                    @error('consent')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror

                    <div class="support-form-actions">
                        <a href="{{ route('store.support.index') }}" class="btn support-cancel-button">
                            Hủy
                        </a>

                        <button type="submit" class="btn support-submit-button">
                            <i class="bi bi-send me-2" aria-hidden="true"></i>
                            Gửi yêu cầu
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>

<style>
    .support-form-page {
        min-height: 70vh;
        padding: 30px 0 56px;
        background: #f5f9ff;
        color: #183153;
    }

    .support-form-container {
        max-width: 1080px;
    }

    .support-back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 18px;
        color: #3477b9;
        font-weight: 600;
        text-decoration: none;
    }

    .support-back-link:hover {
        color: #0878e8;
    }

    .support-form-layout {
        display: grid;
        grid-template-columns: minmax(250px, .78fr) minmax(0, 1.6fr);
        overflow: hidden;
        border: 1px solid #e1eaf4;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 14px 38px rgba(36, 76, 119, .08);
    }

    .support-form-aside {
        padding: 34px 28px;
        background:
            radial-gradient(circle at 90% 10%, rgba(255,255,255,.2), transparent 25%),
            linear-gradient(155deg, #0878e8, #36a3ff);
        color: #fff;
    }

    .support-form-aside__icon {
        display: grid;
        width: 58px;
        height: 58px;
        margin-bottom: 28px;
        place-items: center;
        border: 1px solid rgba(255,255,255,.35);
        border-radius: 16px;
        background: rgba(255,255,255,.16);
        font-size: 1.55rem;
    }

    .support-form-aside__eyebrow {
        color: #d9efff;
        font-size: .7rem;
        font-weight: 800;
        letter-spacing: .12em;
    }

    .support-form-aside h1 {
        margin: 8px 0 12px;
        font-size: 1.8rem;
        font-weight: 800;
    }

    .support-form-aside > p {
        color: rgba(255,255,255,.88);
        line-height: 1.65;
    }

    .support-form-aside__account {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-top: 28px;
        padding: 13px;
        border: 1px solid rgba(255,255,255,.25);
        border-radius: 12px;
        background: rgba(255,255,255,.12);
        overflow-wrap: anywhere;
    }

    .support-form-aside__account-icon {
        font-size: 1.4rem;
    }

    .support-form-aside__account small,
    .support-form-aside__account strong {
        display: block;
    }

    .support-form-aside__account small {
        margin-bottom: 3px;
        color: rgba(255,255,255,.78);
    }

    .support-form-aside__tip {
        display: flex;
        gap: 9px;
        margin-top: 22px;
        color: rgba(255,255,255,.9);
        font-size: .88rem;
        line-height: 1.5;
    }

    .support-form-card {
        padding: 34px 38px;
    }

    .support-form-card__heading {
        margin-bottom: 26px;
    }

    .support-form-step {
        color: #1687f8;
        font-size: .7rem;
        font-weight: 800;
        letter-spacing: .12em;
    }

    .support-form-card__heading h2 {
        margin: 6px 0;
        color: #183153;
        font-size: 1.45rem;
        font-weight: 800;
    }

    .support-form-card__heading p {
        margin: 0;
        color: #74869c;
    }

    .support-form-card .form-label {
        color: #294661;
        font-weight: 700;
    }

    .support-form-card .form-control,
    .support-form-card .form-select {
        min-height: 46px;
        border-color: #dce6f0;
        border-radius: 9px;
    }

    .support-form-card textarea.form-control {
        min-height: 150px;
        resize: vertical;
    }

    .support-form-card .form-control:focus,
    .support-form-card .form-select:focus {
        border-color: #76baff;
        box-shadow: 0 0 0 .2rem rgba(22, 135, 248, .12);
    }

    .support-role-display {
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 46px;
        padding: 9px 12px;
        border: 1px solid #dce6f0;
        border-radius: 9px;
        background: #f8fbff;
        color: #294661;
        font-weight: 700;
    }

    .support-role-display > i {
        color: #1687f8;
        font-size: 1.1rem;
    }

    .support-role-display__note {
        margin-left: auto;
        color: #8a9bb0;
        font-size: .78rem;
        font-weight: 400;
    }

    .support-character-count {
        margin-top: 6px;
        color: #8a9bb0;
        font-size: .78rem;
        text-align: right;
    }

    .support-upload {
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 70px;
        padding: 14px;
        border: 1px dashed #a9c9e9;
        border-radius: 10px;
        background: #f8fbff;
        color: #617b96;
        cursor: pointer;
    }

    .support-upload:hover {
        border-color: #1687f8;
        background: #f0f8ff;
    }

    .support-upload > i {
        color: #1687f8;
        font-size: 1.5rem;
    }

    .support-upload strong {
        color: #0878e8;
    }

    .support-upload input {
        position: absolute;
        width: 1px;
        height: 1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
    }

    .support-file-names {
        margin-top: 8px;
        color: #617b96;
        font-size: .85rem;
    }

    .support-consent {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 14px;
        border-radius: 10px;
        background: #f5f9ff;
        color: #526b84;
        font-size: .9rem;
        line-height: 1.5;
    }

    .support-consent .form-check-input {
        flex: 0 0 auto;
        margin-top: 3px;
    }

    .support-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 26px;
        padding-top: 20px;
        border-top: 1px solid #edf2f8;
    }

    .support-cancel-button,
    .support-submit-button {
        min-height: 44px;
        padding: 10px 18px;
        border-radius: 9px;
        font-weight: 700;
    }

    .support-cancel-button {
        border: 1px solid #d5e0eb;
        color: #58718b;
    }

    .support-submit-button {
        border: 0;
        background: #1687f8;
        color: #fff;
    }

    .support-submit-button:hover {
        background: #0874df;
        color: #fff;
    }

    @media (max-width: 767.98px) {
        .support-form-page {
            padding-top: 20px;
        }

        .support-form-layout {
            grid-template-columns: 1fr;
            border-radius: 14px;
        }

        .support-form-aside {
            padding: 24px;
        }

        .support-form-aside__icon {
            width: 48px;
            height: 48px;
            margin-bottom: 18px;
        }

        .support-form-aside h1 {
            font-size: 1.5rem;
        }

        .support-form-card {
            padding: 24px 20px;
        }

        .support-role-display {
            flex-wrap: wrap;
        }

        .support-role-display__note {
            width: 100%;
            margin-left: 29px;
        }
    }

    @media (max-width: 420px) {
        .support-form-actions {
            flex-direction: column-reverse;
        }

        .support-form-actions .btn {
            width: 100%;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.querySelector('#supportFiles');
    const output = document.querySelector('#supportFileNames');

    input?.addEventListener('change', () => {
        if (!output) return;

        const files = [...input.files];

        if (files.length > 5) {
            alert('Bạn chỉ được chọn tối đa 5 tệp.');
            input.value = '';
            output.textContent = '';
            return;
        }

        output.textContent = files.length
            ? `Đã chọn ${files.length} tệp: ${files.map(file => file.name).join(', ')}`
            : '';
    });
});
</script>
@endsection
