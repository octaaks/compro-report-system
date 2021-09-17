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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        //daily schedule
            $schedule->call('App\Http\Controllers\Api\TransactionsController@daily_transaction_create')->daily()->onSuccess(function () {
                // The task succeeded...
            })
            ->onFailure(function () {
                // The task failed...
            });
            $schedule->call('App\Http\Controllers\Api\UserRegisterController@daily_user_register_create')->dailyAt('16:40')->onSuccess(function () {
                // The task succeeded...
            })
            ->onFailure(function () {
                // The task failed...
            });
            $schedule->call('App\Http\Controllers\Api\EmailSendController@daily_send_email_create')->daily()->onSuccess(function () {
                // The task succeeded...
            })
            ->onFailure(function () {
                // The task failed...
            });
            $schedule->call('App\Http\Controllers\Api\PushNotificationController@daily_push_notification_create')->daily()->onSuccess(function () {
                // The task succeeded...
            })
            ->onFailure(function () {
                // The task failed...
            });

        //weekly schedule
            $schedule->call('App\Http\Controllers\Api\TransactionsController@weekly_transaction_create')->weekly()->onSuccess(function () {
                // The task succeeded...
            })
            ->onFailure(function () {
                // The task failed...
            });
        //monthly schedule
            $schedule->call('App\Http\Controllers\Api\TransactionsController@monthly_transaction_create')->monthly()->onSuccess(function () {
                // The task succeeded...
            })
            ->onFailure(function () {
                // The task failed...
            });
            $schedule->call('App\Http\Controllers\Api\UserRegisterController@monthly_user_register_create')->monthly()->onSuccess(function () {
                // The task succeeded...
            })
            ->onFailure(function () {
                // The task failed...
            });
            $schedule->call('App\Http\Controllers\Api\EmailSendController@monthly_send_email_create')->monthly()->onSuccess(function () {
                // The task succeeded...
            })
            ->onFailure(function () {
                // The task failed...
            });
            $schedule->call('App\Http\Controllers\Api\PushNotificationController@monthly_push_notification_create')->monthly()->onSuccess(function () {
                // The task succeeded...
            })
            ->onFailure(function () {
                // The task failed...
            });
        //yearly schedule
            $schedule->call('App\Http\Controllers\Api\TransactionsController@yearly_transaction_create')->yearly()->onSuccess(function () {
                // The task succeeded...
            })
            ->onFailure(function () {
                // The task failed...
            });
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
