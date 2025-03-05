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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_id')->constrained('finances')->onDelete('cascade');
            $table->double('amount');
            $table->nullableMorphs('related'); // Polymorphic relationship (related_id & related_type)
            $table->double('previous_net_worth')->default(0.0);
            $table->double('current_net_worth');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
