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
        Schema::create('quaeres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_topic_id')->constrained()->cascadeOnDelete();
            $table->integer('sort_order')->nullable();
            $table->string('question');
            $table->text('description')->nullable();

            $table->tinyInteger('difficulty')->default(3);

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->unsignedBigInteger('last_edited_by_id')->nullable();
            $table->foreign('last_edited_by_id')->references('id')->on('users');

            $table->softDeletes();
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
        Schema::dropIfExists('quaeres');
    }
};
