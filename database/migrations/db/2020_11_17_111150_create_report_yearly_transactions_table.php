<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportYearlyTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('report_yearly_transactions')) {
            Schema::create('report_yearly_transactions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('order_sales');
                $table->unsignedBigInteger('order_count');
                $table->unsignedBigInteger('sales_per_order');
                $table->unsignedBigInteger('status');
                $table->date('start_date');
                $table->date('end_date');

                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_yearly_transactions');
    }
}
