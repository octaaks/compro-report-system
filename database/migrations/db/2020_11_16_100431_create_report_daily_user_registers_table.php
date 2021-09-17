<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportDailyUserRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('report_daily_user_registers')) {
            Schema::create('report_daily_user_registers', function (Blueprint $table) {
                    $table->bigIncrements('id');
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
        Schema::dropIfExists('report_daily_user_registers');
    }
}
