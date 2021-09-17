<?php

use Illuminate\Database\Seeder;
use App\Models\ReportDailySendEmail;
use App\Models\SendEmailLog;
use Carbon\Carbon;

class email_send_log_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SendEmailLog::truncate();
        $collection = ReportDailySendEmail::where('company_id','=', 61373)->orderBy('date', 'asc')->get();

        for ($i = 0; $i < count($collection); $i++) {
            for ($j = 0; $j < $collection[$i]->total_send_email; $j++) {
                SendEmailLog::create(array(
                    'company_id' => $collection[$i]->company_id,
                    'no_email_sent' => 1,
                    'email_used_for' => 'new transaction',
                    'created_at' => Carbon::create($collection[$i]->date)->add('1 day')->subminutes(rand(1, 720)),
                    'updated_at' => Carbon::create($collection[$i]->date)->add('1 day')->subminutes(rand(1, 720)),
                ));
            }
        }
    }
}
