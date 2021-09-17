<?php

use Illuminate\Database\Seeder;
use App\Models\ReportDailyUserRegister;
use App\Models\UserRegisterLog;
use Carbon\Carbon;

class user_register_log_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserRegisterLog::truncate();
        $collection = ReportDailyUserRegister::where('company_id','=', 61373)->orderBy('date', 'asc')->get();

        for ($i = 0; $i < count($collection); $i++) {
            for ($j = 0; $j < $collection[$i]->total_user_register; $j++) {
                UserRegisterLog::create(array(
                    'company_id' => $collection[$i]->company_id,
                    'company_user_id' => rand(1,999),
                    'created_at' => Carbon::create($collection[$i]->date)->add('1 day')->subminutes(rand(1, 720)),
                    'updated_at' => Carbon::create($collection[$i]->date)->add('1 day')->subminutes(rand(1, 720)),
                ));
            }
        }
    }
}
