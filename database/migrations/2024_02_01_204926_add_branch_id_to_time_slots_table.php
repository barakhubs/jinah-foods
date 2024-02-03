<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('time_slots', 'branch_id'))
        {
            Schema::table('time_slots', function (Blueprint $table) {
                $table->unsignedBigInteger('branch_id')->after('id')->nullable();
                $table->foreign('branch_id')->references('id')->on('branches');
            });
        }
       
        Schema::table('time_slots', function (Blueprint $table) {
            $table->unique(['branch_id', 'opening_time', 'closing_time', 'day']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('time_slots', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropUnique(['branch_id', 'opening_time', 'closing_time', 'day']);
            $table->foreign('branch_id')->references('id')->on('branches');
        });
    }
};
