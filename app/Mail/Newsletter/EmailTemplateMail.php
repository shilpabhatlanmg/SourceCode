<?php

namespace App\Mail\Newsletter;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Model\Newsletter\Newsletter;

class EmailTemplateMail extends Mailable
{
    use Queueable, SerializesModels;
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
    public function build()
    {
        $attachmentArr = @$this->data['email_template_attachment_path'];

        $tmpFileArr = @$this->data['attachment'];

        $email = $this->to($this->data['to']);

        if (!empty($this->data['cc'])) {
            $email->cc($this->data['cc']);
        }
        if (!empty($this->data['bcc'])) {
            $email->bcc($this->data['bcc']);
        }
        $email->subject($this->data['subject']);

        $email->view('emails.admin.newsletter.email_template');

        if (!empty($attachmentArr)) {
            foreach ($attachmentArr as $filePath) {
                $email->attach("$filePath");
            }
        }

        if (!empty($tmpFileArr)) {
            foreach ($tmpFileArr as $tmpFile) {
                $email->attach($tmpFile->getRealPath(), ['as' => $tmpFile->getClientOriginalName()]);
            }
        }

        return $email;
    }
}
