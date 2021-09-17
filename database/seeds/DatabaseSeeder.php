<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(user_register_log_seeder::class);
        // $this->call(email_send_log_seeder::class);
        // $this->call(push_notification_log_seeder::class);
        // $this->call(MonthlyUserRegisterSeeder::class);
        // $this->call(MonthlySendEmailSeeder::class);
        // $this->call(MonthlyPushNotificationSeeder::class);
        $this->call(TransactionsTableSeeder::class);

        // $this->call(product_table_seeder::class);
        // $this->call(DailyTransactionsTableSeeder::class);
        // $this->call(WeeklyTransactionsTableSeeder::class);
        // $this->call(MonthlyTransactionsTableSeeder::class);
        // $this->call(YearlyTransactionsTableSeeder::class);

        // $this->call(DailyUserRegisterTableSeeder::class);
        // $this->call(DailySendEmailTableSeeder::class);
        // $this->call(DailyPushNotificationTableSeeder::class);
    }
}
