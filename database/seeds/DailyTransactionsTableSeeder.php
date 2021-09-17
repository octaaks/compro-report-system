<?php

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\ReportDailyTransaction;
use Facade\FlareClient\Stacktrace\File;

class DailyTransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReportDailyTransaction::truncate();

        function getDates($date1, $date2, $format)
        {//return date interval between 2 dates
            $dates = array();
            $current = strtotime($date1);
            $date2 = strtotime($date2);
            $stepVal = '+1 day';

            while ($current <= $date2) {
                $dates[] = date($format, $current);
                $current = strtotime($stepVal, $current);
            }

            return $dates;
        }
        
        // $dateArr = getDates('2020-08-1', date('Y-m-d'), 'Y-m-d');
        $dateArr = getDates('2020-01-01', '2020-12-31', 'Y-m-d');
        $collect = [];

        for ($i = 0; $i < count($dateArr); $i++) {
            $collect[$i] = Transaction::where('company_id', '=', 61373)
            ->whereDate('transaction_created_at', $dateArr[$i])->get();

            $collect2[$i] = Transaction::where('company_id', '=', 61373)->where('status', '=', 7)
            ->whereDate('transaction_created_at', $dateArr[$i])->get();

            $collect3[$i] = Transaction::where('company_id', '=', 61373)->where('status', '=', 5)
            ->whereDate('transaction_created_at', $dateArr[$i])->get();
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

            ReportDailyTransaction::create(array(
                'company_id' => 61373,
                'order_sales' => $os,
                'order_count' => $count,
                'sales_per_order' => $ops,
                'status' => 7,
                'date' => $dateArr[$k]
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

            ReportDailyTransaction::create(array(
                'company_id' => 61373,
                'order_sales' => $os,
                'order_count' => $count,
                'sales_per_order' => $ops,
                'status' => 5,
                'date' => $dateArr[$l]
            ));
        }
    }
}
