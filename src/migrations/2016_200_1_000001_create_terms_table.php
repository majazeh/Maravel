<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('creator_id')->nullable()->index();
            $table->unsignedBigInteger('subset_id')->nullable()->index();
            $table->string('title', 100)->index();
            $table->string('cat', 100)->index()->nullable();
            $table->string('type', 20)->index();
            $table->unique(['type', 'cat', 'title'], '');
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
        Schema::dropIfExists('terms');
    }
}
