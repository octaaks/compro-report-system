<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StorageUsage;
use App\Helpers\GlobalHelper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class StorageUsageController extends Controller
{
    function getStorageUsage (Request $request, $company_id) {
        $collection = StorageUsage::where('company_id','=',$company_id);
        
        $used = $collection->pluck('used')->toArray();
        $available = $collection->pluck('available')->toArray();

        if (empty($used) || empty($available)) {
            $used = [0];
            $available = [GlobalHelper::max_storage];
        }

        $array = ([
            'used'=> $used,
            'available'=> $available,
            'size'=> GlobalHelper::storage_size,
        ]);

        return response()->json(['result'=> 'success', 'data'=> $array]);
    }

    public function storageUsage_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id'=>'required|integer',
            'used'=>'required|integer',
            'available'=>'required',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n", $validator->errors()->all());
            return $errorString;
        } else {
            DB::beginTransaction();
    
            try {
                $storageUsage = new StorageUsage();
                $storageUsage->company_id = $request->company_id;
                $storageUsage->used = $request->used;
                $storageUsage->available = $request->available;
                $storageUsage->save();
    
                DB::commit();

                return response()->json([
                    "result" => 'success',
                    "message" => "successfully inserted data",
                    'data' => $storageUsage
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return response()->json(['result' => 'failed', 'error' => $e]);
            }
        }
    }

    public function storageUsage_update(Request $request, $company_id)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|integer',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n", $validator->errors()->all());
            return $errorString;
        } else {
            DB::beginTransaction();
            $id = $request->id;

            try {
                $storageUsage = StorageUsage::find($id);

                if (isset($storageUsage)) {
                    
                    $storageUsage->company_id = $company_id;
                    $storageUsage->used = $request->used;
                    $storageUsage->available = $request->available;
                    $storageUsage->save();
        
                    DB::commit();

                    return response()->json([
                        "result" => 'success',
                        "message" => "data updated success",
                        'data' => $storageUsage
                    ]);
                } else {
                    return response()->json([
                        "result" => 'failed',
                        "message" => "data with ID=".$id." not found",
                        'data' => $storageUsage
                    ]);
                }
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return response()->json(['result' => 'failed', 'error' => $e]);
            }
        }
    }

    public function storageUsage_delete($id)
    {
        $storageUsage = StorageUsage::find($id);
        
        if (isset($storageUsage)) {
            $storageUsage->delete();
                
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
