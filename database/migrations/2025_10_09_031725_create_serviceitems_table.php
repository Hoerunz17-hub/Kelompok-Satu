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
        Schema::create('serviceitems', function (Blueprint $table) {
            $table->id();
            $table->string('service_name');
            $table->decimal('price', 12, 2);
            $table->enum('is_active', ['active', 'nonactive'])->default('active');
            $table->timestamps();
             $table->softDeletes(); // 🟢 tambahkan ini
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serviceitems');
    }
};
