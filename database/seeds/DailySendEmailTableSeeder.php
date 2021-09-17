<?php

use Illuminate\Database\Seeder;
use App\Helpers\GlobalHelper;
use App\Models\ReportDailySendEmail;

use Carbon\Carbon;

class DailySendEmailTableSeeder extends Seeder
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
            ReportDailySendEmail::create(array(
                'company_id' => 61373,
                'date' => $date[$i],
                'total_send_email' => rand(10,50)
            ));
        }
    }
}
