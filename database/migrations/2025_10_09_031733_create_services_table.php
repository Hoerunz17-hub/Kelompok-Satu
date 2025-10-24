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
       Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('laptop_id')->constrained('laptops')->cascadeOnDelete();
            $table->text('damage_description')->nullable();
            $table->decimal('estimated_cost', 12, 2)->nullable();
            $table->enum('status', ['accepted', 'process', 'finished', 'taken', 'cancelled'])->default('accepted');
            $table->decimal('total_cost', 12, 2)->nullable();
            $table->decimal('other_cost', 12, 2)->nullable();
            $table->decimal('paid', 12, 2)->nullable();
            $table->decimal('change', 12, 2)->nullable();
            $table->enum('paymentmethod', ['cash', 'transfer'])->nullable();
            $table->enum('status_paid', ['paid', 'debt', 'unpaid'])->default('unpaid');
            $table->date('received_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->timestamps();
             $table->softDeletes(); // 🟢 tambahkan ini
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
