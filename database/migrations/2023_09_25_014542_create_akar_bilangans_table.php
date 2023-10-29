<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('akar_bilangans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bilangan');
            $table->double('akar');
            $table->double('waktu_pemrosesan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akar_bilangans');
    }
};
