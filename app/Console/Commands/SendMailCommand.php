<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models as Models;
use App\Jobs as Jobs;

class SendMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando responsavel por enviar e-mails.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Models\Mail $model_mail)
    {
        $mails = $model_mail->where(function ($query) {
            $query->whereDate("send_date", "<", date("Y-m-d H:i:s"))->orWhereNull("send_date");
        })->whereNull("sent_date")->get();
      
        $bar = $this->output->createProgressBar(count($mails));

        $bar->start();

        foreach ($mails as $mail) {
            Jobs\SendMailJob::dispatch($mail);
            $bar->advance();
        }

        $bar->finish();
        echo PHP_EOL;
    }
}
