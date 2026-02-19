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
      Schema::create('installments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('loan_id')->constrained()->onDelete('cascade');
    $table->integer('installment_number');
    $table->date('due_date');
    $table->decimal('amount', 15, 2);
    $table->decimal('penalty', 15, 2)->default(0);
    $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
    $table->timestamps();

    $table->index('loan_id');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
