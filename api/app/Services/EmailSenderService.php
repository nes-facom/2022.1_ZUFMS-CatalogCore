<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class EmailSenderService
{
    public static function sendTemplate(string $template, string $receiver, array $contentVariables, string $subject){
        Mail::send($template, $contentVariables, function($m) use ($receiver, $subject){
            $m->from(env('MAIL_USERNAME'));
            $m->to($receiver);
            $m->subject($subject);
        });
    }

    public static function send(string $body, string $receiver, string $subject){
        Mail::send(array(), array(), function ($message) use ($body, $receiver, $subject) {
            $message->to($receiver)
              ->subject($subject)
              ->from(env('MAIL_USERNAME'))
              ->setBody($body, 'text/html');
          });
    }
}
