<?php

use Illuminate\Database\Seeder;
use App\Models\ReportMonthlyPushNotification;
use App\Models\ReportDailyPushNotification;
use Carbon\Carbon;
use App\Helpers\GlobalHelper;
class MonthlyPushNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReportMonthlyPushNotification::truncate();

        $week_date = GlobalHelper::displayDates('2019-01-01', date('Y-m-d'), '+1 month', 'Y-m-d');
        $startMonth = [];
        $endMonth = [];
        $collect = [];

        for ($i = 0; $i < count($week_date); $i++) {
            $startMonth[$i] = Carbon::createFromFormat('Y-m-d', $week_date[$i])->startOfMonth();
            $endMonth[$i] = Carbon::createFromFormat('Y-m-d', $week_date[$i])->endOfMonth();
            $collect[$i] = ReportDailyPushNotification::where('company_id','=', 61373)
            ->whereBetween('date', [$startMonth[$i], $endMonth[$i]])->get();
            $startMonth[$i] = date('Y-m-d', strtotime($startMonth[$i]));
            $endMonth[$i] = date('Y-m-d', strtotime($endMonth[$i]));
        }

        for ($i = 0; $i < count($collect); $i++) {
            $count = count($collect[$i]);

            if ($count == 0) {
                $total = 0;
            } else {
                $total = array_sum($collect[$i]->pluck('total_push_notification')->toArray());
            }
            
            ReportMonthlyPushNotification::create(array(
                    'company_id' => 61373,
                    'date' => $endMonth[$i],
                    'total_push_notification' => $total,
            ));
        }
    }
}
