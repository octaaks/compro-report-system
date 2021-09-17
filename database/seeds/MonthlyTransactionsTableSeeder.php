<?php

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use Facade\FlareClient\Stacktrace\File;
use Carbon\Carbon;

class MonthlyTransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        function displayDates($date1, $date2, $format)
        {//return date interval between 2 dates
            $dates = array();
            $current = strtotime($date1);
            $date2 = strtotime($date2);
            $stepVal = '+1 month';

            while ($current <= $date2) {
                $dates[] = date($format, $current);
                $current = strtotime($stepVal, $current);
            }

            return $dates;
        }

        $dates = displayDates('2019-01-1', '2020-12-31', 'Y-m-d');
        // $dates = displayDates('2018-01-1', date('Y-m-d'), 'Y-m-d');
        $firstday = [];
        $lastday = [];
        $collect = [];

        for ($i = 0; $i < count($dates); $i++) {
            $firstday[$i] = Carbon::createFromFormat('Y-m-d', $dates[$i])->startOfMonth();
            $lastday[$i] = Carbon::createFromFormat('Y-m-d', $dates[$i])->endOfMonth();

            $collect[$i] = Transaction::where('company_id', '=', 61373)
            ->whereBetween('transaction_created_at', [$firstday[$i], $lastday[$i]])->get();

            $collect2[$i] = Transaction::where('company_id', '=', 61373)->where('status', '=', 7)
            ->whereBetween('transaction_created_at', [$firstday[$i], $lastday[$i]])->get();

            $collect3[$i] = Transaction::where('company_id', '=', 61373)->where('status', '=', 5)
            ->whereBetween('transaction_created_at', [$firstday[$i], $lastday[$i]])->get();

            $firstday[$i] = date('Y-m-d', strtotime($firstday[$i]));
            $lastday[$i] = date('Y-m-d', strtotime($lastday[$i]));
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

            ReportMonthlyTransaction::create(array(
                'company_id' => 61373,
                'order_sales' => $os,
                'order_count' => $count,
                'sales_per_order' => $ops,
                'status' => 7,
                'start_date' => $firstday[$k],
                'end_date' => $lastday[$k],
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

            ReportMonthlyTransaction::create(array(
                'company_id' => 61373,
                'order_sales' => $os,
                'order_count' => $count,
                'sales_per_order' => $ops,
                'status' => 5,
                'start_date' => $firstday[$l],
                'end_date' => $lastday[$l],
            ));
        }
    }
}
