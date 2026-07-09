<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('customer_name');
            $table->string('mobile');
            $table->string('email')->nullable();
            $table->string('serial_no');
            $table->date('purchase_date');
            $table->string('dealer_name')->nullable();
            $table->string('invoice_path')->nullable();
            $table->string('status')->default('pending'); // pending, verified, rejected
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('admin_users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['serial_no', 'mobile']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranties');
    }
};
