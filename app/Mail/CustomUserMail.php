<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $subjectLine,
        public string $bodyMessage,
        public User $user
    ) {
        $this->subject($this->subjectLine);
    }

    public function build(): self
    {
        return $this->view('emails.custom-user')
            ->with([
                'user' => $this->user,
                'bodyMessage' => $this->bodyMessage,
            ]);
    }
}
