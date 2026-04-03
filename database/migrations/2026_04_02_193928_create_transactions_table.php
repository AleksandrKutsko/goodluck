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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->string('external_id')->unique();
            $table->string('uuid')->unique()->nullable();

            $table->foreignId('payment_system_id')->constrained();
            $table->foreignId('transaction_type_id')->constrained()->restrictOnDelete();
            $table->string('transaction_status_code')->default('pending');
            $table->foreign('transaction_status_code')->references('code')->on('transaction_statuses')->restrictOnDelete();
            $table->string('transaction_sub_status_code')->nullable();
            $table->foreign('transaction_sub_status_code')->references('code')->on('transaction_sub_statuses')->restrictOnDelete();
            $table->string('bank_code')->nullable();
            $table->foreign('bank_code')->references('code')->on('banks')->nullOnDelete();

            $table->decimal('amount', 13, 2);
            $table->string('currency')->nullable();
            $table->text('payment_link')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
