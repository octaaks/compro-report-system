<?php

use Illuminate\Database\Seeder;
use App\Models\Activity;
use Facade\FlareClient\Stacktrace\File;

class ActivitiesTableSeeder extends Seeder
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
                    'company_id' => $j->company_id,
                    'title' => $j ->title,
                    'content' => $j->content,
                    'url' => $j->url,
                    'type' => $j->title,
                ));
        }
    }
}
