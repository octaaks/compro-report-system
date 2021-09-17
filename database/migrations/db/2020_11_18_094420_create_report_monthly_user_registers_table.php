<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportMonthlyUserRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('report_monthly_user_registers')) {
            Schema::create('report_monthly_user_registers', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id');
                $table->date('date');
                $table->unsignedBigInteger('total_user_register');

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
        Schema::dropIfExists('report_monthly_user_registers');
    }
}
