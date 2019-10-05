<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkToGuardPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guard_positions', function (Blueprint $table) {
            $table->foreign('guard_id')->references('id')->on('guards');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guard_positions', function (Blueprint $table) {
            $table->dropForeign(['guard_id']);
        });
    }
}
