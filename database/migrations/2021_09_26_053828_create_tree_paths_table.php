<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreePathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tree_paths', function (Blueprint $table) {
            $table->foreignId('ancestor_id');
            $table->foreignId('descendant_id');
            $table->string('entity_type');
            $table->timestamps();
            $table->softDeletes();
            
            $table->primary(['ancestor_id', 'descendant_id', 'entity_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tree_paths');
    }
}
