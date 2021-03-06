@csrf

<div class="form-row">
  <div class="form-group col-4">
    <label for="name">Nombre Completo:</label>
    <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
    @if ($errors->has('name'))
      <div class="text-danger">{{ $errors->first('name') }}</div>
    @endif
  </div>
  <div class="form-group col">
    <label for="email">Correo electrónico:</label>
    <input class="form-control" type="email" name="email" id="email" value="{{ old('email', $user->email) }}">
    {!! $errors->first('email', '<div class="text-danger">:message</div>') !!}
  </div>
</div>

<div class="form-row">
  <div class="form-group col-3">
    <label for="password">Contraseña:</label>
    <input class="form-control" type="password" name="password" id="password" placeholder="Mayor a 6 caracteres">
    {!! $errors->first('password', '<div class="text-danger">:message</div>') !!}
  </div>
  <div class="form-group col-4">
    <label for="profession_id">Profesión:</label>
    <select name="profession_id" id="profession_id" class="form-control">
      <option value="">Seleccionar</option>
      {{-- @foreach (App\Models\Profession::orderBy('title')->get() as $profession) --}}
      @foreach($professions as $profession)
        <option value="{{ $profession->id }}"{{ old('profession_id', $user->profile->profession_id) == $profession->id ? ' selected' : '' }}>
          {{ $profession->title }}
        </option>
      @endforeach
    </select>
    {!! $errors->first('profession_', '<div class="text-danger">:message</div>') !!}
  </div>
  <div class="form-group col">
    <label for="twitter">Twitter:</label>
    <input class="form-control" type="text" name="twitter" id="twitter" 
        value="{{ old('twitter', $user->profile->twitter) }}" 
        placeholder="https://twitter.com/Stydenet">
    {!! $errors->first('twitter', '<div class="text-danger">:message</div>') !!}
  </div>
</div>

<div class="form-row">
  <div class="form-group col">
    <label for="bio">Biografía:</label>
    <textarea class="form-control" type="text" name="bio" id="bio">{{ old('bio', $user->profile->bio) }}</textarea>
    {!! $errors->first('bio', '<div class="text-danger">:message</div>') !!}
  </div>
</div>

<h5>Habilidades</h5>

@foreach ($skills as $skill)
  <div class="form-check form-check-inline">
    <input name="skills[{{ $skill->id }}]"
        id="skill_{{ $skill->id }}"
        class="form-check-input"
        type="checkbox"
        value="{{ $skill->id }}"
        {{ ($errors->any() ? old("skills.{$skill->id}") : $user->skills->contains($skill)) ? 'checked' : '' }}>
    <label class="form-check-label" for="skill_{{ $skill->id }}">{{ $skill->name }}</label>
  </div>
@endforeach
{!! $errors->first('skills', '<div class="text-danger">:message</div>') !!}

<h5 class="mt-3">Rol</h5>

@foreach (trans('users.roles') as $role => $name)
  <div class="form-check form-check-inline">
    <input class="form-check-input"
        type="radio"
        name="role"
        id="role_{{ $role }}"
        value="{{ $role }}"
        {{ old('role', $user->role) == $role ? 'checked' : '' }}>
    <label class="form-check-label" for="role_{{ $role }}">{{ $name }}</label>
  </div>
@endforeach

<h5 class="mt-3">Estado</h5>

@foreach(trans('users.states') as $state => $label)
  <div class="form-check form-check-inline">
    <input class="form-check-input"
        type="radio"
        name="state"
        id="state_{{ $state }}"
        value="{{ $state }}"
        {{ old('state', $user->state) == $state ? 'checked' : '' }}>
    <label class="form-check-label" for="state_{{ $state }}">{{ $label }}</label>
  </div>
@endforeach