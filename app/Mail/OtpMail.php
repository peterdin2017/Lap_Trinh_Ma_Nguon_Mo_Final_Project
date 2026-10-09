<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otpCode,
        public string $purpose
    ) {}

    public function build(): self
    {
        $subject = $this->purpose === 'reset_password'
            ? 'Mã OTP đặt lại mật khẩu'
            : 'Mã OTP xác nhận đăng ký';

        return $this->subject($subject)
            ->view('email.otp')
            ->with([
                'otpCode' => $this->otpCode,
                'purpose' => $this->purpose,
            ]);
    }
}
