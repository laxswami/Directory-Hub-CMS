<?php

namespace App\Mail;

use App\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $toName;

    public $greeting;
    public $level;
    public $introLines;
    public $actionText;
    public $actionUrl;
    public $outroLines;
    public $salutation;


    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param string $from_address
     * @param string $from_name
     * @param array $intro_lines
     * @param string|null $greenting
     * @param string $level
     * @param string|null $action_text
     * @param string|null $action_url
     * @param array $outro_lines
     * @param string|null $salutation
     */
    public function __construct(string $subject,
                                string $to_name=null,
                                string $greenting=null,
                                array $intro_lines=[],
                                string $action_text=null,
                                $level="success",
                                string $action_url=null,
                                array $outro_lines=[],
                                string $salutation=null)
    {
        //
        $this->subject = $subject;
        $this->toName = $to_name;
        $this->introLines = $intro_lines;
        $this->greeting = $greenting;
        $this->level = $level;
        $this->actionText = $action_text;
        $this->actionUrl = $action_url;
        $this->outroLines = $outro_lines;
        $this->salutation = $salutation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->markdown('emails.notification');
    }
}
