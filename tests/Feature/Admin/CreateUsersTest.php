<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Profession;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateUsersTest extends TestCase
{
  use RefreshDatabase;

  protected $defaultData = [
    'name' => 'Super Admin',
    'email' => 'superadmin@admin.net',
    'password' => 'superadmin',
    'bio' => 'Programador de Laravel y Vue.js',
    'twitter' => 'https://twitter.com/superadmin',
    'profession_id' => '',
    'role' => 'user',
    'state' => 'active',    // 2-34 Usar campos y atributos diferentes
  ];

  /** @test */
  public function it_loads_the_new_users_page()
  {
    $profession = Profession::factory()->create();

    $skillA = Skill::factory()->create();
    $skillB = Skill::factory()->create();

    $this->get('/usuarios/nuevo')
        ->assertStatus(200)
        ->assertSee('Crear usuario');
  }

  /** @test */
  public function it_creates_a_new_user()
  {
    $profession = Profession::factory()->create();

    $skillA = Skill::factory()->create();
    $skillB = Skill::factory()->create();
    $skillC = Skill::factory()->create();

    $this->post('/usuarios/', $this->withData([
      'skills' => [$skillA->id, $skillB->id],
      'profession_id' => $profession->id,
    ]))->assertRedirect('usuarios');

    $this->assertCredentials([
      'name' => 'Super Admin',
      'email' => 'superadmin@admin.net',
      'password' => 'superadmin',
      'role' => 'user',
      'active' => true,    // 2-34 Usar campos y atributos diferentes
    ]);

    $user = User::findByEmail('superadmin@admin.net');

    $this->assertDatabaseHas('user_profiles', [
      'bio' => 'Programador de Laravel y Vue.js',
      'twitter' => 'https://twitter.com/superadmin',
      'user_id' => $user->id,
      'profession_id' => $profession->id,
    ]);

    $this->assertDatabaseHas('user_skill', [
      'user_id' => $user->id,
      'skill_id' => $skillA->id,
    ]);

    $this->assertDatabaseHas('user_skill', [
      'user_id' => $user->id,
      'skill_id' => $skillB->id,
    ]);

    $this->assertDatabaseMissing('user_skill', [
      'user_id' => $user->id,
      'skill_id' => $skillC->id,
    ]);
  }

  /** @test */
  public function the_twitter_field_is_optional()
  {
    $this->post('/usuarios/', $this->withData([
      'twitter' => null,
    ]))->assertRedirect('usuarios');

    $this->assertCredentials([
      'name' => 'Super Admin',
      'email' => 'superadmin@admin.net',
      'password' => 'superadmin',
    ]);

    $this->assertDatabaseHas('user_profiles', [
      'bio' => 'Programador de Laravel y Vue.js',
      'twitter' => null,
      'user_id' => User::findByEmail('superadmin@admin.net')->id,
    ]);
  }

  /** @test */
  public function the_role_field_is_optional()
  {
    $this->post('/usuarios/', $this->withData([
      'role' => null,
    ]))->assertRedirect('usuarios');

    $this->assertDatabaseHas('users', [
      'email' => 'superadmin@admin.net',
      'role' => 'user',
    ]);
  }

  /** @test */
  public function the_role_must_be_valid()
  {
    $this->handleValidationExceptions();

    $this->post('/usuarios/', $this->withData([
      'role' => 'invalid-role',
    ]))->assertSessionHasErrors('role');

    $this->assertDatabaseEmpty('users');
  }

  /** @test */
  public function the_profession_id_field_is_optional()
  {
    $this->post('/usuarios/', $this->withData([
      'profession_id' => null,
    ]))->assertRedirect('usuarios');

    $this->assertCredentials([
      'name' => 'Super Admin',
      'email' => 'superadmin@admin.net',
      'password' => 'superadmin',
    ]);

    $this->assertDatabaseHas('user_profiles', [
      'bio' => 'Programador de Laravel y Vue.js',
      'profession_id' => null,
      'user_id' => User::findByEmail('superadmin@admin.net')->id,
    ]);
  }

  /** @test */
  public function the_user_is_redirected_to_the_previous_page_when_the_validation_fails()
  {
    $this->handleValidationExceptions();

    $this->post('/usuarios/', []);

    $this->assertDatabaseEmpty('users');
  }

  /** @test */
  public function the_name_is_required()
  {
    $this->handleValidationExceptions();

    $this->post('/usuarios/', $this->withData([
      'name' => '',
    ]))
        ->assertSessionHasErrors(['name']);

    //  Comprobar que el usuario no se creo
    $this->assertDatabaseEmpty('users');
    /* $this->assertDatabaseMissing('users', [
      'email' => 'superadmin@admin.net',
    ]); */
  }

  /** @test */
  public function the_email_is_required()
  {
    $this->handleValidationExceptions();

    $this->post('/usuarios/', $this->withData([
      'email' => '',
    ]))
        ->assertSessionHasErrors(['email']);

    //  Comprobar que el usuario no se creo
    $this->assertDatabaseEmpty('users');
  }

  /** @test */
  public function the_email_must_be_valid()
  {
    $this->handleValidationExceptions();

    $this->post('/usuarios/', $this->withData([
      'email' => 'correo-no-valido',
    ]))
        ->assertSessionHasErrors(['email']);

    $this->assertDatabaseEmpty('users');
  }

  /** @test */
  public function the_email_must_be_unique()
  {
    $this->handleValidationExceptions();

    User::factory()->create([
      'email' => 'superadmin@admin.net'
    ]);

    $this->post('/usuarios/', $this->withData([
      'email' => 'superadmin@admin.net',
    ]))
        ->assertSessionHasErrors(['email']);

    $this->assertEquals(1, User::count());
  }

  /** @test */
  public function the_password_is_required()
  {
    $this->handleValidationExceptions();

    $this->post('/usuarios/', $this->withData([
      'password' => null,
    ]))
        ->assertSessionHasErrors(['password']);

    //  Comprobar que el usuario no se creo
    $this->assertDatabaseEmpty('users');
  }

  /** @test */
  public function the_profession_must_be_valid()
  {
    $this->handleValidationExceptions();

    $this->post('/usuarios/', $this->withData([
      'profession_id' => '999'
    ]))
        ->assertSessionHasErrors(['profession_id']);

    $this->assertDatabaseEmpty('users');
  }

  /** @test */
  public function only_not_deleted_professions_can_be_selected()
  {
    $this->withExceptionHandling();

    $deletedProfession = Profession::factory()->create([
      'deleted_at' => now()->format('Y-m-d'),
    ]);

    $this->handleValidationExceptions();

    $this->post('/usuarios/', $this->withData([
      'profession_id' => $deletedProfession->id,
    ]))
        ->assertSessionHasErrors(['profession_id']);

    $this->assertDatabaseEmpty('users');
  }

  /** @test */
  public function the_skills_must_be_an_array()
  {
    $this->handleValidationExceptions();

    $this->post('/usuarios/', $this->withData([
      'skills' => 'PHP, JS'
    ]))
        ->assertSessionHasErrors(['skills']);

    $this->assertDatabaseEmpty('users');
  }

  /** @test */
  public function the_skills_must_be_valid()
  {
    $this->handleValidationExceptions();

    $skillA = Skill::factory()->create();
    $skillB = Skill::factory()->create();

    $this->post('/usuarios/', $this->withData([
      'skills' => [$skillA->id, $skillB->id + 1],
    ]))
        ->assertSessionHasErrors(['skills']);

    $this->assertDatabaseEmpty('users');
  }

  /** @test */
  public function the_state_is_required()
  {
    $this->handleValidationExceptions();

    $this->post('/usuarios/', $this->withData([
      'state' => null,
    ]))->assertSessionHasErrors('state');

    $this->assertDatabaseEmpty('users');
  }

  /** @test */
  public function the_state_must_be_valid()
  {
    $this->handleValidationExceptions();

    $this->post('/usuarios/', $this->withData([
      'state' => 'invalid-state',
    ]))->assertSessionHasErrors('state');

    $this->assertDatabaseEmpty('users');
  }
}
