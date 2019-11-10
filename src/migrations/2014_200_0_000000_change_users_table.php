<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('password')->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->string('email')->nullable()->change();

            $table->string('mobile')->unique()->nullable()->after('email');
            $table->string('username')->unique()->nullable()->after('mobile');

            $table->string('gender')->nullable()->after('mobile'); // ['male', 'female']
            $table->string('status')->default('waiting')->after('gender'); // ['waiting', 'active', 'block']
            $table->string('type')->default('user')->after('status'); // ['guest', 'user', 'admin']
            $table->string('groups')->nullable()->after('type');

            $table->unsignedBigInteger('avatar_id')->nullable()->after('status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropColumn('mobile');
            $table->dropColumn('username');
            $table->dropColumn('gender');
            $table->dropColumn('status');
            $table->dropColumn('type');
            $table->dropColumn('groups');
            $table->dropColumn('avatar');
            $table->string('password')->nullable(false)->change();
            $table->string('name')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
       });
    }
}
