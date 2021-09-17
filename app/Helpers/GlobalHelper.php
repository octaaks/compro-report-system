<?php

namespace App\Helpers;

use App\Models\Product;

class GlobalHelper
{
    const max_storage = 18000;
    const storage_size = 'MB';

    /**
     * get array of hours in a day
     *
     * @param int $start
     * @param int $end
     * @param int $step
     * @param $format -> 'H:i'
     *
     * @param $response
     * $times
     */
    public static function displayHours($start = 0, $end = 86400, $step = 3600, $format = 'H:i')
    {//return time interval in a day
        $times = array();

        foreach (range($start, $end, $step) as $timestamp) {
            $hour_mins = gmdate('H:i', $timestamp);

            if (!empty($format)) {
                $times[$hour_mins] = gmdate($format, $timestamp);
            } else {
                $times[$hour_mins] = $hour_mins;
            }
        }

        return $times;
    }

    /**
     * get array of dates from date range
     *
     * @param $date1
     * @param $date2
     * @param $stepVal -> '+1 day'
     * @param $format -> 'Y-m-d'
     *
     * @param $response
     * $dates
     */
    public static function displayDates($date1, $date2, $stepVal, $format)
    {//return date interval between 2 dates
        $dates = array();
        $current = strtotime($date1);
        $date2 = strtotime($date2);
        // $stepVal = '+1 day';
        $c = 0;

        while ($current <= $date2) {
            $dates[] = date($format, $current);
            if ($format == 'd' && $dates[$c] == '1') {
                $dates[$c] = date('d M', $current);
            }
            $current = strtotime($stepVal, $current);
            $c++;
        }

        if ($format == 'd') {
            $dates[0] = date('d M', strtotime($date1));
        } elseif ($format == 'M') {
            $dates[0] = date('Y M', strtotime($date1));
        }


        return $dates;
    }

    /**
     * get number of products with 0 stock
     *
     * @param int $company_id
     * @param $response
     * $total_data
     */
    public static function countOutOfStock($company_id)
    {
        $total_data = Product::where([['company_id', '=', $company_id],['stock', '=', 0]])->count();

        return $total_data;
    }
}
