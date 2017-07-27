<?php
namespace App\Models\Services;

use Illuminate\Support\Facades\Mail;

/**
 * Mail sending service for usage in the controllers
 *
 * @author AnPCD <anpcd@evolableasia.vn>
 * @package app\Models\Services
 */

class SendMail
{
    /**
     * Sends an email
     *
     * @param string $template Template name
     * @param array $data Email data. Must contain
     * @example
     * [
     *  'mail_to' => <recipient email address>,
     *  'recipient_name' => <recipient_name>,
     *  'subject' => <email_subject>
     * ]
     * @return bool
     */
    
    public static function send($template, array $data)
    {
        Mail::send($template, ['data' => $data], function ($msg) use ($data) {
            $msg->to($data['mail_to'], $data['recipient_name']);
            $msg->subject($data['subject']);
        });
        
        // Check for email failures
        if (count(Mail::failures()) > 0) {
            return false;
        }

        return true;
    }
}
