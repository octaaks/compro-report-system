<?php

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use Facade\FlareClient\Stacktrace\File;

class transaction_table_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appUrl = env('APP_URL');
        Transaction::truncate();
        $json = json_decode(file_get_contents($appUrl.'/jsonFile/dashboard/transaction_compro.json'));
        // $json = json_decode(file_get_contents($appUrl.'/jsonFile/storeinfo/data_grafik_engine.json'));
        
        foreach ($json->data as $j) {
            Transaction::create(array(
                'company_id' => $j->company_id,
                'company_user_id' => $j->user_id,
                'name' => $j->name,
                'status' => $j->status,
                'date_time' => date('Y-m-d H:i:s', strtotime($j->date_created)),
                'quantity' => rand(1, 15),
                'total_price' => $j->total_price,
                'address' => $j->address
            ));
        }
    }
}
