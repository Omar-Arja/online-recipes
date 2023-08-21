<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecipeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout',[AuthController::class, 'logout']);
});

Route::prefix('/recipes')->group(function () {
    Route::get('/{search?}', [RecipeController::class, 'getRecipes']);
    Route::get('/{id}', [RecipeController::class, 'getRecipe']);
    Route::post('/', [RecipeController::class, 'createRecipe']);
    Route::delete('/{id}', [RecipeController::class, 'deleteRecipe']);

    Route::post('/{id}/comment', [RecipeController::class, 'addComment']);
    Route::post('/{id}/like', [RecipeController::class, 'likeRecipe']);

    Route::post('/shopping-list/{id}', [RecipeController::class, 'addRecipeToShoppingList']);
    Route::delete('/shopping-list/{id}', [RecipeController::class, 'removeRecipeFromShoppingList']);
});