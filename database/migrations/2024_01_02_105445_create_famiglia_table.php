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
        Schema::create('famiglie', function (Blueprint $table) {
            $table->bigInteger("id");
            $table->string("cittadino_id");
            $table->string("ruolo");
            $table->string('responsabile')->default(false);
            
            $table->timestamps();
            // Per identificare la famiglia e un suo membro
            $table->primary(["id","cittadino_id"]); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('famiglie');
    }
};
