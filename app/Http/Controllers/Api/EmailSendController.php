<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\ReportDailySendEmail;
use App\Models\ReportMonthlySendEmail;
use App\Models\SendEmailLog;
use Carbon\Carbon;

class EmailSendController extends Controller
{
    /* ..................
        report daily send email
        function:
    .......................*/
    public function daily_send_email_create(Request $request)
    {
        $tran = SendEmailLog::select('company_id')->groupBy('company_id')->get();
        $company_id = $tran->pluck('company_id')->toArray();

        $date = date('Y-m-d', strtotime("-1 days"));

        $fd = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
        $ld = Carbon::createFromFormat('Y-m-d', $date)->endOfDay();

        DB::beginTransaction();

        try {
            for ($j = 0; $j < count($company_id); $j++) {
                $data[$j] = ReportDailySendEmail::where('company_id', '=', $company_id[$j])
                ->whereBetween('date', [$fd, $ld]);

                if ($data[$j]->count() !== 0){
                    $data[$j]->delete();
                }

                $collect[$j] = SendEmailLog::where('company_id', '=', $company_id[$j])
                ->whereBetween('created_at', [$fd, $ld])->count();

                ReportDailySendEmail::create(array(
                    'company_id' => $company_id[$j],
                    'date' => $date,
                    'total_send_email' => $collect[$j]
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

    public function daily_send_email_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_id'=>'required|integer',
            'date'=>'date|date_format:Y-m-d',
            'total_send_email'=>'required|integer',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n",$validator->errors()->all());
            return $errorString;
        } else {
            DB::beginTransaction();
 
            try {
                $sendEmail = ReportDailySendEmail::find($id);

                if(isset($sendEmail)){
                    $sendEmail->company_id = $request->company_id;
                    $sendEmail->date = $request->date;
                    $sendEmail->total_send_email = $request->total_send_email;
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

    public function daily_send_email_delete($id)
    {
        $sendEmail = ReportDailySendEmail::find($id);
        
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

    /* ..................
        report Monthly send email
        function:
    .......................*/
    public function Monthly_send_email_create(Request $request)
    {
        $tran = ReportDailySendEmail::select('company_id')->groupBy('company_id')->get();
        $company_id = $tran->pluck('company_id')->toArray();

        $date = date('Y-m-d', strtotime("-1 days"));

        $fd = Carbon::createFromFormat('Y-m-d', $date)->startOfMonth();
        $ld = Carbon::createFromFormat('Y-m-d', $date)->endOfMonth();

        DB::beginTransaction();

        try {
            for ($j = 0; $j < count($company_id); $j++) {
                $data = ReportMonthlySendEmail::where('company_id', '=', $company_id[$j])
                ->whereBetween('date', [$fd, $ld]);

                if ($data->count() !== 0){
                    $data->delete();
                }

                $collect = ReportDailySendEmail::where('company_id', '=', $company_id[$j])
                ->whereBetween('created_at', [$fd, $ld])->get();

                ReportMonthlySendEmail::create(array(
                    'company_id' => $company_id[$j],
                    'date' => $ld,
                    'total_send_email' => array_sum($collect[$j]->pluck('total_send_email')->toArray())
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

    public function Monthly_send_email_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_id'=>'required|integer',
            'date'=>'date|date_format:Y-m-d',
            'total_send_email'=>'required|integer',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n",$validator->errors()->all());
            return $errorString;
        } else {
            DB::beginTransaction();
 
            try {
                $sendEmail = ReportMonthlySendEmail::find($id);

                if(isset($sendEmail)){
                    $sendEmail->company_id = $request->company_id;
                    $sendEmail->date = $request->date;
                    $sendEmail->total_send_email = $request->total_send_email;
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

    public function Monthly_send_email_delete($id)
    {
        $sendEmail = ReportMonthlySendEmail::find($id);
        
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

    /* ..................
        send email log
        function:
    .......................*/
    public function send_email_log_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id'=>'required|integer',
            'no_email_sent'=>'required|integer',            
            'email_used_for'=>'required',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n",$validator->errors()->all());
            return $errorString;
        } else {
        
            DB::beginTransaction();
    
            try {
                $sendEmail = new SendEmailLog();
                $sendEmail->company_id = $request->company_id;
                $sendEmail->no_email_sent = $request->no_email_sent;                
                $sendEmail->email_used_for = $request->email_used_for;
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

    public function send_email_log_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_id'=>'required|integer',
            'no_email_sent'=>'required|integer',            
            'email_used_for'=>'required',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n",$validator->errors()->all());
            return $errorString;
        } else {
            DB::beginTransaction();
 
            try {
                $sendEmail = SendEmailLog::find($id);

                if(isset($sendEmail)){
                    $sendEmail = new SendEmailLog();
                    $sendEmail->company_id = $request->company_id;
                    $sendEmail->no_email_sent = $request->no_email_sent;                    
                    $sendEmail->email_used_for = $request->email_used_for;
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

    public function send_email_log_delete($id)
    {
        $sendEmail = SendEmailLog::find($id);
        
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
