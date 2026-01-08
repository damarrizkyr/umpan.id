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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('field_id')->constrained()->cascadeOnDelete();
            $table->foreignId('schedule_id')->constrained('field_schedules')->cascadeOnDelete();

            $table->string('customer_name');
            $table->string('customer_phone');
            $table->date('booking_date');
            $table->integer('total_price');
            $table->string('booking_code')->unique();

            $table->enum('status', ['pending', 'confirmed', 'cancelled'])
                ->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid'])
                ->default('unpaid');

            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->unique(['field_id', 'schedule_id', 'booking_date']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
