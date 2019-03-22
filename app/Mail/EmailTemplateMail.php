<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailTemplateMail extends Mailable
{

    use Queueable,
        SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
    /**
     * Build the message.
     *
     * @return $this
     */

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->to($this->data['to']);

        $ccArr = $this->data['cc'];
        $bccArr = $this->data['bcc'];

        if (!empty($ccArr)) {
            $email->cc($ccArr);
        }

        if (!empty($bccArr)) {
            $email->bcc($bccArr);
        }

        $email->subject($this->data['subject']);
        $email->view('emails.email-layout');

        $attachmentArr = @$this->data['email_template_attachment_path'];
        if (!empty($attachmentArr)) {
            foreach ($attachmentArr as $file) {
                $email->attach("$file");
            }
        }

        $tmpFileArr = @$this->data['attachment'];

        if (!empty($tmpFileArr)) {
            foreach ($tmpFileArr as $tmpFile) {
                $email->attach($tmpFile->getRealPath(), ['as' => $tmpFile->getClientOriginalName()]);
            }
        }
        return $email;
    }
}