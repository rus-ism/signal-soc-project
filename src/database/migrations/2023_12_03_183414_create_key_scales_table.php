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
        Schema::create('key_scales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_key_id');
            $table->unsignedBigInteger('scale_id');

            $table->timestamps();

            $table->foreign(['quiz_key_id'])->references(['id'])->on('quiz_keys')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['scale_id'])->references(['id'])->on('scales')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('key_scales');
    }
};
