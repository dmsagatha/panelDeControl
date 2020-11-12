<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
  public function index()
  {
    request('field');

    if (request()->has('empty')) {
      $users = [];
    } else {
      $users = [
          'Joel', 'Ellie', 'Tess', 'Tommy', 'Bill',
      ];
    }

    $title = 'Listado de usuarios';

    // dd(compact('title', 'users'));

    return view('users', compact('title', 'users'));
  }

  public function show($id)
  {
    return "Mostrando detalle del usuario: {$id}";
  }

  public function create()
  {
    return 'Crear nuevo usuario';
  }
}