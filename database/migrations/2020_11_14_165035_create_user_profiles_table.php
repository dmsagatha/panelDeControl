<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
  public function up()
  {
    Schema::create('user_profiles', function (Blueprint $table) {
      $table->id();

      // Al eliminar un Usuario, eliminar el Perfil
      $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
      $table->foreignId('profession_id')
          ->nullable()
          ->constrained()
          ->onUpdate('restrict')
          ->onDelete('set null');

      $table->string('bio', 1000);
      $table->string('twitter')->nullable();
      $table->softDeletes();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('user_profiles');
  }
}
