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
        Schema::table('certificates', function (Blueprint $table) {
            // Renames the 'does_no' column to 'no_of_doses_taken'
            $table->renameColumn('does_no', 'no_of_doses_taken');
        });
    }
    
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            // This allows you to reverse the change if needed
            $table->renameColumn('no_of_doses_taken', 'does_no');
        });
    }
};
