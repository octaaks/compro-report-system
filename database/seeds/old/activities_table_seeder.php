<?php

use Illuminate\Database\Seeder;
use App\Models\Activity;
use Illuminate\Notifications\Action;

class activities_table_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appUrl = env('APP_URL');
        Activity::truncate();
        $json = json_decode(file_get_contents($appUrl.'/jsonFile/activities/activities_compro.json'));
        
        foreach ($json->data as $j) {
            Activity::create(array(
                    'id' => $j->id,
                    'company_id' => $j->company_id,
                    'company_user_id' => $j->company_id,
                    'type' => $j->title,
                    'content' => $j->content,
                    'date_time' => $j->created_at
                ));
        }
    }
}
