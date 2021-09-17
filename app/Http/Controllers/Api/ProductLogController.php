<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductLogController extends Controller
{
    /**
     * show product logs with inserted name
     *
     * @param array $request ["search_name" => string]
     * @param int $company_id
     * @param $response
     * "result": "success",
     * 'data' => $data
     */
    public function product_log(Request $request, $company_id)
    {
        $collection = ProductLog::where('company_id', $company_id)->get();

        //Function search by name, date range
        $name = strtolower($request->input('search_name'));
        similar_text('Nike shoes', $name, $perc);

        if ($name == null || $name == '') {
            return response()->json(['result' => 'success', 'data' => $collection]);
        } else {
            $data = [];
            foreach ($collection as $collect) {
                $c_name = strtolower($collect->product_name);
                
                similar_text($c_name, $name, $perc);//presentace of similarity between 2 data
                // dd($perc);
                if ((number_format($perc, 0) > 50)) {
                    $data[] = $collect;
                }
            }

            return response()->json(['result' => 'success', 'data' => $data]);
        }
    }

    /**
     * create a new product log
     *
     * @param array $request ["company_id" => int, "product_id" => int, "product_name" => string,"stock" => int]
     * @param $response
     * "result": "success",
     * "message" => "successfully inserted data",
     * 'data' => $product
     */
    public function product_log_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id'=>'required|integer',
            'product_id'=>'required|integer',
            'product_name'=>'required',
            'stock'=>'required|integer',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n", $validator->errors()->all());
            return $errorString;
        } else {
            DB::beginTransaction();
 
            try {
                $product = new ProductLog();
                $product->company_id = $request->company_id;
                $product->product_id = $request->product_id;
                $product->product_name = $request->product_name;
                $product->stock = $request->stock;
                $product->save();
    
                DB::commit();

                return response()->json([
                    "result" => 'success',
                    "message" => "successfully inserted data",
                    'data' => $product
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return response()->json(['result' => 'failed', 'error' => $e]);
            }
        }
    }

    /**
     * update a product log
     *
     * @param array $request ["company_id"=> int,"product_id" => int, "product_name" => string,"stock" => int]
     * @param int $id
     * @param $response
     * "result": "success",
     * "message" => "data updated success",
     * 'data' => $product
     */
    public function product_log_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_id'=>'required|integer',
            'product_id'=>'required|integer',
            'product_name'=>'required',
            'stock'=>'required|integer',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n", $validator->errors()->all());
            return $errorString;
        } else {
            DB::beginTransaction();
 
            try {
                $product = ProductLog::find($id);

                if (isset($product)) {
                    $product->company_id = $request->company_id;
                    $product->product_id = $request->product_id;
                    $product->product_name = $request->product_name;
                    $product->stock = $request->stock;
                    $product->save();
        
                    DB::commit();

                    return response()->json([
                        "result" => 'success',
                        "message" => "data updated success",
                        'data' => $product
                    ]);
                } else {
                    return response()->json([
                        "result" => 'failed',
                        "message" => "data with ID=".$id." not found",
                        'data' => $product
                    ]);
                }
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return response()->json(['result' => 'failed', 'error' => $e]);
            }
        }
    }

    /**
     * delete a product log
     *
     * @param int $id
     * @param $response
     * "result": "success",
     * "message" => "data with ID = ".$id." delete success"
     */
    public function product_log_delete($id)
    {
        $product = ProductLog::find($id);
        
        if (isset($product)) {
            $product->delete();
                
            return response()->json([
                "result" => 'success',
                "message" => "data with ID = ".$id." delete success"
            ]);
        } else {
            return response()->json([
                "result" => 'failed',
                "message" => "data with ID = ".$id." not found"
            ]);
        }
    }
}
