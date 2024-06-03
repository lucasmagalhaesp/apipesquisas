<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PerguntasController;
use App\Http\Controllers\PesquisasController;
use App\Http\Controllers\PesquisasRealizadasController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::resource("/usuarios", UsuariosController::class);
    Route::get("/usuarios/check", [UsuariosController::class, "check"]);
    Route::resource("/pesquisas", PesquisasController::class);
    Route::get("/pesquisas/getIDsPerguntas/{pesquisa}", [PesquisasController::class, "getIDsPerguntas"]);
    Route::resource("/perguntas", PerguntasController::class);
    Route::resource("/pesquisasRealizadas", PesquisasRealizadasController::class);
    Route::post("/auth/logout", [AuthController::class, "logout"]);
});

Route::post("/auth/login", [AuthController::class, "login"]);

