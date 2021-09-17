<?php

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use Facade\FlareClient\Stacktrace\File;

class TransactionsTableSeeder extends Seeder
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
        $json = json_decode(file_get_contents($appUrl.'/jsonFile/storeinfo_fake_data.json'));
        
        foreach ($json as $j) {
            Transaction::create(array(
                'company_id'                => $j->company_id,
                'transaction_id'            => $j->transaction_id,
                'transaction_created_at'    => $j->transaction_created_at,
                'order_id'                  => $j->order_id,
                'total_price'               => $j->total_price,
                'status'                    => $j->status,
                'name'                      => $j->name,
                'email'                     => $j->email,
                'phone'                     => $j->phone,
                'address'                   => $j->address
            ));
        }
    }
}
