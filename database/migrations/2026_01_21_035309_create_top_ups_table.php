<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
 public function up(): void
    {
        Schema::create('top_ups', function (Blueprint $table) {
            $table->uuid('top_up_id')->primary();
            $table->uuid('user_id');
            $table->decimal('amount_top_up', 15, 2);
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->timestamp('created_date')->useCurrent();
            
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('top_ups');
    }
};