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
        Schema::table('lecture_topics', function (Blueprint $table) {
            $table->unsignedBigInteger('last_edited_by_id')->nullable()->after('user_id');
            $table->foreign('last_edited_by_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lecture_topics', function (Blueprint $table) {
            $table->dropColumn('last_edited_by_id');
        });
    }
};
