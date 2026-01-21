<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->uuid('transfer_id')->primary();
            $table->uuid('user_id');
            $table->uuid('target_user');
            $table->decimal('amount', 15, 2);
            $table->string('remarks');
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->enum('status', ['PENDING', 'SUCCESS', 'FAILED'])->default('PENDING');
            $table->timestamp('created_date')->useCurrent();
            
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('target_user')->references('user_id')->on('users')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};