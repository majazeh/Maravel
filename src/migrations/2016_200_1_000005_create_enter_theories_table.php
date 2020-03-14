<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnterTheoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enter_theories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key', 110)->index();
            $table->string('theory', 50)->index();
            $table->string('value', 110)->nullable();
            $table->string('trigger', 50)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('meta')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
            $table->unique(['key', 'value']);
            $table->foreign('parent_id')->references('id')->on('enter_theories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enter_theories');
    }
}
