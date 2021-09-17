<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Activity;

class ActivitiesController extends Controller
{

    public function activity(Request $request, $company_id)
    {
        $type = $request->input('type');
        $collection = Activity::where([['company_id', '=', $company_id],['type', '=', $type]])->orderBy('created_at', 'desc')->take(5)->get();
        
        return response()->json(['result'=> 'success', 'data'=> $collection]);
    }

    public function activity_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id'=>'required|integer',
            'title'=>'required',
            'content'=>'required',
            'url'=>'required',
            'type'=>'required'
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n",$validator->errors()->all());
            return $errorString;
        } else {
            DB::beginTransaction();
 
            try {
                $activity = new Activity();
                $activity->company_id = $request->company_id;
                $activity->title = $request->title;
                $activity->content = $request->content;
                $activity->url = $request->url;
                $activity->type = $request->type;
                $activity->save();
    
                DB::commit();

                return response()->json([
                    "result" => 'success',
                    "message" => "successfully inserted data",
                    'data' => $activity
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return response()->json(['result' => 'failed', 'error' => $e]);
            }
        }
    }

    public function activity_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_id'=>'required|integer',
            'title'=>'required',
            'content'=>'required',
            'url'=>'required',
            'type'=>'required'
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n",$validator->errors()->all());
            return $errorString;
        } else {
            DB::beginTransaction();
 
            try {
                $activity = Activity::find($id);

                if(isset($activity)){
                    $activity->company_id = $request->company_id;
                    $activity->title = $request->title;
                    $activity->content = $request->content;
                    $activity->url = $request->url;
                    $activity->type = $request->type;
                    $activity->save();
        
                    DB::commit();

                    return response()->json([
                        "result" => 'success',
                        "message" => "data updated success",
                        'data' => $activity
                    ]);

                } else {
                    return response()->json([
                        "result" => 'failed',
                        "message" => "data with ID=".$id." not found",
                        'data' => $activity
                    ]);
                }
                

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return response()->json(['result' => 'failed', 'error' => $e]);
            }
        }
    }

    public function activity_delete($id)
    {
        $activity = Activity::find($id);
        
        if (isset($activity)) {
            $activity->delete();
                
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
