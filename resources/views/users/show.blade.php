@extends('layout')

@section('title', "Usuario {$user->name}")

@section('content')
  <h1 class="mt-3">Usuario #{{ $user->id }}</h1>
  
  <p>Nombre del usuario: {{ $user->name }}</p>
  <p>Correo electrónico: {{ $user->email }}</p>
@endsection