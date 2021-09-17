<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionsLog;
use App\Models\requestedFile;
use App\Models\ReportDailyTransaction;
use App\Models\ReportWeeklyTransaction;
use App\Models\ReportMonthlyTransaction;
use App\Models\ReportYearlyTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Helpers\TransactionsHelper;
use App\Helpers\GlobalHelper;

class TransactionsController extends Controller
{
    /**
     * Show all transaction
     *
     * @param array $request = ['search_name'=> String,'search_daterange'=>String]
     * search_daterange : Y-m-d - Y-m-d
     * @param int $company_id
     * @return $response
     * 'result' => 'success',
     * 'data' => $data
     */

    public function transaction(Request $request, $company_id)
    {
        $collection = Transaction::where('company_id', '=', $company_id)->orderBy('transaction_created_at', 'desc')->get();

        //Function search by name, date range
        $data = [];
        
        $name = strtolower($request -> input('search_name'));
        $date_range = $request -> input('search_daterange');

        if ($name == null && $date_range == null) {
            return response()->json(['result' => 'success', 'data' => $collection]);
        } else {
            if (($date_range != null || $date_range != '') && ($name == null || $name == '')) {
                $date_range = explode(' - ', $date_range);
                $first_date = date('Y-m-d', strtotime($date_range[0]));
                $last_date = date('Y-m-d', strtotime($date_range[1]));

                foreach ($collection as $collect) {
                    $transaction_created_at = date('Y-m-d', strtotime($collect->transaction_created_at));
                    
                    if (($first_date <= $transaction_created_at && $last_date >= $transaction_created_at) || ($first_date >= $transaction_created_at && $last_date <= $transaction_created_at)) {
                        $data[] = $collect;
                    }
                }
            }

            if (($name != null || $name != '') && ($date_range == null || $date_range == '')) {
                foreach ($collection as $collect) {
                    $c_name = strtolower($collect->name);

                    similar_text($c_name, $name, $perc); //presentace of similarity between 2 data

                    if (number_format($perc, 0) > 50) {
                        $data[] = $collect;
                    }
                }
            }

            if (($name != null || $name != '') && ($date_range != null || $date_range != '')) {
                $date_range = explode(' - ', $date_range);
                $first_date = date('Y-m-d', strtotime($date_range[0]));
                $last_date = date('Y-m-d', strtotime($date_range[1]));

                foreach ($collection as $collect) {
                    $transaction_created_at = date('Y-m-d', strtotime($collect->transaction_created_at));
                    $c_name = strtolower($collect->name);

                    similar_text($c_name, $name, $perc); //presentace of similarity between 2 data

                    if (($first_date <= $transaction_created_at && $last_date >= $transaction_created_at && number_format($perc, 0) > 50) || ($first_date >= $transaction_created_at && $last_date <= $transaction_created_at && number_format($perc, 0) > 50)) {
                        $data[] = $collect;
                    }
                }
            }

            return response()->json(['result' => 'success', 'data' => $data]);
        }
    }

    /**
     * Store a new transaction
     *
     * @param $request ["company_id", "transaction_id", "transaction_created_at", "order_id", "total_price", "status", "name", "email", "phone", "address"]
     * @param $response
     * "result": "success",
     * 'message' => 'transaction added successfully',
     * 'data' => $transaction
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id'=>'required|integer',
            'transaction_id'=>'required|integer',
            'transaction_created_at'=>'required',
            'order_id'=>'required',
            'total_price'=>'required|integer',
            'status'=>'required|integer',
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required',
            'address'=>'required',
        ]);
 
        if ($validator->fails()) {
            $errorString = implode(", ", $validator->errors()->all());
            return $errorString;
        // return $validator->errors()->all();
        } else {
            DB::beginTransaction();
 
            try {
                // Step 1 : Create Transaction
                $transaction = new Transaction();
                $transaction->company_id = $request->company_id;
                $transaction->transaction_id = $request->transaction_id;
                $transaction->transaction_created_at = $request->transaction_created_at;
                $transaction->order_id = $request->order_id;
                $transaction->total_price = $request->total_price;
                $transaction->status = $request->status;
                $transaction->name = $request->name;
                $transaction->email = $request->email;
                $transaction->phone = $request->phone;
                $transaction->address = $request->address;
                $transaction->save();
     
                DB::commit();
    
                return response()->json(['result' => 'success','message' => 'transaction added successfully', 'data' => $transaction]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['result' => 'failed']);
            }
        }
    }
    
    /**
     * Update a transaction
     *
     * @param $request ["total_price", "status", "name", "email", "phone", "address"]
     * @param int $company_id
     * @param int $id
     * @param $response
     * "result": "success",
     * 'message' => 'Updated!',
     * 'data' => $transaction
     */
    public function update(Request $request, $company_id, $id)
    {
        $validator = Validator::make($request->all(), [
         
            'total_price'=>'required|integer',
            'status'=>'required|integer',
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required',
            'address'=>'required',
        ]);
 
        if ($validator->fails()) {
            $errorString = implode(",", $validator->errors()->all());
            return $errorString;
        // return $validator->errors()->all();
        } else {
            DB::beginTransaction();

            try {
                $transaction = Transaction::where('company_id', '=', $company_id)
                ->find($id);
        
                if (!$transaction) {
                    return response()->json(['result' => 'failed','message'=>'Transaction not found!']);
                }
                
                $transaction ->total_price = $request ->total_price;
                $transaction ->status = $request ->status;
                $transaction ->name = $request ->name;
                $transaction ->email = $request ->email;
                $transaction ->phone = $request ->phone;
                $transaction ->address = $request ->address;
                $transaction ->save();

                $transactionLog = new TransactionsLog;
                $transactionLog ->company_id =$transaction ->company_id;
                $transactionLog ->transaction_id =$transaction ->transaction_id;
                $transactionLog ->order_id =$transaction ->order_id;
                $transactionLog ->status = $request->status;
                $transactionLog ->save();
                
                DB::commit();
                
                return response([
                'result' => 'success',
                'messsage' => 'Updated!',
                'data' => $transaction
            ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['result' => 'failed']);
            }
        }
    }

    /**
     * Delete a transaction
     *
     * @param int $company_id
     * @param int $id
     * @param $response
     * "result": "success",
     * 'message' => 'Transaction deleted!',
     */
    public function delete($company_id, $id)
    {
        $transaction = Transaction::where('company_id', $company_id)
        ->find($id);
        
        if (!$transaction) {
            return response()->json(['result' => 'failed','message'=>'Transaction not found!']);
        }
        $transaction->delete();

        return response()->json(['result' => 'success','message'=>'Transaction deleted!']);
    }

    /**
     * Edit a transaction status
     *
     * @param $request (id, status)
     * @param int $company_id
     * @param $response
     * 'result' => 'success',
     * 'message' =>'order: '.$transaction ->order_id.' status has changed'
     */
    public function editStatus(Request $request, $company_id)
    {
        $id = $request -> input('id');
        $status = $request -> input('status');
        $transaction = Transaction::where('company_id', $company_id)->find($id);

        if (isset($transaction)) {
            $t_log = TransactionsLog::where('company_id', $company_id)->where('order_id', '=', $transaction->order_id);

            if ($t_log ->count() !== 0) {
                $t_log ->delete();
            }
            $transaction_log = new TransactionsLog;

            $transaction ->status = $status;
            $transaction_log ->status = $status;

            $transaction_log ->company_id = $transaction ->company_id;
            $transaction_log ->transaction_id = $transaction ->transaction_id;
            $transaction_log ->order_id = $transaction ->order_id;

            $transaction ->save();
            $transaction_log ->save();

            return response() ->json(['result' => 'success', 'message' =>'order: '.$transaction ->order_id.' status has changed']);
        } else {
            return response() ->json(['result' => 'fail']);
        }
    }
    
    ///BEGIN graph function

    /**
     * Show transactions from the given date and status
     *
     * @param $request ["date", "status"]
     * date -> Y-m-d,
     * status :
     * 0 -> all
     * 5 -> processed
     * 7 -> finished
     * @param int $company_id
     * @param $response
     * 'result': 'success',
     * 'tb_sales'  => $tb_sales ,   (total sales of last period)
     * 'tb_order'  => $tb_order ,   (total order of last period)
     * 'tb_avg'    => $tb_avg   ,   (sales/order of last period)
     *
     * 't_sales'   => $t_sales  ,   (total sales of current period)
     * 't_order'   => $t_order  ,   (total order of current period)
     * 't_avg'     => $t_avg    ,   (sales/order of current period)
     *
     * 'category'  => $periode  ,   (x axis array for graph)
     * 'sales'     => $sales    ,   (sales array for graph)
     * 'count'     => $count    ,   (order array for graph)
     * 'avg'       => $avg          (sales/order array for graph)
     */
    public function graphRealtime(Request $request, $company_id)
    {
        $date = date('Y-m-d', strtotime($request->input('date')));
        $status = $request->input('status');
        $today = date('Y-m-d');
        $first_date= Carbon::createFromFormat('Y-m-d', $today)->startOfDay();
        $last_date= Carbon::createFromFormat('Y-m-d', $today)->endOfDay();

        //get today transactions
        $transactions = Transaction::where('company_id', $company_id);
        $transactions_before = Transaction::where('company_id', $company_id);
        $transactions_graph = Transaction::where('company_id', $company_id);
        
        if ($status != 0) {
            $transactions = $transactions->where('status', '=', $status);
            $transactions_before = $transactions_before->where('status', '=', $status);
            $transactions_graph = $transactions_graph->where('status', '=', $status);
        } else {
            $transactions = $transactions->whereIn('status', [7, 5]);
            $transactions_before = $transactions_before->whereIn('status', [7, 5]);
            $transactions_graph = $transactions_graph->whereIn('status', [7, 5]);
        }

        $transactions_graph = $transactions_graph
        ->whereDate('transaction_created_at', $today)
        ->orderBy('transaction_created_at', 'asc')
        ->get();
        
        $transactions = $transactions->whereDate('transaction_created_at', $today)
        ->select(
            DB::raw('COUNT(*) as count'),
            DB::raw("SUM(total_price) as sales"),
            DB::raw('HOUR(transaction_created_at) as hour'),
        )
        ->groupBy('hour')
        ->get();
        //get yesterday transactions
        
        $transactions_before = $transactions_before->whereDate('transaction_created_at', date('Y-m-d', strtotime("-1 days")))
        ->select(
            DB::raw('COUNT(*) as count'),
            DB::raw("SUM(total_price) as sales"),
            DB::raw('HOUR(transaction_created_at) as hour'),
        )
        ->groupBy('hour')
        ->get();

        $t_sales = [strval(array_sum($transactions->pluck('sales')->toArray()))];
        $t_order = [strval(array_sum($transactions->pluck('count')->toArray()))];
        
        if ($t_order[0] != 0) {
            $t_avg = [strval(intval($t_sales[0]) / intval($t_order[0]))];
        } else {
            $t_sales = [strval(0)];
            $t_order = [strval(0)];
            $t_avg = [strval(0)];
        }
        
        $tb_sales = [strval(array_sum($transactions_before->pluck('sales')->toArray()))];
        $tb_order = [strval(array_sum($transactions_before->pluck('count')->toArray()))];
        
        if ($tb_order[0] != 0) {
            $tb_avg = [strval(intval($tb_sales[0]) / intval($tb_order[0]))];
        } else {
            $tb_sales = [strval(0)];
            $tb_order = [strval(0)];
            $tb_avg = [strval(0)];
        }

        $periode = array_keys(GlobalHelper::displayHours());
        
        if (count($transactions_graph) !== 0) { //When database not return null
            $arrDate = $transactions_graph->pluck('transaction_created_at')->toArray();
            $value_sales = $transactions_graph->pluck('total_price')->toArray();
            $count = TransactionsHelper::getValuesDay($arrDate, $periode, 'H:m', '%h', false, true);
            $sales = TransactionsHelper::getValuesDay($arrDate, $periode, 'H:m', '%h', $value_sales, true);
            $avg = TransactionsHelper::getValuesAvg($sales, $count);
        } else {
            //When database return null
            for ($i = 0; $i < count($periode); $i++) {
                $date_time = date_create(date('H:m', strtotime($periode[$i])));
                $now = date_create(date('H:m'));

                if ($date_time <= $now) {
                    $sales[$i] = 0;
                    $count[$i] = 0;
                    $avg[$i] = 0;
                } else {
                    $sales[$i] = null;
                    $count[$i] = null;
                    $avg[$i] = null;
                }
            }
        }

        return response() -> json([
            'result'    => 'success',
            
            'tb_sales'  => $tb_sales   ,
            'tb_order'  => $tb_order   ,
            'tb_avg'    => $tb_avg     ,

            't_sales'   => $t_sales   ,
            't_order'   => $t_order   ,
            't_avg'     => $t_avg     ,

            'category'  => $periode,
            'sales'     => $sales,
            'count'     => $count,
            'avg'       => $avg
        ]);
    }

    
    /**
     * Show transactions from last week - yesterday
     *
     * @param $request ["date", "status"]
     * date -> Y-m-d - Y-m-d,
     * status :
     * 0 -> all
     * 5 -> processed
     * 7 -> finished
     * @param int $company_id
     * @param $response
     * 'result': 'success',
     * 'tb_sales'  => $tb_sales ,   (total sales of last period)
     * 'tb_order'  => $tb_order ,   (total order of last period)
     * 'tb_avg'    => $tb_avg   ,   (sales/order of last period)
     *
     * 't_sales'   => $t_sales  ,   (total sales of current period)
     * 't_order'   => $t_order  ,   (total order of current period)
     * 't_avg'     => $t_avg    ,   (sales/order of current period)
     *
     * 'category'  => $periode  ,   (x axis array for graph)
     * 'sales'     => $sales    ,   (sales array for graph)
     * 'count'     => $count    ,   (order array for graph)
     * 'avg'       => $avg          (sales/order array for graph)
     */
    public function graphLastweek(Request $request, $company_id)
    {
        $date_range = $request->input('date');
        $status = $request->input('status');
        
        $date = explode(' - ', $date_range);
        $first_date = date('Y-m-d', strtotime($date[0]));
        $last_date = date('Y-m-d', strtotime($date[1]));

        $transactions = ReportDailyTransaction::where('company_id', $company_id);
        $total_now = ReportDailyTransaction::where('company_id', $company_id);
        $total_before = ReportDailyTransaction::where('company_id', $company_id);

        if ($status != 0) {
            $transactions = $transactions->where('status', '=', $status);
            $total_now = $total_now->where('status', '=', $status);
            $total_before = $total_before->where('status', '=', $status);
        }
        
        $transactions = $transactions->whereBetween('date', [$first_date, $last_date])
        ->select(
            DB::raw('SUM(order_sales) as order_sales'),
            DB::raw('SUM(order_count) as order_count'),
            'date'
        )
        ->groupBy('date')
        ->get();
        
        $avg = TransactionsHelper::getAvgArray($transactions->pluck('order_sales'), $transactions->pluck('order_count'));

        $total_now = $total_now->whereBetween('date', [$first_date, $last_date])
        ->select(
            DB::raw("SUM(order_sales) as order_sales"),
            DB::raw("SUM(order_count) as order_count"),
        )
        ->get();
        
        $t_avg = TransactionsHelper::getAvg($total_now[0]->order_sales, $total_now[0]->order_count);

        $total_before = $total_before->whereBetween(
            'date',
            [date('Y-m-d', strtotime('-7 day', strtotime($first_date))), date('Y-m-d', strtotime('-7 day', strtotime($last_date)))]
        )
        ->select(
            DB::raw("SUM(order_sales) as order_sales"),
            DB::raw("SUM(order_count) as order_count"),
        )
        ->get();
        
        $tb_avg = TransactionsHelper::getAvg($total_before[0]->order_sales, $total_before[0]->order_count);
        
        return response() -> json([
            'result' => 'success',
            
            'tb_sales' => $total_before->pluck('order_sales'),
            'tb_order' => $total_before->pluck('order_count'),
            'tb_avg' => $tb_avg,

            't_sales' => $total_now->pluck('order_sales'),
            't_order' => $total_now->pluck('order_count'),
            't_avg' => $t_avg,

            'category' => $transactions->pluck('date'),
            'sales' => $transactions->pluck('order_sales'),
            'count' => $transactions->pluck('order_count'),
            'avg' => $avg,

            ]);
    }

    /**
     * Show transactions from last month - yesterday
     *
     * @param $request ["date", "status"]
     * date -> Y-m-d - Y-m-d,
     * status :
     * 0 -> all
     * 5 -> processed
     * 7 -> finished
     * @param int $company_id
     * @param $response
     * 'result': 'success',
     * 'tb_sales'  => $tb_sales ,   (total sales of last period)
     * 'tb_order'  => $tb_order ,   (total order of last period)
     * 'tb_avg'    => $tb_avg   ,   (sales/order of last period)
     *
     * 't_sales'   => $t_sales  ,   (total sales of current period)
     * 't_order'   => $t_order  ,   (total order of current period)
     * 't_avg'     => $t_avg    ,   (sales/order of current period)
     *
     * 'category'  => $periode  ,   (x axis array for graph)
     * 'sales'     => $sales    ,   (sales array for graph)
     * 'count'     => $count    ,   (order array for graph)
     * 'avg'       => $avg          (sales/order array for graph)
     */
    public function graphLastmonth(Request $request, $company_id)
    {
        $date_range = $request->input('date');
        $status = $request->input('status');
        
        $date = explode(' - ', $date_range);
        $first_date = date('Y-m-d', strtotime($date[0]));
        $last_date = date('Y-m-d', strtotime($date[1]));

        $transactions = ReportDailyTransaction::where('company_id', $company_id);
        $total_now = ReportDailyTransaction::where('company_id', $company_id);
        $total_before = ReportDailyTransaction::where('company_id', $company_id);
        
        if ($status != 0) {
            $transactions = $transactions->where('status', '=', $status);
            $total_now = $total_now->where('status', '=', $status);
            $total_before = $total_before->where('status', '=', $status);
        }

        $transactions = $transactions
        ->whereBetween('date', [$first_date, $last_date])
        ->select(
            DB::raw('SUM(order_sales) as order_sales'),
            DB::raw('SUM(order_count) as order_count'),
            'date'
        )
        ->groupBy('date')
        ->get();
        
        $avg = TransactionsHelper::getAvgArray($transactions->pluck('order_sales'), $transactions->pluck('order_count'));

        $total_now = $total_now
        ->whereBetween('date', [$first_date, $last_date])
        ->select(
            DB::raw("SUM(order_sales) as order_sales"),
            DB::raw("SUM(order_count) as order_count"),
        )
        ->get();

        $t_avg = TransactionsHelper::getAvg($total_now[0]->order_sales, $total_now[0]->order_count);

        $total_before = $total_before->whereBetween(
            'date',
            [date('Y-m-d', strtotime('-30 day', strtotime($first_date))), date('Y-m-d', strtotime('-30 day', strtotime($last_date)))]
        )
        ->select(
            DB::raw("SUM(order_sales) as order_sales"),
            DB::raw("SUM(order_count) as order_count"),
        )
        ->get();

        $tb_avg = TransactionsHelper::getAvg($total_before[0]->order_sales, $total_before[0]->order_count);
        
        return response() -> json([
            'result' => 'success',

            'tb_sales' => $total_before->pluck('order_sales'),
            'tb_order' => $total_before->pluck('order_count'),
            'tb_avg' => $tb_avg,

            't_sales' => $total_now->pluck('order_sales'),
            't_order' => $total_now->pluck('order_count'),
            't_avg' => $t_avg,

            'category' => $transactions->pluck('date'),
            'sales' => $transactions->pluck('order_sales'),
            'count' => $transactions->pluck('order_count'),
            'avg' => $avg,
            ]);
    }

    /**
     * Show transactions from the selected date and status
     *
     * @param $request ["date", "status"]
     * date -> Y-m-d,
     * status :
     * 0 -> all
     * 5 -> processed
     * 7 -> finished
     * @param int $company_id
     * @param $response
     * 'result': 'success',
     * 'tb_sales'  => $tb_sales ,   (total sales of last period)
     * 'tb_order'  => $tb_order ,   (total order of last period)
     * 'tb_avg'    => $tb_avg   ,   (sales/order of last period)
     *
     * 't_sales'   => $t_sales  ,   (total sales of current period)
     * 't_order'   => $t_order  ,   (total order of current period)
     * 't_avg'     => $t_avg    ,   (sales/order of current period)
     *
     * 'category'  => $periode  ,   (x axis array for graph)
     * 'sales'     => $sales    ,   (sales array for graph)
     * 'count'     => $count    ,   (order array for graph)
     * 'avg'       => $avg          (sales/order array for graph)
     */
    public function graphDay(Request $request, $company_id)
    {
        $date = $request->input('date');
        $status = $request->input('status');

        $transactions = Transaction::where('company_id', $company_id);
        $total_now = ReportDailyTransaction::where('company_id', $company_id);
        $total_before = ReportDailyTransaction::where('company_id', $company_id);

        if ($status != 0) {
            $transactions = $transactions->where('status', '=', $status);
            $total_now = $total_now->where('status', '=', $status);
            $total_before = $total_before->where('status', '=', $status);
        } else {
            $transactions = $transactions->whereIn('status', [7, 5]);
            $total_now = $total_now->whereIn('status', [7, 5]);
            $total_before = $total_before->whereIn('status', [7, 5]);
        }

        $transactions = $transactions->whereDate('transaction_created_at', date('Y-m-d', strtotime($date)))
        ->orderBy('transaction_created_at', 'asc')
        ->get();
        
        $total_now = $total_now->whereDate('date', date('Y-m-d', strtotime($date)))
        ->select(
            DB::raw('SUM(order_sales) as order_sales'),
            DB::raw('SUM(order_count) as order_count'),
        )
        ->get();

        $total_before = $total_before->whereDate('date', date('Y-m-d', strtotime('-1 day', strtotime($date))))
        ->select(
            DB::raw('SUM(order_sales) as order_sales'),
            DB::raw('SUM(order_count) as order_count'),
        )
        ->get();

        $periode = array_keys(GlobalHelper::displayHours());
        
        if (count($transactions) !== 0) {
            //When database not return null
            $arrDate = $transactions->pluck('transaction_created_at')->toArray();
            $value_sales = $transactions->pluck('total_price')->toArray();
            $count = TransactionsHelper::getValuesDay($arrDate, $periode, 'H:m', '%h', false, false);
            $sales = TransactionsHelper::getValuesDay($arrDate, $periode, 'H:m', '%h', $value_sales, false);
            $avg = TransactionsHelper::getValuesAvg($sales, $count, $periode, false);
        } else {
            //When database return null
            for ($i = 0; $i < count($periode); $i++) {
                $sales[$i] = 0;
                $count[$i] = 0;
                $avg[$i] = 0;
            }
        }

        $t_sales = [strval(array_sum($transactions->pluck('order_sales')->toArray()))];
        $t_order = [strval(array_sum($transactions->pluck('order_count')->toArray()))];
        
        if ($t_order[0] != 0) {
            $t_avg = [strval(intval($t_sales[0]) / intval($t_order[0]))];
        } elseif (count($transactions) != 0 && $t_order[0] == 0) {
            $t_sales = [strval(array_sum($sales))];
            $t_order = [strval(array_sum($count))];
            $t_avg = [strval(intval($t_sales[0]) / intval($t_order[0]))];
        } else {
            $t_sales = [strval(0)];
            $t_order = [strval(0)];
            $t_avg = [strval(0)];
        }

        $tb_sales = [strval(array_sum($transactions->pluck('order_sales')->toArray()))];
        $tb_order = [strval(array_sum($transactions->pluck('order_count')->toArray()))];

        if ($tb_order[0] != 0) {
            $tb_avg = [strval(intval($tb_sales[0]) / intval($tb_order[0]))];
        } else {
            $tb_sales = [strval(0)];
            $tb_order = [strval(0)];
            $tb_avg = [strval(0)];
        }

        return response() -> json([
            'result' => 'success',
            
            'tb_sales'  =>  $tb_sales   ,
            'tb_order'  =>  $tb_order   ,
            'tb_avg'    =>  $tb_avg     ,

            't_sales'   =>  $t_sales   ,
            't_order'   =>  $t_order   ,
            't_avg'     =>  $t_avg     ,

            'category'  => $periode,
            'sales'     => $sales,
            'count'     => $count,
            'avg'       => $avg
        ]);
    }

    /**
     * Show transactions from the selected week and status
     *
     * @param $request ["date", "status"]
     * date -> Y-m-d - Y-m-d,
     * status :
     * 0 -> all
     * 5 -> processed
     * 7 -> finished
     * @param int $company_id
     * @param $response
     * 'result': 'success',
     * 'tb_sales'  => $tb_sales ,   (total sales of last period)
     * 'tb_order'  => $tb_order ,   (total order of last period)
     * 'tb_avg'    => $tb_avg   ,   (sales/order of last period)
     *
     * 't_sales'   => $t_sales  ,   (total sales of current period)
     * 't_order'   => $t_order  ,   (total order of current period)
     * 't_avg'     => $t_avg    ,   (sales/order of current period)
     *
     * 'category'  => $periode  ,   (x axis array for graph)
     * 'sales'     => $sales    ,   (sales array for graph)
     * 'count'     => $count    ,   (order array for graph)
     * 'avg'       => $avg          (sales/order array for graph)
     */
    public function graphWeek(Request $request, $company_id)
    {
        $date_range = $request->input('date');
        $status = $request->input('status');

        $date = explode(' - ', $date_range);
        $first_date = date('Y-m-d', strtotime($date[0]));
        $last_date = date('Y-m-d', strtotime($date[1]));

        $transactions = ReportDailyTransaction::where('company_id', $company_id);
        
        if ($status != 0) {
            $transactions = $transactions->where('status', '=', $status);
        }

        $transactions = $transactions->whereBetween('date', [$first_date, $last_date])
        ->select(
            DB::raw('SUM(order_sales) as order_sales'),
            DB::raw('SUM(order_count) as order_count'),
            'date'
        )
        ->groupBy('date')
        ->get();

        $dates = GlobalHelper::displayDates($date[0], $date[1], '+1 day', 'Y-m-d');
        $t = $transactions->pluck('date');
        // dd($dates);
        // dd($dates[2] < date('Y-m-d', strtotime('2020-11-30')));
        
        $s = 0;
        $c = 0;
        $a = 0;

        for ($i = 0; $i < count($dates); $i++) {
            for ($j = 0; $j < count($t); $j++) {
                if ($t[$j] == $dates[$i]) {
                    $s = $transactions[$j]->order_sales;
                    $c = $transactions[$j]->order_count;
                }
            }

            if (date('Y-m-d') < $last_date) {
                if ($dates[$i] < date('Y-m-d')) {
                    // if ($dates[$i] < date('Y-m-d', strtotime('2020-11-30'))) {
                    $sales[$i] = $s;
                    $count[$i] = $c;
                    $avg[$i] = TransactionsHelper::getAvg($s, $c);
                } else {
                    $sales[$i] = null;
                    $count[$i] = null;
                    $avg[$i] = null;
                }
            } else {
                $sales[$i] = $s;
                $count[$i] = $c;
                $avg[$i] = TransactionsHelper::getAvg($s, $c);
            }

            $s = 0;
            $c = 0;
            $a = 0;
        }

        if (date('Y-m-d') < $last_date) {
            $t_sales  = array_sum($sales);
            $t_order  = array_sum($count);
            $t_avg    = TransactionsHelper::getAvg($t_sales, $t_order);
        } else {
            $total_now = ReportWeeklyTransaction::where('company_id', $company_id);
            
            if ($status != 0) {
                $total_now = $total_now->where('status', '=', $status);
            }

            $total_now = $total_now->whereDate('start_date', date('Y-m-d', strtotime($first_date)))
            ->whereDate('end_date', date('Y-m-d', strtotime($last_date)))
            ->select(
                DB::raw('SUM(order_sales) as order_sales'),
                DB::raw('SUM(order_count) as order_count'),
            )
            ->get();

            $t_sales = $total_now->pluck('order_sales');
            $t_order = $total_now->pluck('order_count');
            $t_avg   = TransactionsHelper::getAvg($t_sales[0], $t_order[0]);
        }

        $total_before = ReportWeeklyTransaction::where('company_id', $company_id);

        if ($status != 0) {
            $total_before = $total_before->where('status', '=', $status);
        }
        
        $total_before = $total_before->whereDate('start_date', date('Y-m-d', strtotime('-7 day', strtotime($first_date))))
        ->whereDate('end_date', date('Y-m-d', strtotime('-7 day', strtotime($last_date))))
        ->select(
            DB::raw('SUM(order_sales) as order_sales'),
            DB::raw('SUM(order_count) as order_count'),
        )
        ->get();

        $tb_avg = TransactionsHelper::getAvg($total_before[0]->order_sales, $total_before[0]->order_count);
        
        return response() -> json([
            'result' => 'success',
            
            'tb_sales'  => $total_before->pluck('order_sales'),
            'tb_order'  => $total_before->pluck('order_count'),
            'tb_avg'    => $tb_avg,

            't_sales'   => $t_sales,
            't_order'   => $t_order,
            't_avg'     => $t_avg,
            
            'category' => $dates,
            'sales'     => $sales,
            'count'     => $count,
            'avg'       => $avg
        ]);
    }
    
    /**
     * Show transactions from the selected month and status
     *
     * @param $request ["date", "status"]
     * date -> Y-m-d - Y-m-d,
     * status :
     * 0 -> all
     * 5 -> processed
     * 7 -> finished
     * @param int $company_id
     * @param $response
     * 'result': 'success',
     * 'tb_sales'  => $tb_sales ,   (total sales of last period)
     * 'tb_order'  => $tb_order ,   (total order of last period)
     * 'tb_avg'    => $tb_avg   ,   (sales/order of last period)
     *
     * 't_sales'   => $t_sales  ,   (total sales of current period)
     * 't_order'   => $t_order  ,   (total order of current period)
     * 't_avg'     => $t_avg    ,   (sales/order of current period)
     *
     * 'category'  => $periode  ,   (x axis array for graph)
     * 'sales'     => $sales    ,   (sales array for graph)
     * 'count'     => $count    ,   (order array for graph)
     * 'avg'       => $avg          (sales/order array for graph)
     */
    public function graphMonth(Request $request, $company_id)
    {
        $date_range = $request->input('date');
        $status = $request->input('status');

        $date_now = date('');
        $date = explode(' - ', $date_range);
        $first_date = date('Y-m-d', strtotime($date[0]));
        $last_date = date('Y-m-d', strtotime($date[1]));

        //GET TRANSACTIONS COLLECTIONS
        $transactions = ReportDailyTransaction::where('company_id', $company_id);
        
        if ($status != 0) {
            $transactions = $transactions->where('status', '=', $status);
        }

        $transactions = $transactions->whereBetween('date', [$first_date, $last_date])
        ->select(
            DB::raw('SUM(order_sales) as order_sales'),
            DB::raw('SUM(order_count) as order_count'),
            'date'
        )
        ->groupBy('date')
        ->get();

        //get x axis array fo graph
        $dates = GlobalHelper::displayDates($first_date, $last_date, '+1 day', 'Y-m-d');
        $t = $transactions->pluck('date');

        $s = 0;
        $c = 0;
        $a = 0;

        for ($i = 0; $i < count($dates); $i++) {
            for ($j = 0; $j < count($t); $j++) {
                if ($t[$j] == $dates[$i]) {
                    $s = $transactions[$j]->order_sales;
                    $c = $transactions[$j]->order_count;
                }
            }

            if (date('Y-m-d') < $last_date) {
                //jika periode melewati hari ini
                if ($dates[$i] < date('Y-m-d')) {
                    //jika kurang dari hari ini push data
                    $sales[$i] = $s;
                    $count[$i] = $c;
                    $avg[$i] = TransactionsHelper::getAvg($s, $c);
                } else {
                    //jika lebih dari hari ini push null
                    $sales[$i] = null;
                    $count[$i] = null;
                    $avg[$i] = null;
                }
            } else {
                //data tidak melewati tgl hari ini diinput secara normal
                $sales[$i] = $s;
                $count[$i] = $c;
                $avg[$i] = TransactionsHelper::getAvg($s, $c);
            }

            $s = 0;
            $c = 0;
            $a = 0;
        }
            
        if (date('Y-m-d') < $last_date) {
            $t_sales  = array_sum($sales);
            $t_order  = array_sum($count);
            $t_avg    = TransactionsHelper::getAvg($t_sales, $t_order);
        } else {
            $total_now = ReportMonthlyTransaction::where('company_id', $company_id);
            
            if ($status != 0) {
                $total_now = $total_now->where('status', '=', $status);
            }

            $total_now = $total_now->whereDate('start_date', date('Y-m-d', strtotime($first_date)))
            ->whereDate('end_date', date('Y-m-d', strtotime($last_date)))
            ->select(
                DB::raw('SUM(order_sales) as order_sales'),
                DB::raw('SUM(order_count) as order_count'),
            )
            ->get();

            $t_sales = $total_now->pluck('order_sales');
            $t_order = $total_now->pluck('order_count');
            $t_avg   = TransactionsHelper::getAvg($t_sales[0], $t_order[0]);
        }

        $total_before = ReportMonthlyTransaction::where('company_id', $company_id);
        
        if ($status != 0) {
            $total_before = $total_before->where('status', '=', $status);
        }

        $total_before = $total_before->whereDate('start_date', date('Y-m-01', strtotime('-1 month', strtotime($first_date))))
        ->whereDate('end_date', date('Y-m-t', strtotime('-1 month', strtotime($last_date))))
        ->select(
            DB::raw('SUM(order_sales) as order_sales'),
            DB::raw('SUM(order_count) as order_count'),
        )
        ->get();
        
        $tb_avg = TransactionsHelper::getAvg($total_before[0]->order_sales, $total_before[0]->order_count);
        
        return response() -> json([
            'result' => 'success',
            
            'tb_sales'  => $total_before->pluck('order_sales'),
            'tb_order'  => $total_before->pluck('order_count'),
            'tb_avg'    => $tb_avg,

            't_sales'   => $t_sales,
            't_order'   => $t_order,
            't_avg'     => $t_avg,
            
            'category' => $dates,
            'sales'     => $sales,
            'count'     => $count,
            'avg'       => $avg
        ]);
    }
    
    /**
     * Show transactions from the selected year and status
     *
     * @param $request ["date", "status"]
     * date -> Y-m-d - Y-m-d,
     * status :
     * 0 -> all
     * 5 -> processed
     * 7 -> finished
     * @param int $company_id
     * @param $response
     * 'result': 'success',
     * 'tb_sales'  => $tb_sales ,   (total sales of last period)
     * 'tb_order'  => $tb_order ,   (total order of last period)
     * 'tb_avg'    => $tb_avg   ,   (sales/order of last period)
     *
     * 't_sales'   => $t_sales  ,   (total sales of current period)
     * 't_order'   => $t_order  ,   (total order of current period)
     * 't_avg'     => $t_avg    ,   (sales/order of current period)
     *
     * 'category'  => $periode  ,   (x axis array for graph)
     * 'sales'     => $sales    ,   (sales array for graph)
     * 'count'     => $count    ,   (order array for graph)
     * 'avg'       => $avg          (sales/order array for graph)
     */
    public function graphYear(Request $request, $company_id)
    {
        $date_range = $request->input('date');
        $status = $request->input('status');

        $date = explode(' - ', $date_range);
        $first_date = date('Y-m-d', strtotime($date[0]));
        $last_date = date('Y-m-d', strtotime($date[1]));
        $today = date('Y-m-d');

        $transactions = ReportMonthlyTransaction::where('company_id', $company_id);
        
        if ($status != 0) {
            $transactions = $transactions->where('status', '=', $status);
        }

        $transactions = $transactions->whereBetween('start_date', [$first_date, $last_date])
        ->select(
            DB::raw('SUM(order_sales) as order_sales'),
            DB::raw('SUM(order_count) as order_count'),
            DB::raw('MONTH(start_date) as date'),
        )
        ->groupBy('date')
        ->get();

        $dates = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        
        $t = $transactions->pluck('date');
    
        $s = 0;
        $c = 0;
        $a = 0;
    
        for ($i = 0; $i < count($dates); $i++) {
            for ($j = 0; $j < count($t); $j++) {
                if ($t[$j] == $dates[$i]) {
                    $s = $transactions[$j]->order_sales;
                    $c = $transactions[$j]->order_count;
                }
            }
            
            if (date('Y-m-d') < $last_date) {
                if ($dates[$i] < date('m')) {
                    $sales[] = $s;
                    $count[] = $c;
                    $avg[] = TransactionsHelper::getAvg($s, $c);
                } else {
                    $sales[] = null;
                    $count[] = null;
                    $avg[] = null;
                }
            } else {
                $sales[] = $s;
                $count[] = $c;
                $avg[] = TransactionsHelper::getAvg($s, $c);
            }

            $s = 0;
            $c = 0;
            $a = 0;
        }
            
        if (date('Y-m-d') < $last_date) {
            $t_sales  = array_sum($sales);
            $t_order  = array_sum($count);
            $t_avg    = TransactionsHelper::getAvg($t_sales, $t_order);
        } else {
            $total_now = ReportYearlyTransaction::where('company_id', $company_id);
            
            if ($status != 0) {
                $total_now = $total_now->where('status', '=', $status);
            }
    
            $total_now = $total_now
            ->whereDate('start_date', date('Y-m-d', strtotime($first_date)))
            ->whereDate('end_date', date('Y-m-d', strtotime($last_date)))
            ->select(
                DB::raw('SUM(order_sales) as order_sales'),
                DB::raw('SUM(order_count) as order_count'),
            )
            ->get();

            $t_sales = $total_now->pluck('order_sales');
            $t_order = $total_now->pluck('order_count');
            $t_avg   = TransactionsHelper::getAvg($t_sales[0], $t_order[0]);
        }

        $total_before = ReportYearlyTransaction::where('company_id', $company_id);
        
        if ($status != 0) {
            $total_before = $total_before->where('status', '=', $status);
        }

        $total_before = $total_before
        ->whereDate('start_date', date('Y-m-01', strtotime('-1 year', strtotime($first_date))))
        ->whereDate('end_date', date('Y-m-t', strtotime('-1 year', strtotime($last_date))))
        ->select(
            DB::raw('SUM(order_sales) as order_sales'),
            DB::raw('SUM(order_count) as order_count'),
        )
        ->get();
        
        $tb_avg = TransactionsHelper::getAvg($total_before[0]->order_sales, $total_before[0]->order_count);

        return response() -> json([
            'result' => 'success',
            
            'tb_sales'  =>  $total_before->pluck('order_sales'),
            'tb_order'  =>  $total_before->pluck('order_count'),
            'tb_avg'    =>  $tb_avg,

            't_sales'   => $t_sales,
            't_order'   => $t_order,
            't_avg'     => $t_avg,
            
            'category' => $dates,
            'sales'     => $sales,
            'count'     => $count,
            'avg'       => $avg
            ]);
    }
    ///END graph function

    /**
     * Get the today's transactions from each status
     *
     * @param $request ("status")
     * @param int $company_id
     * @param $response 'result' => 'success', 'data' => $wordCount
     */
    public function countTransaction(Request $request, $company_id)
    {
        $status = $request -> input('status');
        // dd(Carbon::create('2020-11-17')->add('1 day')->subminutes(rand(1, 720)));
        $date = date_create(date('Y-m-d'));
        date_time_set($date, 00, 00);

        $first_date = date_format($date, 'Y-m-d H:i:s');
        $last_date = date('Y-m-d H:i:s');

        $wordCount = Transaction::where('company_id', '=', $company_id)
                                ->where('status', '=', $status)
                                ->whereBetween('transaction_created_at', [$first_date, $last_date])
                                ->count();

        return response()->json(['result' => 'success', 'data' => $wordCount]);
    }

    //Begin Function Create Daily, weekly, monthly, Yearly report

    /**
     * Store yesterday's transactions to report_daily_transactions
     *
     * @param $request
     * @param $response 'result' => 'success' | 'failed'
     */
    public function daily_transaction_create(Request $request)
    {
        $tran = Transaction::select('company_id')->groupBy('company_id')->get();
        $company_id = $tran->pluck('company_id')->toArray();

        $date = date('Y-m-d', strtotime("-1 days"));

        $first_date= Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
        $last_date= Carbon::createFromFormat('Y-m-d', $date)->endOfDay();

        DB::beginTransaction();

        try {
            for ($j = 0; $j < count($company_id); $j++) {
                $data[$j] = ReportDailyTransaction::where('company_id', '=', $company_id[$j])
                ->whereBetween('date', [$first_date, $last_date]);
    
                if ($data[$j]->count() !== 0) {
                    $data[$j]->delete();
                }

                $collect[$j] = Transaction::where('company_id', '=', $company_id[$j])
                ->where('status', '=', 7)
                ->whereBetween('transaction_created_at', [$first_date, $last_date])->get();

                $collect2[$j] = Transaction::where('company_id', '=', $company_id[$j])
                ->where('status', '=', 5)
                ->whereBetween('transaction_created_at', [$first_date, $last_date])->get();

                $count = count($collect[$j]);
                $os = array_sum($collect[$j]->pluck('total_price')->toArray());

                if ($count == 0) {
                    $ops = 0;
                } else {
                    $ops = TransactionsHelper::getAvg($os, $count);
                }

                $count2 = count($collect2[$j]);
                $os2 = array_sum($collect2[$j]->pluck('total_price')->toArray());

                if ($count2 == 0) {
                    $ops2 = 0;
                } else {
                    $ops2 = TransactionsHelper::getAvg($os2, $count2);
                }
                
                ReportDailyTransaction::create(array(
                    'company_id' => $company_id[$j],
                    'order_sales' => $os,
                    'order_count' => $count,
                    'sales_per_order' => $ops,
                    'status' => 7,
                    'date' => $date,
                ));
    
                ReportDailyTransaction::create(array(
                    'company_id' => $company_id[$j],
                    'order_sales' => $os2,
                    'order_count' => $count2,
                    'sales_per_order' => $ops2,
                    'status' => 5,
                    'date' => $date,
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

    /**
     * Store this week's transactions to report_weekly_transactions
     *
     * @param $request
     * @param $response 'result' => 'success' | 'failed'
     */
    public function weekly_transaction_create(Request $request)
    {
        $tran = ReportDailyTransaction::select('company_id')->groupBy('company_id')->get();
        $company_id = $tran->pluck('company_id')->toArray();

        $date = date('Y-m-d', strtotime("-1 days"));

        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);

        $first_date= Carbon::createFromFormat('Y-m-d', $date)->startOfWeek();
        $last_date= Carbon::createFromFormat('Y-m-d', $date)->endOfWeek();

        DB::beginTransaction();

        try {
            for ($j = 0; $j < count($company_id); $j++) {
                $data[$j] = ReportWeeklyTransaction::where('company_id', '=', $company_id[$j])
                ->whereBetween('date', [$first_date, $last_date]);
                
                if ($data[$j]->count() !== 0) {
                    $data[$j]->delete();
                }

                $collect[$j] = ReportDailyTransaction::where('company_id', '=', $company_id[$j])
                ->where('status', '=', 7)
                ->whereBetween('start_date', [$first_date, $last_date])->get();

                $collect2[$j] = ReportDailyTransaction::where('company_id', '=', $company_id[$j])
                ->where('status', '=', 5)
                ->whereBetween('start_date', [$first_date, $last_date])->get();

                $os = array_sum($collect[$j]->pluck('order_sales')->toArray());
                $oc = array_sum($collect[$j]->pluck('order_count')->toArray());

                if ($oc == 0) {
                    $ops = 0;
                } else {
                    $ops = TransactionsHelper::getAvg($os, $oc);
                }

                $os2 = array_sum($collect2[$j]->pluck('order_sales')->toArray());
                $oc2 = array_sum($collect2[$j]->pluck('order_count')->toArray());
    
                if ($oc2 == 0) {
                    $ops2 = 0;
                } else {
                    $ops2 = TransactionsHelper::getAvg($os2, $oc2);
                }

                ReportWeeklyTransaction::create(array(
                    'company_id' => $company_id[$j],
                    'order_sales' => $os,
                    'order_count' => $oc,
                    'sales_per_order' => $ops,
                    'status' => 7,
                    'start_date' => $first_date,
                    'end_date' => $last_date,
                ));

                ReportWeeklyTransaction::create(array(
                    'company_id' => $company_id[$j],
                    'order_sales' => $os2,
                    'order_count' => $oc2,
                    'sales_per_order' => $ops2,
                    'status' => 5,
                    'start_date' => $first_date,
                    'end_date' => $last_date,
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

    /**
     * Store this month's transactions to report_monthly_transactions
     *
     * @param $request
     * @param $response 'result' => 'success' | 'failed'
     */
    public function monthly_transaction_create(Request $request)
    {
        $tran = ReportWeeklyTransaction::select('company_id')->groupBy('company_id')->get();
        $company_id = $tran->pluck('company_id')->toArray();

        $date = date('Y-m-d', strtotime("-1 days"));

        $first_date= Carbon::createFromFormat('Y-m-d', $date)->startOfMonth();
        $last_date= Carbon::createFromFormat('Y-m-d', $date)->endOfMonth();

        DB::beginTransaction();

        try {
            for ($j = 0; $j < count($company_id); $j++) {
                $data[$j] = ReportMonthlyTransaction::where('company_id', '=', $company_id[$j])
                ->whereBetween('date', [$first_date, $last_date]);

                if ($data[$j]->count() !== 0) {
                    $data[$j]->delete();
                }

                $collect[$j] = ReportWeeklyTransaction::where('company_id', '=', $company_id[$j])
                ->where('status', '=', 7)
                ->whereBetween('start_date', [$first_date, $last_date])->get();

                $collect2[$j] = ReportWeeklyTransaction::where('company_id', '=', $company_id[$j])
                ->where('status', '=', 5)
                ->whereBetween('start_date', [$first_date, $last_date])->get();
                
                $os = array_sum($collect[$j]->pluck('order_sales')->toArray());
                $oc = array_sum($collect[$j]->pluck('order_count')->toArray());

                if ($oc == 0) {
                    $ops = 0;
                } else {
                    $ops = TransactionsHelper::getAvg($os, $oc);
                }

                $os2 = array_sum($collect2[$j]->pluck('order_sales')->toArray());
                $oc2 = array_sum($collect2[$j]->pluck('order_count')->toArray());
    
                if ($oc2 == 0) {
                    $ops2 = 0;
                } else {
                    $ops2 = TransactionsHelper::getAvg($os2, $oc2);
                }
    
                ReportMonthlyTransaction::create(array(
                    'company_id' => $company_id[$j],
                    'order_sales' => $os,
                    'order_count' => $oc,
                    'sales_per_order' => $ops,
                    'status' => 7,
                    'start_date' => $first_date,
                    'end_date' => $last_date,
                ));

                ReportMonthlyTransaction::create(array(
                    'company_id' => $company_id[$j],
                    'order_sales' => $os2,
                    'order_count' => $oc2,
                    'sales_per_order' => $ops2,
                    'status' => 5,
                    'start_date' => $first_date,
                    'end_date' => $last_date,
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

    /**
     * Store this year's transactions to report_yearly_transactions
     *
     * @param $request
     * @param $response 'result' => 'success' | 'failed'
     */
    public function yearly_transaction_create(Request $request)
    {
        $tran = ReportMonthlyTransaction::select('company_id')->groupBy('company_id')->get();
        $company_id = $tran->pluck('company_id')->toArray();

        $date = date('Y-m-d', strtotime("-1 days"));
        
        $first_date= Carbon::createFromFormat('Y-m-d', $date)->startOfYear();
        $last_date= Carbon::createFromFormat('Y-m-d', $date)->endOfYear();

        DB::beginTransaction();

        try {
            for ($j = 0; $j < count($company_id); $j++) {
                $data = ReportYearlyTransaction::where('company_id', '=', $company_id[$j])
                ->whereBetween('date', [$first_date, $last_date]);

                if ($data->count() !== 0) {
                    $data->delete();
                }
                $collect[$j] = ReportDailyTransaction::where('company_id', '=', $company_id[$j])
                ->where('status', '=', 7)
                ->whereBetween('start_date', [$first_date, $last_date])->get();

                $collect2[$j] = ReportDailyTransaction::where('company_id', '=', $company_id[$j])
                ->where('status', '=', 5)
                ->whereBetween('start_date', [$first_date, $last_date])->get();

                $os = array_sum($collect[$j]->pluck('order_sales')->toArray());
                $oc = array_sum($collect[$j]->pluck('order_count')->toArray());

                if ($oc == 0) {
                    $ops = 0;
                } else {
                    $ops = TransactionsHelper::getAvg($os, $oc);
                }

                $os2 = array_sum($collect2[$j]->pluck('order_sales')->toArray());
                $oc2 = array_sum($collect2[$j]->pluck('order_count')->toArray());
    
                if ($oc2 == 0) {
                    $ops2 = 0;
                } else {
                    $ops2 = TransactionsHelper::getAvg($os2, $oc2);
                }
    
                ReportYearlyTransaction::create(array(
                    'company_id' => $company_id[$j],
                    'order_sales' => $os,
                    'order_count' => $oc,
                    'sales_per_order' => $ops,
                    'status' => 7,
                    'start_date' => $first_date,
                    'end_date' => $last_date,
                ));

                ReportYearlyTransaction::create(array(
                    'company_id' => $company_id[$j],
                    'order_sales' => $os2,
                    'order_count' => $oc2,
                    'sales_per_order' => $ops2,
                    'status' => 5,
                    'start_date' => $first_date,
                    'end_date' => $last_date,
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
}
