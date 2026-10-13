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
        Schema::create('vaccine_lists', function (Blueprint $table) {
            $table->id();
            $table->string('vaccine_name');        
            $table->string('manufacturer');        
            $table->integer('quantity');           
            $table->date('production_date');       
            $table->date('expiry_date');       
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccine_lists');
    }
};
