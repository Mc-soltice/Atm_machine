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

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_number');
            $table->decimal('amount', 10, 2);
            $table->enum('type', \App\Enums\TransactionType::getValues()); // Utilisation de la méthode statique
            $table->timestamps();
            $table->foreign('account_number')->references('account_number')->on('bank_accounts');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('transactions');
    }
};
