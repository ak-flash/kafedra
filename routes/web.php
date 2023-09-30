<?php

use App\Http\Controllers\ReClassController;
use App\Livewire\Reclasses;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reclass/new', Reclasses::class);

Route::get('/dashboard/no-department', function () {
    return view('errors.no-department');
})
    ->middleware(['auth:web'])
    ->name('no-department');


Route::prefix('/dashboard/')
    ->middleware(['auth:web','verified'])
    ->group(function () {

        Route::get('/variant/{variant}/print/{fontSize}', [\App\Http\Controllers\GetVariantPdf::class, 'generate'])
            ->name('print.variant');

    });

