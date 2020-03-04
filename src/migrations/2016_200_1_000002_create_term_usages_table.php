<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('term_usages', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('term_id');
            $table->string('table_name', 50);
            $table->unsignedBigInteger('table_id');
            $table->timestamps();

            $table->foreign('term_id')->references('id')->on('terms');
            $table->unique(['term_id', 'table_name'], 'term_id_table_area');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('term_usages');
    }
}
