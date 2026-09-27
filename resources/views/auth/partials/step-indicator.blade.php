{{-- File này nhận vào biến $current (giá trị 1, 2 hoặc 3) để biết đang tô sáng bước nào --}}
<div class="d-flex justify-content-center align-items-center">

    <div class="text-center">
        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto"
             style="width: 36px; height: 36px; background-color: {{ $current >= 1 ? '#198754' : '#e9ecef' }}; color: white;">
            @if($current > 1)
                ✓
            @else
                1
            @endif
        </div>
        <small class="d-block mt-1" style="color: {{ $current == 1 ? '#198754' : '#adb5bd' }};">Xác minh email</small>
    </div>

    <div style="width: 60px; height: 1px; background-color: #dee2e6; margin: 0 8px 20px;"></div>

    <div class="text-center">
        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto"
             style="width: 36px; height: 36px; background-color: {{ $current >= 2 ? '#198754' : '#e9ecef' }}; color: white;">
            @if($current > 2)
                ✓
            @else
                2
            @endif
        </div>
        <small class="d-block mt-1" style="color: {{ $current == 2 ? '#198754' : '#adb5bd' }};">Tạo mật khẩu</small>
    </div>

    <div style="width: 60px; height: 1px; background-color: #dee2e6; margin: 0 8px 20px;"></div>

    <div class="text-center">
        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto"
             style="width: 36px; height: 36px; background-color: {{ $current >= 3 ? '#198754' : '#e9ecef' }}; color: white;">
            3
        </div>
        <small class="d-block mt-1" style="color: #adb5bd;">Hoàn thành</small>
    </div>

</div>
