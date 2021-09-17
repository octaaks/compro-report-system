<?php

use Illuminate\Database\Seeder;
use App\Models\Product;

class product_table_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appUrl = env('APP_URL');
        Product::truncate();
        $json = json_decode(file_get_contents($appUrl.'/jsonFile/product/product_engine.json'));
        foreach ($json->data as $j) {
            Product::create(array(
                    'id'            => $j->id,
                    'company_id'    => $j->company_id,
                    'post_id'       => $j->term_id,
                    'product_name'  => $j->title,
                    'stock'  => $j->stock,
                    // 'price'         => $j->price
                ));
        }
    }
}
