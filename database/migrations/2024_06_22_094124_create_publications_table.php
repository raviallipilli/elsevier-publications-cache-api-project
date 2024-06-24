<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create the publications table with columns for id, doi, data, and timestamps
        Schema::create('publications', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('doi')->unique(); // DOI column, unique constraint
            $table->json('data'); // Data column to store publication details in JSON format
            $table->timestamps(); // Timestamps columns for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the publications table if it exists
        Schema::dropIfExists('publications');
    }
}
