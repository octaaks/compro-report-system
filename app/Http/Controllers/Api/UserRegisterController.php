<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\ReportDailyUserRegister;
use App\Models\ReportMonthlyUserRegister;
use App\Models\UserRegisterLog;
use Carbon\Carbon;

class UserRegisterController extends Controller
{
    /* ..................
        report daily user registered
        function:
    .......................*/
    public function daily_user_register_create(Request $request)
    {
        $tran = UserRegisterLog::select('company_id')->groupBy('company_id')->get();
        $company_id = $tran->pluck('company_id')->toArray();

        $date = date('Y-m-d', strtotime("-1 days"));

        $fd = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
        $ld = Carbon::createFromFormat('Y-m-d', $date)->endOfDay();

        DB::beginTransaction();

        try {
            for ($j = 0; $j < count($company_id); $j++) {
                $data[$j] = ReportDailyUserRegister::where('company_id', '=', $company_id[$j])
                ->whereBetween('date', [$fd, $ld]);

                if ($data[$j]->count() !== 0){
                    $data[$j]->delete();
                }

                $collect[$j] = UserRegisterLog::where('company_id', '=', $company_id[$j])
                ->whereBetween('created_at', [$fd, $ld])->count();

                ReportDailyUserRegister::create(array(
                    'company_id' => $company_id[$j],
                    'date' => $date,
                    'total_user_register' => $collect[$j]
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

    public function daily_user_register_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_id'=>'required|integer',
            'date'=>'date|date_format:Y-m-d',
            'total_user_register'=>'required|integer',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n",$validator->errors()->all());
            return $errorString;
        } else {
            DB::beginTransaction();
 
            try {
                $userRegister = ReportDailyUserRegister::find($id);

                if(isset($userRegister)){
                    $userRegister->company_id = $request->company_id;
                    $userRegister->date = $request->date;
                    $userRegister->total_user_register = $request->total_user_register;
                    $userRegister->save();
        
                    DB::commit();

                    return response()->json([
                        "result" => 'success',
                        "message" => "data updated success",
                        'data' => $userRegister
                    ]);

                } else {
                    return response()->json([
                        "result" => 'failed',
                        "message" => "data with ID=".$id." not found",
                        'data' => $userRegister
                    ]);
                }
                

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return response()->json(['result' => 'failed', 'error' => $e]);
            }
        }
    }

    public function daily_user_register_delete($id)
    {
        $userRegister = ReportDailyUserRegister::find($id);
        
        if (isset($userRegister)) {
            $userRegister->delete();
                
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
        report monthly user registered
        function:
    .......................*/
    public function Monthly_user_register_create(Request $request)
    {
        $tran = ReportDailyUserRegister::select('company_id')->groupBy('company_id')->get();
        $company_id = $tran->pluck('company_id')->toArray();

        $date = date('Y-m-d', strtotime("-1 days"));

        $fd = Carbon::createFromFormat('Y-m-d', $date)->startOfMonth();
        $ld = Carbon::createFromFormat('Y-m-d', $date)->endOfMonth();

        DB::beginTransaction();

        try {
            for ($j = 0; $j < count($company_id); $j++) {
                $data[$j] = ReportMonthlyUserRegister::where('company_id', '=', $company_id[$j])
                ->whereBetween('date', [$fd, $ld]);

                if ($data[$j]->count() !== 0){
                    $data[$j]->delete();
                }

                $collect[$j] = ReportDailyUserRegister::where('company_id', '=', $company_id[$j])
                ->whereBetween('created_at', [$fd, $ld])->get();

                ReportMonthlyUserRegister::create(array(
                    'company_id' => $company_id[$j],
                    'date' => $ld,
                    'total_user_register' => array_sum($collect[$j]->pluck('total_user_register')->toArray())
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

    public function Monthly_user_register_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_id'=>'required|integer',
            'date'=>'date|date_format:Y-m-d',
            'total_user_register'=>'required|integer',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n",$validator->errors()->all());
            return $errorString;
        } else {
            DB::beginTransaction();
 
            try {
                $userRegister = ReportMonthlyUserRegister::find($id);

                if(isset($userRegister)){
                    $userRegister->company_id = $request->company_id;
                    $userRegister->date = $request->date;
                    $userRegister->total_user_register = $request->total_user_register;
                    $userRegister->save();
        
                    DB::commit();

                    return response()->json([
                        "result" => 'success',
                        "message" => "data updated success",
                        'data' => $userRegister
                    ]);

                } else {
                    return response()->json([
                        "result" => 'failed',
                        "message" => "data with ID=".$id." not found",
                        'data' => $userRegister
                    ]);
                }
                

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return response()->json(['result' => 'failed', 'error' => $e]);
            }
        }
    }

    public function Monthly_user_register_delete($id)
    {
        $userRegister = ReportMonthlyUserRegister::find($id);
        
        if (isset($userRegister)) {
            $userRegister->delete();
                
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
        user registered log
        function:
    .......................*/
    public function user_register_log_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id'=>'required|integer',
            'company_user_id'=>'required|integer',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n",$validator->errors()->all());
            return $errorString;
        } else {
        
            DB::beginTransaction();
    
            try {
                $userRegisterLog = new UserRegisterLog();
                $userRegisterLog->company_id = $request->company_id;
                $userRegisterLog->company_user_id = $request->company_user_id;
                $userRegisterLog->save();
    
                DB::commit();

                return response()->json([
                    "result" => 'success',
                    "message" => "successfully inserted data",
                    'data' => $userRegisterLog
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return response()->json(['result' => 'failed', 'error' => $e]);
            }
        }
    }

    public function user_register_log_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_id'=>'required|integer',
            'company_user_id'=>'required|integer',
        ]);

        if ($validator->fails()) {
            $errorString = implode("\n",$validator->errors()->all());
            return $errorString;
        } else {
            DB::beginTransaction();
 
            try {
                $userRegisterLog = UserRegisterLog::find($id);

                if(isset($userRegisterLog)){
                    $userRegisterLog = new UserRegisterLog();
                    $userRegisterLog->company_id = $request->company_id;
                    $userRegisterLog->company_user_id = $request->company_user_id;
                    $userRegisterLog->save();
        
                    DB::commit();

                    return response()->json([
                        "result" => 'success',
                        "message" => "data updated success",
                        'data' => $userRegisterLog
                    ]);

                } else {
                    return response()->json([
                        "result" => 'failed',
                        "message" => "data with ID=".$id." not found",
                        'data' => $userRegisterLog
                    ]);
                }
                

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return response()->json(['result' => 'failed', 'error' => $e]);
            }
        }
    }

    public function user_register_log_delete($id)
    {
        $userRegisterLog = UserRegisterLog::find($id);
        
        if (isset($userRegisterLog)) {
            $userRegisterLog->delete();
                
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
