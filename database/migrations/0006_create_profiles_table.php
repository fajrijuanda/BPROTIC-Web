<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Tambahkan foreign key constraint
            $table->string('first_name');
            $table->string('last_name');
            $table->text('bio');
            $table->string('avatar');
            $table->string('nim')->unique();
            $table->string('class');
            $table->string('mobile');
            $table->foreignId('major_id')->constrained('majors')->onDelete('cascade'); // Pastikan major juga punya constraint
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
