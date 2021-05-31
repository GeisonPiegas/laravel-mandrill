<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SendMailCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //Limpeza cache laravel diária
        $schedule->command("cache:clear")->daily();

        //Reinicio dos executores de filas laravel a cada hora (Para evitar acumulo de memoria ram)
        $schedule->command("queue:restart")->hourly();

        //Reinicio do servidor pusher diário (Para evitar acumulo de memoria ram)
        $schedule->command("pusher:restart")->daily();

        //A cada minuto verifica se o executor está em execução no sistema e inicia o mesmo caso não esteja
        $schedule->command("queue:work", [
            "--queue=".implode(",", [
                "mail-send",
            ])
        ])->everyMinute()
          ->name("QUEUE_WORK")
          ->withoutOverlapping()
          ->runInBackground();

        //A cada minuto checa se existem e-mails e enviar e envia
        $schedule->command("mail:send")->cron("* * * * *")
                                       ->name("MAIL_SEND")
                                       ->withoutOverlapping()
                                       ->runInBackground();


    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
