<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Website Bán hàng Online')</title>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --shop-primary: #1A94FF;
            --shop-primary-hover: #0077E6;
            --shop-primary-light: #EBF5FF;
            --shop-page-bg: #F4F7FA;
            --shop-card-bg: #FFFFFF;
            --shop-text: #0F172A;
            --shop-text-muted: #64748B;
            --shop-border: #E2E8F0;
            --shop-price: #EE4D2D;
            --shop-shadow: 0 2px 8px rgba(0, 0, 0, .04);

            --bs-primary: #1A94FF;
            --bs-primary-rgb: 26, 148, 255;
            --bs-link-color: #1A94FF;
            --bs-link-hover-color: #0077E6;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            margin: 0;
            color: var(--shop-text);
            background: var(--shop-page-bg);
            font-family: Inter, Roboto, "Segoe UI", sans-serif;
        }

        .store-main {
            flex: 1 0 auto;
        }

        .shop-button,
        .btn-primary {
            --bs-btn-color: #fff;
            --bs-btn-bg: var(--shop-primary);
            --bs-btn-border-color: var(--shop-primary);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--shop-primary-hover);
            --bs-btn-hover-border-color: var(--shop-primary-hover);
            --bs-btn-active-bg: var(--shop-primary-hover);
            --bs-btn-active-border-color: var(--shop-primary-hover);
        }

        .btn-outline-primary {
            --bs-btn-color: var(--shop-primary);
            --bs-btn-border-color: var(--shop-primary);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--shop-primary);
            --bs-btn-hover-border-color: var(--shop-primary);
            --bs-btn-active-bg: var(--shop-primary-hover);
            --bs-btn-active-border-color: var(--shop-primary-hover);
        }

        .text-primary,
        .link-primary {
            color: var(--shop-primary) !important;
        }

        .text-secondary {
            color: var(--shop-text-muted) !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--shop-primary);
            box-shadow: 0 0 0 .2rem rgba(26, 148, 255, .16);
        }

        .form-check-input:checked {
            border-color: var(--shop-primary);
            background-color: var(--shop-primary);
        }

        .form-check-input:focus {
            border-color: var(--shop-primary);
            box-shadow: 0 0 0 .2rem rgba(26, 148, 255, .16);
        }

        .page-item.active .page-link {
            color: #fff;
            border-color: var(--shop-primary);
            background-color: var(--shop-primary);
        }

        .page-link {
            color: var(--shop-primary);
        }

        .page-link:hover {
            color: var(--shop-primary-hover);
        }

        .shop-footer {
            flex-shrink: 0;
            color: var(--shop-text);
            background: var(--shop-card-bg);
            border-top: 1px solid var(--shop-border);
        }

        .shop-footer a {
            color: var(--shop-text-muted);
            text-decoration: none;
            transition: color .15s ease;
        }

        .shop-footer a:hover,
        .shop-footer a:focus-visible {
            color: var(--shop-primary);
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    @include('store.layouts.header')

    <main class="store-main">
        @yield('content')
    </main>

    @include('store.layouts.footer')


<style id="retail-redesign-final">
:root{--shop-primary:#086fd8;--shop-primary-hover:#0454af;--shop-primary-light:#eaf4ff;--shop-page-bg:#f4f7fc;--shop-card-bg:#fff;--shop-text:#17243b;--shop-text-muted:#63748c;--shop-border:#dfe7f2;--shop-price:#f66b20;--shop-shadow:0 8px 28px rgba(16,56,115,.075);--bs-primary:#086fd8;--bs-primary-rgb:8,111,216}
body{background:#f4f7fc;color:#17243b;font-family:Inter,'Segoe UI',Arial,sans-serif}
.shop-header{background:linear-gradient(116deg,#06439d 0%,#086edb 53%,#139ded 100%)!important;box-shadow:0 10px 30px rgba(4,63,142,.19)!important;position:relative}
.shop-header>.container{padding-top:19px!important;padding-bottom:17px!important}
.shop-header__logo{letter-spacing:-.45px}.shop-header__logo .bi{background:#ffffff1a;border:1px solid #ffffff35;padding:7px 10px;border-radius:13px}
.shop-header__search{background:#fff!important;border-radius:12px!important;overflow:hidden;box-shadow:0 5px 22px #00235c35;min-height:49px!important}
.shop-header__search .form-control{min-height:49px!important;padding-inline:19px!important;font-size:15px}
.shop-header__search-button{background:#ff7927!important;color:#fff!important;border:0!important;min-width:63px!important;font-size:18px}
.shop-header__search-button:hover{background:#ef6014!important;color:#fff!important}
.shop-header__action{background:#ffffff14!important;border:1px solid #ffffff65!important;border-radius:12px!important;padding:10px 15px!important;min-height:44px!important;font-weight:600!important}
.shop-header__action:hover{background:#ffffff2b!important;color:white!important;transform:translateY(-1px)}
.shop-header__nav{gap:8px!important;margin-top:18px!important;padding-top:5px!important}
.shop-header__nav-link{padding:9px 15px!important;background:#ffffff12;border-radius:9px!important;border-bottom:0!important;color:#fff!important;font-weight:500;font-size:14px;transition:background .2s}
.shop-header__nav-link:hover{background:#ffffff31!important}
.shop-header .dropdown-menu{border:1px solid #e2eaf5!important;border-radius:13px!important;padding:8px!important;box-shadow:0 14px 32px #0b325128!important}
.shop-header .dropdown-item{border-radius:8px;padding:9px 12px}
.store-main{min-width:0}.store-main>.container{padding-top:27px!important;padding-bottom:40px!important}
.store-sidebar{padding:22px!important;border:1px solid #e1e9f3!important;border-radius:17px!important;box-shadow:0 9px 30px #102f5910;background:#fff!important}
.store-sidebar h2{font-size:15px!important;color:#163259}.store-sidebar__section{margin-bottom:21px!important;padding-bottom:19px!important}
.store-sidebar .form-check{padding-top:3px;padding-bottom:3px}.store-sidebar .form-check-input:checked{background-color:#086fd8;border-color:#086fd8}
.filter-category-link{border-radius:9px!important}.filter-category-link:hover{background:#edf5ff!important}
.store-toolbar{padding:21px 24px!important;background:#fff!important;border:1px solid #e1e9f3!important;border-radius:17px!important;box-shadow:0 9px 30px #102f5910!important}
.store-toolbar h1{font-size:22px!important;letter-spacing:-.5px;color:#142944}
.store-sort{background:#f2f6fc;border-radius:11px;padding:4px;gap:2px!important}
.store-sort__link{font-size:14px;padding:10px 13px!important;border-radius:9px!important;white-space:nowrap}
.store-sort__link.is-active{background:#fff!important;color:#056fd8!important;box-shadow:0 2px 10px #24486c16}
.store-empty-state{width:100%;min-height:255px;display:flex;flex-direction:column;align-items:center;justify-content:center;border:1px dashed #b9d2ee!important;border-radius:17px!important;background:linear-gradient(180deg,#fff,#f8fbff)!important;box-shadow:none!important;color:#61728a!important;gap:8px}
.store-empty-state .bi{font-size:38px;color:#8cb5e5}.store-empty-state strong{color:#243853;font-size:18px}.store-empty-state .empty-note{max-width:420px;font-size:14px}
.store-product{border-radius:15px!important;border:1px solid #e0e8f2!important;box-shadow:0 3px 14px #183d670b!important;overflow:hidden}
.store-product__link:hover .store-product{border-color:#a7cdf2!important;box-shadow:0 12px 25px #0e477c24!important;transform:translateY(-4px)}
.store-product__price{color:#f36a1d!important;font-weight:750!important}
.store-main .card{border:1px solid #e0e8f2!important;border-radius:18px!important;box-shadow:0 8px 25px #183d6710!important;overflow:hidden}
.store-main .card-body{padding:25px!important}.store-main .card h1,.store-main .card h2{color:#183251;letter-spacing:-.4px}
.store-main .nav-pills .nav-link{border-radius:10px;padding:12px 16px;transition:background .2s}.store-main .nav-pills .nav-link.active{background:#e9f4ff!important;color:#086fd8!important;font-weight:650}
.form-control,.form-select{border-color:#dce6f3;border-radius:10px;min-height:43px}.form-control:focus,.form-select:focus{border-color:#0878de;box-shadow:0 0 0 3px #0878de24}
.btn-primary,.shop-button{--bs-btn-bg:#ff7825;--bs-btn-border-color:#ff7825;--bs-btn-hover-bg:#e86319;--bs-btn-hover-border-color:#e86319;--bs-btn-active-bg:#d9570e;--bs-btn-active-border-color:#d9570e;font-weight:650;border-radius:10px}
.shop-footer{background:#fff!important;border-top:3px solid #eff3f9!important}.shop-footer h5,.shop-footer h6{color:#183251!important;font-weight:750}.shop-footer a:hover{color:#ff7220!important}
.retail-hero{position:relative;display:flex;justify-content:space-between;align-items:center;gap:22px;overflow:hidden;padding:28px 35px;margin-bottom:23px;border-radius:20px;background:linear-gradient(115deg,#06509f 0%,#087de9 65%,#1ba6ed 100%);color:#fff;box-shadow:0 12px 29px #0765b52a}
.retail-hero:after{content:'';position:absolute;width:220px;height:220px;border:32px solid #ffffff15;border-radius:50%;right:-70px;top:-85px}
.retail-hero .eyebrow{font-size:12px;text-transform:uppercase;letter-spacing:1.8px;font-weight:750;color:#ffcb99}
.retail-hero h2{margin:7px 0;font-size:clamp(23px,2.5vw,34px);font-weight:800;letter-spacing:-.8px;line-height:1.23}
.retail-hero p{margin:0;color:#e6f3ff;font-size:14px}.retail-hero__icon{font-size:80px;color:#ffb36b;opacity:.95;line-height:1;z-index:1}
@media(max-width:991px){.shop-header__nav{gap:5px!important}.shop-header__nav-link{padding:8px 10px!important}.retail-hero{padding:23px}.retail-hero__icon{font-size:55px}}
@media(max-width:575px){.shop-header__action span{display:none}.shop-header__action{padding:10px 12px!important}.store-sort{max-width:100%;overflow-x:auto}.retail-hero{border-radius:15px}.retail-hero__icon{display:none}.store-toolbar{padding:16px!important}}
@media(prefers-reduced-motion:reduce){*,*:before,*:after{transition:none!important;animation:none!important}}

/* SHOPEE-INSPIRED RETAIL REFINEMENT: cleaner type, squarer corners */
:root{--shop-primary:#0878d9;--shop-primary-hover:#0562bf;--shop-price:#f26419;--shop-shadow:0 3px 16px rgba(25,55,95,.055)}
body,.form-control,.form-select,button,input,select,textarea{font-family:'Roboto','Segoe UI',Arial,sans-serif!important}
body{font-size:15px;letter-spacing:0}
h1,h2,h3,h4,h5,h6{font-family:'Roboto','Segoe UI',Arial,sans-serif!important;letter-spacing:-.25px!important}
.shop-header__search,.shop-header__action{border-radius:7px!important}
.shop-header__logo .bi{border-radius:7px!important}
.shop-header__nav-link{border-radius:4px!important}
.shop-header .dropdown-menu,.shop-header .dropdown-item{border-radius:5px!important}
.store-main .card,.store-sidebar,.store-toolbar{border-radius:8px!important;box-shadow:0 3px 16px rgba(25,55,95,.055)!important}
.store-main .card{overflow:visible}
.store-main .card-body{padding:26px 28px!important}
.store-main .card h1,.store-main .card h2{font-weight:700!important}
.store-main .nav-pills .nav-link{border-radius:5px!important;padding:11px 15px!important}
.store-main .nav-pills .nav-link.active{background:#eef5ff!important;box-shadow:inset 3px 0 #0878d9}
.store-main .form-control,.store-main .form-select{border-radius:5px!important;min-height:44px;background-color:#fff}
.store-main input[type=file].form-control{padding:7px}
.store-main input[type=file].form-control::file-selector-button{background:#eef5ff;border:0;border-right:1px solid #dae5f2;padding:8px 13px;margin:-7px 14px -7px -7px;color:#164c87}
.store-main .btn-primary,.store-main .shop-button{border-radius:5px!important;background:#fa7425!important;border-color:#fa7425!important;padding:10px 19px;box-shadow:none!important}
.store-main .btn-primary:hover,.store-main .shop-button:hover{background:#e95e14!important;border-color:#e95e14!important}
.store-sort,.store-sort__link,.filter-category-link{border-radius:5px!important}
.store-empty-state{border-radius:8px!important;min-height:240px}
.store-product{border-radius:7px!important}
.retail-hero{border-radius:8px!important;background:linear-gradient(108deg,#ef551c 0%,#f8751f 58%,#ff9d36 100%)!important;box-shadow:0 7px 22px rgba(225,98,30,.16)!important;padding:30px 37px!important}
.retail-hero:after{border-radius:50%;border-color:#ffffff1a!important}
.retail-hero .eyebrow{color:#fff4d8!important;letter-spacing:1.6px}
.retail-hero h2{font-weight:800!important;letter-spacing:-.6px!important}
.retail-hero p{color:#fff9f1!important}
.retail-hero__icon{color:#ffe2b8!important}
.shop-footer{border-top:1px solid #e7eaf0!important}
@media(max-width:575px){.retail-hero{border-radius:6px!important;padding:22px!important}.store-main .card-body{padding:20px!important}}
</style>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
