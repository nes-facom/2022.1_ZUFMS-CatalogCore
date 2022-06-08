<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class EmailSenderService
{
    public function send(string $template, string $receiver, array $contentVariables, string $subject){
        Mail::send($template, $contentVariables, function($m) use ($receiver, $subject){
            $m->from(env('MAIL_USERNAME'));
            $m->to($receiver);
            $m->subject($subject);
        });
    }
}
