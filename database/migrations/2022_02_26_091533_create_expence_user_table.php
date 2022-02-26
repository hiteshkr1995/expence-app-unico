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
        Schema::create('expence_user', function (Blueprint $table) {
            $table->unsignedBigInteger('expence_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('each_amount', $precision = 8, $scale = 2);
            $table->decimal('percent', $precision = 8, $scale = 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expence_user');
    }
};
