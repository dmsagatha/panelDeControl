<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AppLayout extends Component
{
  public function __construct()
  {
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|string
   */
  public function render()
  {
    return view('layouts.app');
  }
}
