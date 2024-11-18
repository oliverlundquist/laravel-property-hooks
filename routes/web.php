<?php

use App\Models\Data\ProductsData;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
