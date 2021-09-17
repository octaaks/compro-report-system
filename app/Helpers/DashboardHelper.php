<?php

namespace App\Helpers;
use App\Models\ReportDailyUserRegister;
use App\Models\ReportDailySendEmail;
use App\Models\ReportDailyPushNotification;
use App\Models\ReportMonthlyUserRegister;
use App\Models\ReportMonthlySendEmail;
use App\Models\ReportMonthlyPushNotification;
use App\Models\UserRegisterLog;
use App\Models\SendEmailLog;
use App\Models\PushNotificationLog;
use Carbon\Carbon;

class DashboardHelpers
{
    public static function getData($category, $first_date, $last_date, $company_id){
        $year = date_diff(date_create($first_date), date_create($last_date))->format('%m');
        $fd = Carbon::createFromFormat('Y-m-d', $first_date)->startOfDay();
        $ld = Carbon::createFromFormat('Y-m-d', $last_date)->endOfDay();

        //access database
        if ($first_date == $last_date) {
            if ($category == 1 || $category == 'undefined') {
                $collection = UserRegisterLog::where('company_id','=',$company_id)->whereBetween('created_at', [$fd, $ld])->orderBy('created_at', 'asc')->get();
            } if ($category == 2) {
                $collection = SendEmailLog::where('company_id','=',$company_id)->whereBetween('created_at', [$fd, $ld])->orderBy('created_at', 'asc')->get();
            } if ($category == 3) {
                $collection = PushNotificationLog::where('company_id','=',$company_id)->whereBetween('created_at', [$fd, $ld])->orderBy('created_at', 'asc')->get();
            }
        } else {
            if ($year > 1) {
                if ($category == 1 || $category == 'undefined') {
                    $collection = ReportMonthlyUserRegister::where('company_id','=',$company_id)->whereBetween('date', [$first_date, $last_date])->orderBy('date', 'asc')->get();
                } if ($category == 2) {
                    $collection = ReportMonthlySendEmail::where('company_id','=',$company_id)->whereBetween('date', [$first_date, $last_date])->orderBy('date', 'asc')->get();
                } if ($category == 3) {
                    $collection = ReportMonthlyPushNotification::where('company_id','=',$company_id)->whereBetween('date', [$first_date, $last_date])->orderBy('date', 'asc')->get();
                }

            } else {
                if ($category == 1 || $category == 'undefined') {
                    $collection = ReportDailyUserRegister::where('company_id','=',$company_id)->whereBetween('date', [$fd, $ld])->orderBy('date', 'asc')->get();
                } if ($category == 2) {
                    $collection = ReportDailySendEmail::where('company_id','=',$company_id)->whereBetween('date', [$fd, $ld])->orderBy('date', 'asc')->get();
                } if ($category == 3) {
                    $collection = ReportDailyPushNotification::where('company_id','=',$company_id)->whereBetween('date', [$fd, $ld])->orderBy('date', 'asc')->get();
                }
            }
        }
        return $collection;
    }

    public static function getCategoryName($category) {
        switch ($category) {
            case 1: case 'undefined':
                $cat = 'user_register';
                break;
            case 2:
                $cat = 'send_email';
                break;
            case 3:
                $cat = 'push_notification';
                break;
        }
        return $cat;
    }

    public static function getValues($array_data, $array_time, $formatInput, $formatResult, $values, $realtime) {//get values using calculation date/time different between array

        for ($j = 0; $j < count($array_data); $j++) {
            for ($i = 0; $i < count($array_time); $i++) {
                $today = date_create(date($formatInput));
                $date_time = date_create(date($formatInput, strtotime($array_time[$i])));
                $data_date = date_create(date($formatInput, strtotime($array_data[$j])));
                $diff = date_diff($data_date, $date_time)->format($formatResult);

                if ($values !== false) {
                    if ($diff == 0) {
                        $diffResult[$i] = $values[$j];
                    } else {
                        if (isset($diffResult[$i])) {
                            $diffResult[$i] = $diffResult[$i];
                        } else {
                            $diffResult[$i] = 0;
                        }
                    }

                    if ($realtime && $date_time >= $today) {
                        $diffResult[$i] = null;
                    }
                } else {
                    if (!isset($diffResult[$i])) {
                        $nmb = 0;
                    } else if (isset($diffResult[$i])) {
                        $nmb = $diffResult[$i];
                    }

                    if ($diff == 0) {
                        $diffResult[$i] = $nmb + 1;
                    } else {
                        if (isset($diffResult[$i])) {
                            $diffResult[$i] = $diffResult[$i];
                        } else {
                            $diffResult[$i] = 0;
                        }
                    }
                    
                    if ($realtime && $date_time >= $today){
                        $diffResult[$i] = null;
                    }
                }
            
            }
        }

        return $diffResult;
    }
}
