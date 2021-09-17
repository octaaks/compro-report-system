<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\ReportDailyPushNotification;
use App\Models\ReportMonthlyPushNotification;
use App\Models\PushNotificationLog;
use Carbon\Carbon;

class PushNotificationController extends Controller
{
    /* ..................
        report daily push notification
        function:
    .......................*/
    public function daily_push_notification_create(Request $request)
    {
        $tran = PushNotificationLog::select('company_id')->groupBy('company_id')->get();
        $company_id = $tran->pluck('company_id')->toArray();

        $date = date('Y-m-d', strtotime("-1 days"));

        $fd = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
        $ld = Carbon::createFromFormat('Y-m-d', $date)->endOfDay();

        DB::beginTransaction();

        try {
            for ($j = 0; $j < count($company_id); $j++) {
                $data[$j] = ReportDailyPushNotification::where('company_id', '=', $company_id[$j])
                ->whereBetween('date', [$fd, $ld]);

                if ($data[$j]->count() !== 0){
                    $data[$j]->delete();
                }

                $collect[$j] = PushNotificationLog::where('company_id', '=', $company_id[$j])
                ->whereBetween('created_at', [$fd, $ld])->count();

                ReportDailyPushNotification::create(array(
                    'company_id' => $company_id[$j],
                    'date' => $date,
                    'total_push_notification' => $collect[$j]
                ));
            }
            DB::commit();

            return response()->json([
                "result" => 'success',
                "message" => "successfully inserted data"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            return response()->json(['result' => 'failed', 'error' => $e]);
        }
    }

    public function daily_push_notification_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_id'=>'required|integer',
            'date'=>'date|date_format:Y-m-d',
            'total_push_notification'=>'required|integer',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n",$validator->errors()->all());
            return $errorString;
        } else {
            DB::beginTransaction();
 
            try {
                $pushNotif = ReportDailyPushNotification::find($id);

                if(isset($pushNotif)){
                    $pushNotif->company_id = $request->company_id;
                    $pushNotif->date = $request->date;
                    $pushNotif->total_push_notification = $request->total_push_notification;
                    $pushNotif->save();
        
                    DB::commit();

                    return response()->json([
                        "result" => 'success',
                        "message" => "data updated success",
                        'data' => $pushNotif
                    ]);

                } else {
                    return response()->json([
                        "result" => 'failed',
                        "message" => "data with ID=".$id." not found",
                        'data' => $pushNotif
                    ]);
                }
                

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return response()->json(['result' => 'failed', 'error' => $e]);
            }
        }
    }

    public function daily_push_notification_delete($id)
    {
        $pushNotif = ReportDailyPushNotification::find($id);
        
        if (isset($pushNotif)) {
            $pushNotif->delete();
                
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

    /* ..................
        report Monthly push notification
        function:
    .......................*/
    public function Monthly_push_notification_create(Request $request)
    {
        $tran = PushNotificationLog::select('company_id')->groupBy('company_id')->get();
        $company_id = $tran->pluck('company_id')->toArray();

        $date = date('Y-m-d', strtotime("-1 days"));

        $fd = Carbon::createFromFormat('Y-m-d', $date)->startOfMonth();
        $ld = Carbon::createFromFormat('Y-m-d', $date)->endOfMonth();
        
        DB::beginTransaction();

        try {
            for ($j = 0; $j < count($company_id); $j++) {
                $data[$j] = ReportMonthlyPushNotification::where('company_id', '=', $company_id[$j])
                ->whereBetween('date', [$fd, $ld]);

                if ($data[$j]->count() !== 0){
                    $data[$j]->delete();
                }

                $collect[$j] = ReportDailyPushNotification::where('company_id', '=', $company_id[$j])
                ->whereBetween('created_at', [$fd, $ld])->get();

                ReportMonthlyPushNotification::create(array(
                    'company_id' => $company_id[$j],
                    'date' => $ld,
                    'total_push_notification' => array_sum($collect[$j]->pluck('total_push_notification')->toArray())
                ));
            }
            DB::commit();

            return response()->json([
                "result" => 'success',
                "message" => "successfully inserted data"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            return response()->json(['result' => 'failed', 'error' => $e]);
        }
    }

    public function Monthly_push_notification_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_id'=>'required|integer',
            'date'=>'date|date_format:Y-m-d',
            'total_push_notification'=>'required|integer',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n",$validator->errors()->all());
            return $errorString;
        } else {
            DB::beginTransaction();
 
            try {
                $pushNotif = ReportMonthlyPushNotification::find($id);

                if(isset($pushNotif)){
                    $pushNotif->company_id = $request->company_id;
                    $pushNotif->date = $request->date;
                    $pushNotif->total_push_notification = $request->total_push_notification;
                    $pushNotif->save();
        
                    DB::commit();

                    return response()->json([
                        "result" => 'success',
                        "message" => "data updated success",
                        'data' => $pushNotif
                    ]);

                } else {
                    return response()->json([
                        "result" => 'failed',
                        "message" => "data with ID=".$id." not found",
                        'data' => $pushNotif
                    ]);
                }
                

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return response()->json(['result' => 'failed', 'error' => $e]);
            }
        }
    }

    public function Monthly_push_notification_delete($id)
    {
        $pushNotif = ReportMonthlyPushNotification::find($id);
        
        if (isset($pushNotif)) {
            $pushNotif->delete();
                
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

    /* ..................
        push notification log
        function:
    .......................*/
    public function push_notification_log_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id'=>'required|integer',
            'no_push_notification_sent'=>'required|integer',            
            'target'=>'required|integer',
            'status'=>'required',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n",$validator->errors()->all());
            return $errorString;
        } else {
        
            DB::beginTransaction();
    
            try {
                $sendEmail = new PushNotificationLog();
                $sendEmail->company_id = $request->company_id;
                $sendEmail->no_push_notification_sent = $request->no_push_notification_sent;                
                $sendEmail->target = $request->target;                
                $sendEmail->status = $request->status;
                $sendEmail->save();
    
                DB::commit();

                return response()->json([
                    "result" => 'success',
                    "message" => "successfully inserted data",
                    'data' => $sendEmail
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return response()->json(['result' => 'failed', 'error' => $e]);
            }
        }
    }

    public function push_notification_log_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_id'=>'required|integer',
            'no_push_notification_sent'=>'required|integer',            
            'target'=>'required|integer',
            'status'=>'required',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n",$validator->errors()->all());
            return $errorString;
        } else {
            DB::beginTransaction();
 
            try {
                $sendEmail = PushNotificationLog::find($id);

                if(isset($sendEmail)){
                    $sendEmail = new PushNotificationLog();
                    $sendEmail->company_id = $request->company_id;
                    $sendEmail->no_push_notification_sent = $request->no_push_notification_sent;                    
                    $sendEmail->target = $request->target;                    
                    $sendEmail->status = $request->status;
                    $sendEmail->save();
        
                    DB::commit();

                    return response()->json([
                        "result" => 'success',
                        "message" => "data updated success",
                        'data' => $sendEmail
                    ]);

                } else {
                    return response()->json([
                        "result" => 'failed',
                        "message" => "data with ID=".$id." not found",
                        'data' => $sendEmail
                    ]);
                }
                

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return response()->json(['result' => 'failed', 'error' => $e]);
            }
        }
    }

    public function push_notification_log_delete($id)
    {
        $sendEmail = PushNotificationLog::find($id);
        
        if (isset($sendEmail)) {
            $sendEmail->delete();
                
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
