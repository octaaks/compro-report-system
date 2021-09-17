<?php

use Illuminate\Database\Seeder;
use App\Helpers\GlobalHelper;
use App\Models\ReportDailyUserRegister;

use Carbon\Carbon;

class DailyUserRegisterTableSeeder extends Seeder
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
            ReportDailyUserRegister::create(array(
                'company_id' => 61373,
                'date' => $date[$i],
                'total_user_register' => rand(10,50)
            ));
        }
    }
}
