<?php

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\ReportWeeklyTransaction;
use Facade\FlareClient\Stacktrace\File;
use Carbon\Carbon;

class WeeklyTransactionsTableSeeder extends Seeder
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

        $week_date = displayDates('2020-01-01', '2020-12-31', 'Y-m-d');
        $sunday = [];
        $saturday = [];
        $collect = [];
        $collect2 = [];
        $collect3 = [];

        for ($i = 0; $i < count($week_date); $i++) {
            $sunday[$i] = Carbon::createFromFormat('Y-m-d', $week_date[$i])->startOfWeek();
            $saturday[$i] = Carbon::createFromFormat('Y-m-d', $week_date[$i])->endOfWeek();

            $collect[$i] = Transaction::where('company_id', '=', 61373)
            ->whereBetween('transaction_created_at', [$sunday[$i], $saturday[$i]])->get();

            $collect2[$i] = Transaction::where('company_id', '=', 61373)->where('status', '=', 7)
            ->whereBetween('transaction_created_at', [$sunday[$i], $saturday[$i]])->get();

            $collect3[$i] = Transaction::where('company_id', '=', 61373)->where('status', '=', 5)
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
                'status' => 0,
                'start_date' => $sunday[$i],
                'end_date' => $saturday[$i],
            ));
        }

        for ($k = 0; $k < count($collect2); $k++) {
            $count = count($collect2[$k]);
            
            if ($count == 0) {
                $os = 0;
                $ops = 0;
            } else {
                $os = array_sum($collect2[$k]->pluck('total_price')->toArray());
                $ops = $os / $count;
            }

            ReportWeeklyTransaction::create(array(
                'company_id' => 61373,
                'order_sales' => $os,
                'order_count' => $count,
                'sales_per_order' => $ops,
                'status' => 7,
                'start_date' => $sunday[$k],
                'end_date' => $saturday[$k],
            ));
        }

        for ($l = 0; $l < count($collect3); $l++) {
            $count = count($collect3[$l]);
            
            if ($count == 0) {
                $os = 0;
                $ops = 0;
            } else {
                $os = array_sum($collect3[$l]->pluck('total_price')->toArray());
                $ops = $os / $count;
            }

            ReportWeeklyTransaction::create(array(
                'company_id' => 61373,
                'order_sales' => $os,
                'order_count' => $count,
                'sales_per_order' => $ops,
                'status' => 5,
                'start_date' => $sunday[$l],
                'end_date' => $saturday[$l],
            ));
        }
    }
}
