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
            // Adds the new column after 'my_name'
            $table->string('vaccine_name')->after('my_name');
        });
    }
    
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            // This will remove the column if you ever roll back
            $table->dropColumn('vaccine_name');
        });
    }
};
