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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('customer_type', 50)->nullable();
            $table->string('company')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('preferred_contact');
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('tax_identification_number', 50)->nullable();
            $table->string('referred_by')->nullable();
            $table->unsignedBigInteger('assigned_agent_id')->nullable();
            $table->foreign('assigned_agent_id')->references('id')->on('agents');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamp('added_on')->useCurrent();
            $table->timestamp('updated_on')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
