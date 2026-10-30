@extends('seller.layouts.app')

@section('title', 'Thông tin Shop')

@section('content')
    @php
        $avatarUrl = $seller->avatar
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($seller->avatar)
            : null;
    @endphp

    <main class="seller-shop-profile">
        <header class="seller-shop-profile__heading">
            <div>
                <h2>Thông tin Shop</h2>
                <p>Cập nhật ảnh đại diện, tên và địa chỉ cửa hàng.</p>
            </div>
        </header>

        <section class="seller-shop-profile__card">
            <form
                method="POST"
                action="{{ route('seller.shop-information.update') }}"
                enctype="multipart/form-data"
            >
                @csrf
                @method('PUT')

                <div class="seller-shop-profile__avatar-section">
                    <div class="seller-shop-profile__avatar-wrap">
                        <img
                            id="shop-avatar-preview"
                            src="{{ $avatarUrl ?? '' }}"
                            alt="Ảnh đại diện Shop"
                            class="seller-shop-profile__avatar {{ $avatarUrl ? '' : 'd-none' }}"
                        >

                        <div
                            id="shop-avatar-placeholder"
                            class="seller-shop-profile__avatar-placeholder {{ $avatarUrl ? 'd-none' : '' }}"
                            aria-hidden="true"
                        >
                            <i class="bi bi-shop"></i>
                        </div>
                    </div>

                    <div class="seller-shop-profile__avatar-content">
                        <label for="avatar" class="form-label">Ảnh đại diện Shop</label>
                        <input
                            id="avatar"
                            name="avatar"
                            type="file"
                            accept="image/jpeg,image/png,image/webp"
                            class="form-control @error('avatar') is-invalid @enderror"
                        >
                        <div class="form-text">JPG, PNG hoặc WEBP; dung lượng tối đa 2 MB.</div>
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="seller-shop-profile__divider">

                <div class="seller-shop-profile__fields">
                    <div class="seller-shop-profile__field">
                        <label for="email" class="form-label">Email đăng nhập</label>
                        <input
                            id="email"
                            type="email"
                            class="form-control"
                            value="{{ $account->email }}"
                            readonly
                        >
                        <div class="form-text">Email đăng nhập không thể chỉnh sửa tại trang này.</div>
                    </div>

                    <div class="seller-shop-profile__field">
                        <label for="shop_name" class="form-label">
                            Tên Shop <span class="text-danger">*</span>
                        </label>
                        <input
                            id="shop_name"
                            name="shop_name"
                            type="text"
                            maxlength="100"
                            value="{{ old('shop_name', $seller->shop_name) }}"
                            class="form-control @error('shop_name') is-invalid @enderror"
                            required
                        >
                        @error('shop_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="seller-shop-profile__field seller-shop-profile__field--full">
                        <label for="shop_address" class="form-label">Địa chỉ cụ thể</label>
                        <input
                            id="shop_address"
                            name="shop_address"
                            type="text"
                            maxlength="255"
                            value="{{ old('shop_address', $seller->shop_address) }}"
                            class="form-control @error('shop_address') is-invalid @enderror"
                        >
                        @error('shop_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="seller-shop-profile__field">
                        <label for="shop_province" class="form-label">Tỉnh/Thành phố</label>
                        <input
                            id="shop_province"
                            name="shop_province"
                            type="text"
                            maxlength="100"
                            value="{{ old('shop_province', $seller->shop_province) }}"
                            class="form-control @error('shop_province') is-invalid @enderror"
                        >
                        @error('shop_province')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="seller-shop-profile__field">
                        <label for="shop_district" class="form-label">Quận/Huyện</label>
                        <input
                            id="shop_district"
                            name="shop_district"
                            type="text"
                            maxlength="100"
                            value="{{ old('shop_district', $seller->shop_district) }}"
                            class="form-control @error('shop_district') is-invalid @enderror"
                        >
                        @error('shop_district')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="seller-shop-profile__field">
                        <label for="shop_ward" class="form-label">Phường/Xã</label>
                        <input
                            id="shop_ward"
                            name="shop_ward"
                            type="text"
                            maxlength="100"
                            value="{{ old('shop_ward', $seller->shop_ward) }}"
                            class="form-control @error('shop_ward') is-invalid @enderror"
                        >
                        @error('shop_ward')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="seller-shop-profile__actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>
                        Lưu thông tin
                    </button>
                </div>
            </form>
        </section>
    </main>

    <style>
        .seller-shop-profile {
            display: grid;
            gap: 1rem;
            width: 100%;
            max-width: 1080px;
            margin-inline: auto;
            color: var(--seller-text, #0f172a);
        }

        .seller-shop-profile__heading h2 {
            margin: 0 0 .3rem;
            color: var(--seller-text, #0f172a);
            font-size: 1.5rem;
            font-weight: 750;
        }

        .seller-shop-profile__heading p {
            margin: 0;
            color: var(--seller-text-muted, #64748b);
        }

        .seller-shop-profile__card {
            padding: clamp(1rem, 3vw, 1.75rem);
            border: 1px solid var(--seller-border, #e2e8f0);
            border-radius: 14px;
            background: var(--seller-card-bg, #fff);
            color: var(--seller-text, #0f172a);
            box-shadow: var(--seller-shadow, 0 2px 8px rgba(0, 0, 0, .04));
        }

        .seller-shop-profile__avatar-section {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .seller-shop-profile__avatar-wrap {
            width: 104px;
            height: 104px;
            flex: 0 0 104px;
        }

        .seller-shop-profile__avatar,
        .seller-shop-profile__avatar-placeholder {
            display: grid;
            width: 100%;
            height: 100%;
            place-items: center;
            overflow: hidden;
            border: 1px solid var(--seller-border, #e2e8f0);
            border-radius: 50%;
            background: var(--seller-primary-light, #ebf5ff);
        }

        .seller-shop-profile__avatar {
            object-fit: cover;
        }

        .seller-shop-profile__avatar-placeholder {
            color: var(--seller-primary, #1687f8);
            font-size: 2.3rem;
        }

        .seller-shop-profile__avatar-content {
            width: min(100%, 520px);
        }

        .seller-shop-profile__divider {
            margin: 1.5rem 0;
            border-color: var(--seller-border, #e2e8f0);
            opacity: 1;
        }

        .seller-shop-profile__fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem 1.25rem;
        }

        .seller-shop-profile__field {
            min-width: 0;
        }

        .seller-shop-profile__field--full {
            grid-column: 1 / -1;
        }

        .seller-shop-profile .form-label {
            margin-bottom: .4rem;
            color: var(--seller-text, #0f172a);
            font-weight: 650;
        }

        .seller-shop-profile .form-control {
            min-height: 44px;
            border-color: var(--seller-border, #e2e8f0);
            background-color: var(--seller-card-bg, #fff);
            color: var(--seller-text, #0f172a);
        }

        .seller-shop-profile .form-control:focus {
            border-color: var(--seller-primary, #1687f8);
            box-shadow: 0 0 0 .2rem color-mix(
                in srgb,
                var(--seller-primary, #1687f8) 18%,
                transparent
            );
        }

        .seller-shop-profile .form-control[readonly] {
            background-color: var(--seller-page-bg, #f4f7fa);
        }

        .seller-shop-profile .form-text {
            color: var(--seller-text-muted, #64748b);
        }

        .seller-shop-profile__actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--seller-border, #e2e8f0);
        }

        .seller-shop-profile__actions .btn {
            min-height: 44px;
            padding-inline: 1.25rem;
        }

        [data-theme="dark"] .seller-shop-profile,
        [data-theme="dark"] .seller-shop-profile__heading h2,
        [data-theme="dark"] .seller-shop-profile .form-label {
            color: var(--seller-text, #f8fafc);
        }

        [data-theme="dark"] .seller-shop-profile__card {
            border-color: var(--seller-border, #26364d);
            background-color: var(--seller-card-bg, #1e293b);
            color: var(--seller-text, #f8fafc);
        }

        [data-theme="dark"] .seller-shop-profile .form-control {
            border-color: var(--seller-border, #26364d);
            background-color: var(--seller-card-bg, #1e293b);
            color: var(--seller-text, #f8fafc);
        }

        [data-theme="dark"] .seller-shop-profile .form-control[readonly] {
            background-color: var(--seller-page-bg, #0f172a);
        }

        [data-theme="dark"] .seller-shop-profile .form-control::file-selector-button {
            border-color: var(--seller-border, #26364d);
            background-color: var(--seller-hover-bg, #172a46);
            color: var(--seller-text, #f8fafc);
        }

        [data-theme="dark"] .seller-shop-profile__avatar,
        [data-theme="dark"] .seller-shop-profile__avatar-placeholder {
            border-color: var(--seller-border, #26364d);
            background-color: var(--seller-primary-light, #172a46);
        }

        @media (max-width: 575.98px) {
            .seller-shop-profile__avatar-section {
                align-items: flex-start;
                flex-direction: column;
            }

            .seller-shop-profile__avatar-wrap {
                width: 88px;
                height: 88px;
                flex-basis: 88px;
            }

            .seller-shop-profile__fields {
                grid-template-columns: 1fr;
            }

            .seller-shop-profile__field--full {
                grid-column: auto;
            }

            .seller-shop-profile__actions,
            .seller-shop-profile__actions .btn {
                width: 100%;
            }
        }
    </style>

    <script>
        (() => {
            const input = document.getElementById('avatar');
            const preview = document.getElementById('shop-avatar-preview');
            const placeholder = document.getElementById('shop-avatar-placeholder');

            if (!input || !preview || !placeholder) {
                return;
            }

            input.addEventListener('change', () => {
                const file = input.files && input.files[0];

                if (!file) {
                    return;
                }

                const previewUrl = URL.createObjectURL(file);
                preview.src = previewUrl;
                preview.classList.remove('d-none');
                placeholder.classList.add('d-none');
            });
        })();
    </script>
@endsection
