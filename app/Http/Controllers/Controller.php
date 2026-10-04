<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// REQUIREMENT 7: trait AuthorizesRequests dibutuhkan supaya $this->authorize()
// (dipakai di ProductController) bisa jalan -> Laravel 11+ tidak lagi
// menyertakan trait ini secara default di base Controller.
abstract class Controller
{
    use AuthorizesRequests;
}
