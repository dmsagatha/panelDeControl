<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Profession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersProfileTest extends TestCase
{
  use RefreshDatabase;

  protected $defaultData = [
    'name' => 'Super Admin',
    'email' => 'superadmin@admin.net',
    // 'password' => 'superadmin',
    'bio' => 'Programador de Laravel y Vue.js',
    'twitter' => 'https://twitter.com/superadmin',
  ];

  /** @test */
  public function a_user_can_edit_its_profile()
  {
    $user = User::factory()->create();

    $newProfession = Profession::factory()->create();

    //$this->actingAs($user);

    $response = $this->get('/editar-perfil/');

    $response->assertStatus(200);

    $response = $this->put('/editar-perfil/', [
      'name' => 'Super Admin',
      'email' => 'superadmin@admin.net',
      'bio' => 'Programador de Laravel y Vue.js',
      'twitter' => 'https://twitter.com/superadmin',
      'profession_id' => $newProfession->id,
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('users', [
      'name' => 'Super Admin',
      'email' => 'superadmin@admin.net',
    ]);

    $this->assertDatabaseHas('user_profiles', [
      'bio' => 'Programador de Laravel y Vue.js',
      'twitter' => 'https://twitter.com/superadmin',
      'profession_id' => $newProfession->id,
    ]);
  }

  /** @test */
  public function the_user_cannot_change_its_role()
  {
    $user = User::factory()->create([
      'role' => 'user'
    ]);

    $response = $this->put('/editar-perfil/', $this->withData([
      'role' => 'admin',
    ]));

    $response->assertRedirect();

    $this->assertDatabaseHas('users', [
      'id' => $user->id,
      'role' => 'user',
    ]);
  }

  /** @test */
  public function the_user_cannot_change_its_password()
  {
    User::factory()->create([
      'password' => bcrypt('old123'),
    ]);

    $response = $this->put('/editar-perfil/', $this->withData([
      'email' => 'superadmin@admin.net',
      'password' => 'new456'
    ]));

    $response->assertRedirect();

    $this->assertCredentials([
      'email' => 'superadmin@admin.net',
      'password' => 'old123',
    ]);
  }
}
