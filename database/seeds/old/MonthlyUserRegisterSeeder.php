<?php

use Illuminate\Database\Seeder;
use App\Models\ReportMonthlyUserRegister;
use App\Models\ReportDailyUserRegister;
use Carbon\Carbon;
use App\Helpers\GlobalHelper;
class MonthlyUserRegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReportMonthlyUserRegister::truncate();

        $week_date = GlobalHelper::displayDates('2019-01-01', date('Y-m-d'), '+1 month', 'Y-m-d');
        $startMonth = [];
        $endMonth = [];
        $collect = [];

        for ($i = 0; $i < count($week_date); $i++) {
            $startMonth[$i] = Carbon::createFromFormat('Y-m-d', $week_date[$i])->startOfMonth();
            $endMonth[$i] = Carbon::createFromFormat('Y-m-d', $week_date[$i])->endOfMonth();
            $collect[$i] = ReportDailyUserRegister::where('company_id','=', 61373)
            ->whereBetween('date', [$startMonth[$i], $endMonth[$i]])->get();
            $startMonth[$i] = date('Y-m-d', strtotime($startMonth[$i]));
            $endMonth[$i] = date('Y-m-d', strtotime($endMonth[$i]));
        }

        for ($i = 0; $i < count($collect); $i++) {
            $count = count($collect[$i]);

            if ($count == 0) {
                $total = 0;
            } else {
                $total = array_sum($collect[$i]->pluck('total_user_register')->toArray());
            }

            ReportMonthlyUserRegister::create(array(
                    'company_id' => 61373,
                    'date' => $endMonth[$i],
                    'total_user_register' => $total,
            ));
        }
    }
}
