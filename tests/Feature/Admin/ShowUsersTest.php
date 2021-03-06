<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowUsersTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_displays_the_users_details()
  {
    $user = User::factory()->create([
      'name' => 'Super Admin',
    ]);

    $this->get('/usuarios/' . $user->id) // usuarios/5
        ->assertStatus(200)
        ->assertSee('Super Admin');
  }

  /** @test */
  public function it_displays_a_404_error_if_the_user_is_not_found()
  {
    $this->withExceptionHandling();

    $this->get('/usuarios/999')
        ->assertStatus(404)
        ->assertSee('Página no encontrada');
  }
}
