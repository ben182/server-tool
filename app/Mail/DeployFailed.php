<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeployFailed extends Mailable
{
    use Queueable, SerializesModels;

    public $repository;
    public $output;
    public $exit;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($repository, $output, $exit)
    {
        $this->repository = $repository;
        $this->output = $output;
        $this->exit = $exit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('moin@benjaminbortels.de')
                    ->view('emails.deployfailed');
    }
}
