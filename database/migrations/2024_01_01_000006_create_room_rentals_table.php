<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained();
            $table->string('guest_name');
            $table->string('guest_phone')->nullable();
            $table->datetime('check_in');
            $table->datetime('check_out')->nullable();
            $table->integer('nights')->default(1);
            $table->decimal('original_price', 10, 2);
            $table->enum('discount_type', ['none', 'percentage', 'fixed'])->default('none');
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'telebirr', 'cbe_birr', 'credit'])->default('cash');
            $table->enum('payment_status', ['paid', 'pending'])->default('paid');
            $table->foreignId('receptionist_id')->constrained('users');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_rentals');
    }
};
