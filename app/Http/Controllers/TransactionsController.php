<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function transaction(Request $request)
    {
        return view('transactions.transaction');    
    }
    
    public function transactionExport(Request $request)
    {
        return view('transactions.transactionExport');    
    }
}
