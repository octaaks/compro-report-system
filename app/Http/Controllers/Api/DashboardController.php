<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\ReportDailyUserRegister;
use App\Models\ReportDailySendEmail;
use App\Models\ReportDailyPushNotification;
use App\Models\ReportMonthlyUserRegister;
use App\Models\ReportMonthlySendEmail;
use App\Models\ReportMonthlyPushNotification;
use App\Models\UserRegisterLog;
use App\Models\SendEmailLog;
use App\Models\PushNotificationLog;
use App\Helpers\DashboardHelpers;
use App\Helpers\GlobalHelper;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboardGraph(Request $request, $company_id)
    {
        //request input
        $category = $request->input('category');
        $date_range = $request->input('periode');

        //implement variable
        $today = date('Y-m-d');
        $value = [];
        $periode = [];

        $date_range = explode(' - ', $date_range);
        $first_date = date('Y-m-d', strtotime($date_range[0]));
        $last_date = date('Y-m-d', strtotime($date_range[1]));

        $year = date_diff(date_create($first_date), date_create($last_date))->format('%m');

        $category_name = DashboardHelpers::getCategoryName($category);//category name
        $collection = DashboardHelpers::getData($category, $first_date, $last_date, $company_id);// data

        $date = GlobalHelper::displayDates($first_date, $last_date, '+1 day', 'Y-m-d');
        $date_month = GlobalHelper::displayDates($first_date, $last_date, '+1 month', 'Y-m');

        $hours = array_keys(GlobalHelper::displayHours());

        //match data with request date
        $year = date_diff(date_create($first_date), date_create($last_date))->format('%m');
        $fd = Carbon::createFromFormat('Y-m-d', $first_date)->startOfDay();
        $ld = Carbon::createFromFormat('Y-m-d', $last_date)->endOfDay();

        
        if ($first_date == $last_date) { //single date
            $periode = $hours;
            if (count($collection) !== 0) { //When database not return null
                $arrDate = $collection->pluck('created_at')->toArray();

                if ($last_date == $today) { //realtime
                    $value = DashboardHelpers::getValues($arrDate, $hours, 'H:m', '%h', false, true);
                } else { //yesterday or selected date
                    $value = DashboardHelpers::getValues($arrDate, $hours, 'H:m', '%h', false, false);
                }
            } else { //When database return null
                if ($last_date == $today) { // realtime
                    for ($i = 0; $i < count($hours); $i++) {
                        $date_time = date_create(date('H:m', strtotime($hours[$i])));
                        $now = date_create(date('H:m'));

                        if ($date_time <= $now) {
                            $value[$i] = 0;
                        } else {
                            $value[$i] = null;
                        }
                    }
                } else {
                    for ($i = 0; $i < count($hours); $i++) {
                        $value[$i] = 0;
                    }
                }
            }
        } else { //date range
            $value = $collection->pluck('total_'.$category_name)->toArray();

            $daterange = $collection->pluck('date')->toArray();

            if ($year > 1) {
                $periode = GlobalHelper::displayDates($first_date, $last_date, '+1 month', 'M');// not use for now
            } else {
                $periode = GlobalHelper::displayDates($first_date, $last_date, '+1 day', 'd');
            }

            $first_array_date = reset($daterange);
            $last_array_date = end($daterange);

            if (count($collection) !== 0 && ($first_array_date !== $first_date || $last_array_date !== $last_date || count($daterange) !== count($periode)) && $value !== null) {
                $arrDate = $daterange;

                if ($year > 1) { //When first date && last date not same (year)
                    $value = DashboardHelpers::getValues($arrDate, $date_month, 'Y-m', '%d%m', $value, true);
                } else { //When first date && last date not same (week, month)
                    $value = DashboardHelpers::getValues($arrDate, $date, 'Y-m-d', '%d', $value, true);
                }
            }

            if ($value == null) {
                $month = GlobalHelper::displayDates($first_date, $last_date, '+1 day', 'Y-m-d');
                for ($i = 0; $i < count($periode); $i++) {
                    $value[$i] = 0;

                    if ($month[$i] >= $today) {
                        $value[$i] = null;
                    }
                }
            }
        }

        //return response of Api
        $array = ([
            'value'=> $value,
            'periode'=> $periode,
        ]);

        return response()->json(['result'=> 'success', 'data'=> $array]);
    }
}
