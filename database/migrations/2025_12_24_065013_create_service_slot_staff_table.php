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
        Schema::create('service_slot_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_slot_id')->constrained('service_slots')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // staff/admin
            $table->timestamps();

            $table->unique(['service_slot_id', 'user_id'], 'unique_slot_staff');
            $table->index(['service_slot_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_slot_staff');
    }
};
