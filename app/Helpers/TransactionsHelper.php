<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
 
class TransactionsHelper
{
    /**
     * get array of values for sales or order
     *
     * @param int $id
     * @param array $array_data -> array of transactions dates
     * @param array $array_time -> array of transactions time (0,1,2 ...... 22,23)
     * @param  $formatInput -> time format ('H:m')
     * @param  $formatResult -> ('%h')
     * @param array|bool $val :
     * array of sales -> for getting array of sales
     * false -> for getting array of order
     * @param array $val :
     * true -> get full 24 hours data
     * false -> get realtime data
     * @param $response
     * $diffResult
     */
    public static function getValuesDay($array_data, $array_time, $formatInput, $formatResult, $val, $realtime)
    {
        //get values using calculation date/time different between array

        for ($j = 0; $j < count($array_data); $j++) {
            for ($i = 0; $i < count($array_time); $i++) {
                $today = date_create(date($formatInput));
                $date_time = date_create(date($formatInput, strtotime($array_time[$i])));
                $data_date = date_create(date($formatInput, strtotime($array_data[$j])));
                $diff = date_diff($data_date, $date_time)->format($formatResult);

                if ($val !== false) {
                    if ($diff == 0) {
                        if (isset($diffResult[$i])) {
                            $diffResult[$i] = $diffResult[$i] + $val[$j];
                        } else {
                            $diffResult[$i] = $val[$j];
                        }
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
                    } elseif (isset($diffResult[$i])) {
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
                
                    if ($realtime && $date_time >= $today) {
                        $diffResult[$i] = null;
                    }
                }
            }
        }

        return $diffResult;
    }

    /**
     * get sales per order array
     *
     * @param array $arr1
     * @param array $arr2
     * @param $response
     * $diffResult
     */
    public static function getValuesAvg($array1, $array2)
    {//get Average values between 2 array

        for ($i = 0; $i < count($array1); $i++) {
            if (($array1[$i] !== null && $array2[$i] !== null) || ($array1[$i] != 0 && $array2[$i] != 0)) {
                $diffResult[$i] = TransactionsHelper::getAvg($array1[$i], $array2[$i]);
            } else {
                $diffResult[$i] = $array1[$i];
            }
            
            // if ($realtime && $date_time >= $today) {
            //     $diffResult[$i] = null;
            // }
        }

        return $diffResult;
    }

    /**
     * get sales per order value
     *
     * @param int $value1
     * @param int $value2
     * @param $response
     * $avg
     */
    public static function getAvg($value1, $value2)
    {
        if ((int)$value1 == null || (int)$value2 == null) {
            $avg = 0;
        } elseif ((int)$value1 == 0 || (int)$value2 == 0) {
            $avg = 0;
        } else {
            $avg = (int)$value1 / (int)$value2;
        }
        return number_format($avg, 2, ",", "");
    }

    /**
     * get sales per order array
     *
     * @param array $arr1
     * @param array $arr2
     * @param $response
     * $avgArray
     */
    public static function getAvgArray($arr1, $arr2)
    {//get Average values between 2 array

        for ($i = 0; $i < count($arr1); $i++) {
            if ((int)$arr1[$i] == null || (int)$arr2[$i] == null) {
                $avgArray[$i] = 0;
            } elseif ((int)$arr1[$i] == 0 || (int)$arr2[$i] == 0) {
                $avgArray[$i] = 0;
            } else {
                $avgArray[$i] = TransactionsHelper::getAvg($arr1[$i], $arr2[$i]);
            }
        }

        return $avgArray;
    }
}
