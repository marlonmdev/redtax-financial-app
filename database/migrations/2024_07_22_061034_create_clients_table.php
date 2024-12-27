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
            $table->string('name');
            $table->text('contact_details');
            $table->text('address');
            $table->string('tax_identification_number', 50);
            $table->string('segment', 50);
            $table->unsignedBigInteger('assigned_agent_id')->nullable();
            $table->foreign('assigned_agent_id')->references('id')->on('agents');
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
