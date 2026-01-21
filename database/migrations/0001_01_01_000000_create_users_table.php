<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('user_id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number')->unique();
            $table->text('address');
            $table->string('pin');
            $table->decimal('balance', 15, 2)->default(0);
            $table->timestamp('created_date')->useCurrent();
            $table->timestamp('updated_date')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};