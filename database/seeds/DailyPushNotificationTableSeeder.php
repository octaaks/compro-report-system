<?php

use Illuminate\Database\Seeder;
use App\Helpers\GlobalHelper;
use App\Models\ReportDailyPushNotification;

use Carbon\Carbon;

class DailyPushNotificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = GlobalHelper::displayDates('2020-12-16', '2020-12-31', '+1 day', 'Y-m-d');
        
        for ($i = 0; $i < count($date); $i++) {
            ReportDailyPushNotification::create(array(
                'company_id' => 61373,
                'date' => $date[$i],
                'total_push_notification' => rand(10,50)
            ));
        }
    }
}
