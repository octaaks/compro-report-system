<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * show products with 0 stock and inserted name
     *
     * @param array $request ["search_name" => string]
     * @param int $company_id
     * @param $response
     * "result": "success",
     * 'data' => $collection_search
     */
    public function product(Request $request, $company_id)
    {
        $name = $request->input('search_name');
        
        if ($name == null || $name == '') {
            $collection = Product::where('company_id', $company_id)
            ->where('stock', '=', 0)
            ->select('id', 'company_id', 'post_id', 'product_name')
            ->get();
            return response()->json(['result' => 'success', 'data' => $collection]);
        } else {
            $collection_search = Product::where('company_id', $company_id)
            ->where('stock', '=', 0)
            ->select('id', 'company_id', 'post_id', 'product_name')
            ->where('product_name', 'LIKE', '%' . $name . '%')
            ->get();

            return response()->json(['result' => 'success', 'data' => $collection_search]);
        }
    }

    /**
     * create a new product
     *
     * @param array $request ["id" => int, "company_id" => int, "post_id" => int, "product_name" => string,"stock" => int]
     * @param $response
     * "result": "success",
     * "message" => "successfully inserted data",
     * 'data' => $product
     */
    public function product_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|integer',
            'company_id'=>'required|integer',
            'post_id'=>'required|integer',
            'product_name'=>'required',
            'stock'=>'required',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n", $validator->errors()->all());
            return $errorString;
        } else {
            DB::beginTransaction();
    
            try {
                $product = new Product();
                $product->id = $request->id;
                $product->company_id = $request->company_id;
                $product->post_id = $request->post_id;
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
     * update a product
     *
     * @param array $request ["post_id" => int, "product_name" => string,"stock" => int]
     * @param int $company_id
     * @param int $id
     * @param $response
     * "result": "success",
     * "message" => "data updated success",
     * 'data' => $product
     */
    public function product_update(Request $request, $company_id, $id)
    {
        $validator = Validator::make($request->all(), [
            'post_id'=>'required|integer',
            'product_name'=>'required',
            'stock'=>'required',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n", $validator->errors()->all());
            return $errorString;
        } else {
            DB::beginTransaction();
            $id = $request->id;
            try {
                $product = Product::where('company_id', '=', $request->company_id)
                ->find($request->id);

                if (isset($product)) {
                    $new_product_log = new ProductLog;

                    $new_product_log->company_id = $request->company_id;
                    $new_product_log->product_id = $request->id;

                    $new_product_log->product_name = $request->product_name;
                    $new_product_log->stock = $request->stock;
                    $new_product_log->save();

                    $product->post_id = $request->post_id;
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
     * delete a product
     *
     * @param int $company_id
     * @param int $id
     * @param $response
     * "result": "success",
     * "message" => "data with ID = ".$id." delete success"
     */
    public function product_delete($company_id, $id)
    {
        $product = Product::where('company_id', $company_id)
        ->find($id);
        
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
