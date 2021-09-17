<?php

use Illuminate\Database\Seeder;
use App\Models\userRegistered;
use App\Models\sentPushNotification;
use App\Models\sentEmail;

class dahsboard_graph_data_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appUrl = env('APP_URL');
        userRegistered::truncate();
        sentPushNotification::truncate();
        sentEmail::truncate();
        $json = json_decode(file_get_contents($appUrl.'/jsonFile/dashboard/user_registered.json'));
        $json2 = json_decode(file_get_contents($appUrl.'/jsonFile/dashboard/sent_push_notification.json'));
        $json3 = json_decode(file_get_contents($appUrl.'/jsonFile/dashboard/sent_email.json'));
        foreach ($json->data as $j) {
            userRegistered::create(array(
                'company_id' => $j->company_id,
                'username' => $j->username,
                'date_time' => $j->created_at
            ));
        }
        foreach ($json2->data as $j) {
            sentPushNotification::create(array(
                'company_id' => $j->created_at,
                'date_time' => $j->created_at
            ));
        }
        foreach ($json3->data as $j) {
            sentEmail::create(array(
                'company_id' => $j->company_id,
                'total' => $j->email_count,
                'date_time' => $j->updated_at
            ));
        }
    }
}
