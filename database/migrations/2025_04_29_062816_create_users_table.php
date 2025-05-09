<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->innoDb();
            $table->uuid('id')->primary();
            $table->string('email', 80)->unique();
            $table->string('password');
            $table->string('phone', 20);
            $table->string('last_name', 40);
            $table->string('first_name', 40);
            $table->string('middle_name', 40);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('users');
    }
};
