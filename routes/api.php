<?php

use App\Http\Controllers\BooksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('create_book', [BooksController::class,'createBook']);
Route::post('update_book', [BooksController::class,'updateBook']);
Route::post('delete_book', [BooksController::class, 'deleteBook']);
Route::get('get_all_books', [BooksController::class, 'getAllBooks']);

Route::get('testing', function(){
    $isStaging = env('IS_STAGING');
    return $isStaging ;
});
