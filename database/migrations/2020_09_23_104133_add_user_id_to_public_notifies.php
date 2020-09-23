<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToPublicNotifies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('public_notifies', function (Blueprint $table) {
            //
            DB::statement('DELETE FROM public_notifies');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('public_notifies', function (Blueprint $table) {
            //
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
