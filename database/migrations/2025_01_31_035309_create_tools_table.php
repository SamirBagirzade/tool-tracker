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
        Schema::create('tools', function (Blueprint $table) {
  $table->id();
        $table->string('name');
        $table->string('serial_number')->unique();
        $table->string('category');
        $table->string('image')->nullable(); // Store image path
        $table->foreignId('location_id')->nullable()->constrained()->onDelete('set null');
        $table->foreignId('employee_id')->nullable()->constrained()->onDelete('set null');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
