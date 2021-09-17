<?php

use Illuminate\Database\Seeder;
use App\Models\ReportWeeklyTransaction;
use App\Models\Transaction;
use Carbon\Carbon;

class report_weekly_transaction_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReportWeeklyTransaction::truncate();
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);

        function displayDates($date1, $date2, $format)
        {//return date interval between 2 dates
            $dates = array();
            $current = strtotime($date1);
            $date2 = strtotime($date2);
            $stepVal = '+7 day';

            while ($current <= $date2) {
                $dates[] = date($format, $current);
                $current = strtotime($stepVal, $current);
            }

            return $dates;
        }

        $week_date = displayDates('2020-08-30', date('Y-m-d'), 'Y-m-d');
        $sunday = [];
        $saturday = [];
        $collect = [];

        for ($i = 0; $i < count($week_date); $i++) {
            $sunday[$i] = Carbon::createFromFormat('Y-m-d', $week_date[$i])->startOfWeek();
            $saturday[$i] = Carbon::createFromFormat('Y-m-d', $week_date[$i])->endOfWeek();
            $collect[$i] = Transaction::where('company_id', '=', 61373)
            ->whereBetween('transaction_created_at', [$sunday[$i], $saturday[$i]])->get();
            $sunday[$i] = date('Y-m-d', strtotime($sunday[$i]));
            $saturday[$i] = date('Y-m-d', strtotime($saturday[$i]));
        }

        for ($i = 0; $i < count($collect); $i++) {
            $count = count($collect[$i]);
            if ($count == 0) {
                $os = 0;
                $ops = 0;
            } else {
                $os = array_sum($collect[$i]->pluck('total_price')->toArray());
                $ops = $os / $count;
            }
            ReportWeeklyTransaction::create(array(
                        'company_id' => 61373,
                        'order_sales' => $os,
                        'order_count' => $count,
                        'sales_per_order' => $ops,
                        'status' => 7,
                        'start_date' => $sunday[$i],
                        'end_date' => $saturday[$i],
                ));
        }
    }
}
