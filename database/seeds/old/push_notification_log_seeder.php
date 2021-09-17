<?php

use Illuminate\Database\Seeder;
use App\Models\ReportDailyPushNotification;
use App\Models\PushNotificationLog;
use Carbon\Carbon;

class push_notification_log_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PushNotificationLog::truncate();
        $collection = ReportDailyPushNotification::where('company_id','=', 61373)->orderBy('date', 'asc')->get();

        for ($i = 0; $i < count($collection); $i++) {
            for ($j = 0; $j < $collection[$i]->total_push_notification; $j++) {
                PushNotificationLog::create(array(
                    'company_id' => $collection[$i]->company_id,
                    'no_push_notification_sent' => 1,
                    'target' => 5,
                    'status' => 'pending',
                    'created_at' => Carbon::create($collection[$i]->date)->add('1 day')->subminutes(rand(1, 720)),
                    'updated_at' => Carbon::create($collection[$i]->date)->add('1 day')->subminutes(rand(1, 720)),
                ));
            }
        }
    }
}
