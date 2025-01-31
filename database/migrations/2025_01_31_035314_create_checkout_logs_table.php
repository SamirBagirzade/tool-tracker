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
        Schema::create('checkout_logs', function (Blueprint $table) {
    $table->id();
        $table->foreignId('tool_id')->constrained()->onDelete('cascade');
        $table->foreignId('employee_id')->constrained()->onDelete('cascade');
        $table->foreignId('location_id')->constrained()->onDelete('cascade');
        $table->timestamp('checked_out_at')->useCurrent();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_logs');
    }
};
