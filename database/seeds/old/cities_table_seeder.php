<?php

use Illuminate\Database\Seeder;
use App\Models\City;

class cities_table_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::truncate();
        $json = json_decode(file_get_contents(env('APP_URL').'/jsonFile/storeinfo/data_kota.json'));
        
        $total = 0;
        foreach ($json as $i) {
            $total += $i->nominal;
        }
        
        foreach ($json as $j) {
            City::create(array(
                'name' => $j->kota,
                'transaction' => $j->jumlah,
                'total' => 'Rp. '.number_format(($j->nominal), 0, ",", "."),
                'percentage' => number_format(100*($j->nominal/$total), 2, ",", ".").'%'
            ));
        }
    }
}
