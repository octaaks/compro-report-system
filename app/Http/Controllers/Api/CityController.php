<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\requestedFile;

class CityController extends Controller
{
    public function citiesTransaction(Request $request, $company_id)
    {
        $collection = City::all();
        
        $searchKey = strtolower($request->input('search'));

        if ($searchKey == null || $searchKey == '') {
            return response()->json(['result' => 'success', 'data' => $collection]);
        } else {
            foreach ($collection as $collect) {
                $c_name = strtolower($collect ->name);
                
                similar_text($c_name, $searchKey, $perc);//presentace of similarity between 2 data
                
                if ((number_format($perc, 0) > 90)) {
                    $data[] = $collect;
                }
            }
            
            return response()->json(['result' => 'success', 'data' => $data]);
        }
    }

    public function citiesExport(Request $request, $company_id)
    {
        $type = $request->input('type');
        
        $reqFile = new requestedFile;
        $reqFile ->file_name = 'cities.'.$type;
        $reqFile ->type = $type;
        $reqFile ->data_status = "-";
        $reqFile ->data_date = 0;
        $reqFile ->status = 0;
        $reqFile ->save();
       
        return response()->json(
            ['result' => 'success',
             'message' => 'Check "requested file progress" menu for download',
             'type' => 'success'
             ]
        );
    }

    public function downloadCitiesFile(Request $request, $company_id)
    {
        $collection = City::all();
        $type = "xls";

        return response()->json(['result' => 'success','data'=>$collection, 'type' =>$type]);
    }
}
