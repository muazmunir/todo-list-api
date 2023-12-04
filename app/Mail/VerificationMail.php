<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $verification_code;

    public function __construct(User $user, string $verification_code)
    {
        $this->user = $user;
        $this->verification_code = $verification_code;
    }

    public function build(): self
    {
        return $this->view('emails.verification')
            ->subject('Verify your account on To-Do List App')
            ->with([
                'user' => $this->user,
                'verification_code' => $this->verification_code,
            ]);
    }
}
