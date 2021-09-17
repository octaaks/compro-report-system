<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\requestedFile;
use App\Models\Transaction;

class RequestedFileController extends Controller
{
    public function file(Request $request, $company_id)
    {
        //Function search by name, date range
        $type = $request->input('search_type');
        $date_range = $request->input('search_daterange');

        if ($type == 'all' || $type == null || $type == '') {
            $collection = requestedFile::where('company_id', $company_id)->orderBy('created_at', 'asc')->get();

        } else {
            $collection = requestedFile::where('company_id', $company_id)->where('type','=',$type)->orderBy('created_at', 'asc')->get();

        }
        
        if ($date_range == null || $date_range == '') {
            return response()->json(['result'=> 'success', 'data'=> $collection]);

        } else {
            if ($date_range != null || $date_range != '') {
                $date_range = explode(' - ' ,$date_range);
                $first_date = date('Y-m-d', strtotime($date_range[0]));
                $last_date = date('Y-m-d', strtotime($date_range[1]));

                foreach ($collection as $collect) {
                    $date_time = date('Y-m-d', strtotime($collect->created_at));

                    if (($first_date <= $date_time && $last_date >= $date_time) || ($first_date >= $date_time && $last_date <= $date_time)) {
                        $data[] = $collect;
                    } 
                }
            }

            return response()->json(['result'=> 'success', 'data'=> $data]);
        }
    }

    function deleteFile(Request $request, $company_id)
    {
        $id = $request->input('id');
        $reqFile = requestedFile::where('company_id', $company_id)->destroy($id);
        // $reqFile = requestedFile::where('id', $id);
        // $reqFile->delete();
        return response()->json(['result'=> 'success']); 
    }

    function downloadFile(Request $request, $company_id)
    {
        $id = $request->input('id');
        $reqFile = requestedFile::where('company_id', $company_id)->where('id', $id)->get();

        $data_date = $reqFile->pluck('data_date');
        $status = $reqFile->pluck('data_status');
        $type = $reqFile->pluck('type');

        if ($status[0] == 'all') {
            $collection = Transaction::where('company_id', $company_id)->get();
        } else {
            $collection = Transaction::where('company_id', $company_id)->where('status', $status[0])->get();
        }

        $date_range = explode(' - ' ,$data_date[0]);
        $first_date = date('Y-m-d', strtotime($date_range[0]));
        $last_date = date('Y-m-d', strtotime($date_range[1]));

        foreach ($collection as $collect) {
            $date_time = date('Y-m-d', strtotime($collect->date_time));

            if (($first_date <= $date_time && $last_date >= $date_time) || ($first_date >= $date_time && $last_date <= $date_time)) {
                $data[] = $collect;
            } 
        }

        return response()->json(['result'=> 'success', 'data'=> $data, 'type'=> $type]);
    }
}
