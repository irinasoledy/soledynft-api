<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\FrontUser;
use App\Base as Model;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Notifications\LogsHandler;

class MailHandler extends Controller
{
    private $from = "info@soledy.com";

    /**
     * Send simple email
     */
    public function sendEmail($data, $email, $subject, $template)
    {
        $from = $this->from;

        try {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Mail::send($template, $data, function($message) use ($email, $subject, $from)
                {
                    $message->to($email, $subject)
                            ->from($from)
                            ->subject($subject);
                });
            }
        } catch (\Exception $e) {
            $problem = "Send Email error.(". $subject .")";
            LogsHandler::save(debug_backtrace(), $problem, \Auth::guard('persons')->user());
        }
    }

    /**
     * Send email with attach file
     */
    public function sendEmailAttach($data, $email, $subject, $template, $path, $filename)
    {
        $from = $this->from;

        try {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Mail::send($template, $data, function($message) use ($email, $subject, $from, $path, $filename)
                {
                    $message->to($email, $subject)
                            ->from($from)
                            ->subject($subject);

                    if(is_file($path . '/' . $filename)){
                        $message->attach($path . '/' . $filename);
                    }
                });
            }
        } catch (\Exception $e) {
            $problem = "Send Email error.(". $subject .")";
            LogsHandler::save(debug_backtrace(), $problem, \Auth::guard('persons')->user());
        }
    }

}
