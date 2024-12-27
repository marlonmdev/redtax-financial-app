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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('document_name');
            $table->text('document_path');
            $table->string('document_size', 20);
            $table->string('document_extension', 5);
            $table->string('uploaded_by');
            $table->timestamp('upload_date')->useCurrent();
            $table->boolean('viewed')->default(0);
            $table->boolean('downloaded')->default(0);
            $table->string('access_level', 50)->nullable();
            $table->unsignedBigInteger('uploaded_by_agent_id')->nullable();
            $table->foreign('uploaded_by_agent_id')->references('id')->on('agents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
