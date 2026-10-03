<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otpCode,                  // giữ đúng tên biến cũ của bạn
        public string $purpose = 'register',     // mặc định: đăng ký
    ) {}

    // Tiêu đề email thay đổi theo mục đích
    public function envelope(): Envelope
    {
        $subject = match ($this->purpose) {
            'reset_password' => 'Mã đặt lại mật khẩu',
            default          => 'Mã xác nhận đăng ký tài khoản',
        };

        return new Envelope(subject: '[Website Bán hàng Online] ' . $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'store.emails.otp');
    }
}
