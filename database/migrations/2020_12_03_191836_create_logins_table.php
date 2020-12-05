<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginsTable extends Migration
{
  public function up()
  {
    Schema::create('logins', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('logins');
  }
}
