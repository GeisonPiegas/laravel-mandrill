<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers as Helpers;
use App\Models as Models;
use App\Events as Events;
use App\Configs as Configs;
use Illuminate\Support\Facades\Log;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 10;
    public $maxExceptions = 3;

    private Models\Mail $mail;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mail)
    {
        $this->mail = $mail;
        $this->onQueue('mail-send');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Helpers\MailChimpClient $mailChimpClient, Configs\MailChimpConfig $mailChimpConfig)
    {
        try{

            $data = Array(
                "message" => Array(
                    "html" => is_html($this->mail->body) ? $this->mail->body : null,
                    "text" => is_html($this->mail->body) ? null : $this->mail->body,
                    "subject" => $this->mail->subject,
                    "from_email" => $mailChimpConfig->FROM_EMAIL(),
                    "to" => Array(
                        Array(
                            "email" => $this->mail->email,
                            "name" => $this->mail->name,
                        )
                    )
                )
            );

            $response = $mailChimpClient->post_messages_send($data);
            $this->mail->sent_date = date('Y-m-d H:i:s');
            $this->mail->sent_id = $response[0]->_id;
            $this->mail->save();

            event(new Events\SentMailEvent($this->mail));

        }catch(\Exception $e){
            Log::channel('error_mail')->error('Falha no envio do e-mail ID = '.$this->mail->id.': '.$e->getMessage());
        }
    }
}
