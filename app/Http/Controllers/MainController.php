<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MainController extends Controller
{
    private $app_data;

    public function __construct()
    {
        //carrega o arquivo app_data.php da pasta app
        $this->app_data =  require(app_path('app_data.php'));
    }
}
