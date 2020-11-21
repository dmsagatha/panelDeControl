<?php

namespace App\Http\Requests;

use App\Models\{User, Role};
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }
  
  public function rules()
  {
    return [
      'first_name' => 'required',
      'last_name'  => 'required',
      // 'email'    => 'required|email|unique:users,email,'.$user->id,
      'email'    => [
        'required', 'email', 
        Rule::unique('users')->ignore($this->user)
      ],
      'password' => '',
      'role' => [Rule::in(Role::getList())],   //Role.php
      'bio'      => 'required',
      'twitter'  => ['nullable', 'present', 'url'],
      'profession_id' => [
        'nullable', 'present',
        Rule::exists('professions', 'id')->whereNull('deleted_at')
      ],
      'skills'   => [
        'array',
        Rule::exists('skills', 'id'),
      ],
    ];
  }

  public function updateUser(User $user)
  {
    // $user->fill([
    $user->forceFill([
        'first_name' => $this->first_name,
        'last_name'  => $this->last_name,
        'email' => $this->email,
        'role'  => $this->role,
    ]);

    if ($this->password != null) {
        $user->password = bcrypt($this->password);
    }

    $user->save();

    $user->profile->update([
        'twitter' => $this->twitter,
        'bio' => $this->bio,
        'profession_id' => $this->profession_id,
    ]);

    $user->skills()->sync($this->skills ?: []);
  }
}