<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpis', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('kpi_no',10)->comment('รหัสตัวชี้วัด');
            $table->MEDIUMTEXT('kpi_title')->comment('ชื่อตัวชี้วัด');
            $table->bigInteger('approve_status_id')->unsigned()->index()->comment('สถานะอนุมัติการส้าง');
            $table->foreign('approve_status_id')->references('id')->on('approve_statuses')->onDelete('cascade');
            $table->char('budget_year',4)->comment('ปีงบประมาณ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kpis');
    }
}
