<?php

namespace Database\Seeders;

use App\Models\Login;
use App\Models\User;
use App\Models\Profession;
use App\Models\Skill;
use App\Models\Team;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * 2-24 - Creación y asociación de tablas y modelos
   */
  protected $professions;
  protected $skills;
  protected $teams;

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $this->fetchRelations();

    $this->createAdmin();

    $this->createRandomUser([
      'api_token' => 'user-api-token',
    ]);

    /**
     * Crear 999 Usuarios, asociar un Equipo y Habilidades de
     * forma aleatoria y crear el Perfil asociado a dicho Usuario
    */
    foreach (range(1, 99) as $i) {
      $this->createRandomUser();
    }
  }

  protected function fetchRelations()
  {
    $this->professions = Profession::all();
    $this->skills = Skill::all();
    $this->teams = Team::all();
  }

  protected function createAdmin()
  {
    $admin = User::factory()->create([
      'name' => 'Super Admin',
      'email' => 'superadmin@admin.net',
      'password' => bcrypt('superadmin'),
      'role' => 'admin',
      'created_at' => now(), //->addDay(),    // 1 día mas
      'team_id' => $this->teams->firstWhere('name', 'Styde'),
      'active' => true,
      'api_token' => 'admin-api-token',
    ]);

    $admin->skills()->attach($this->skills);

    $admin->profile->update([
      'bio' => 'Programador, editor',
      'twitter' => 'https://twitter.com/superadmin',
      'profession_id' => $this->professions->firstWhere('title', 'Desarrollador back-end')->id,
    ]);
  }

  /**
   * 2-24 - Creación y asociación de tablas y modelos
   * Crear Usuarios, asociar un Equipo y Habilidades de
   * forma aleatoria y crear el Perfil asociado a dicho Usuario
   */
  protected function createRandomUser(array $customAttributes = [])
  {
    $user = User::factory()->create(array_merge([
      'team_id' => rand(0, 2) ? null : $this->teams->random()->id,
      'active' => rand(0, 3) ? true : false,
      'created_at' => now()->subDays(rand(1, 90)),
    ], $customAttributes));

    $user->skills()->attach($this->skills->random(rand(0, 7)));

    // 2-40 Actualizar el perfil del usuario ya existente
    $user->profile->update([
      'profession_id' => rand(0, 2) ? $this->professions->random()->id : null,
    ]);

    Login::factory()->times(rand(1, 10))->create([
      'user_id' => $user->id,
    ]);
  }
}
