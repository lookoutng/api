<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\QuestionController;

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

Route::get('/questions', [QuestionController::class, 'index']);
Route::post('/user/create', [UserController::class, 'store']);

Route::group(
    [
        'middleware' => ['auth:sanctum']
    ], 
    function()
    {
        Route::put('/question/update/{id}', [QuestionController::class, 'update']);
        Route::post('/question/create', [QuestionController::class, 'store']);
        Route::delete('/question/delete/{id}', [QuestionController::class, 'delete']);
        Route::get('/question/id/{id}', [QuestionController::class, 'show']);

        Route::post('/user/logout', [UserController::class, 'logout']);
        Route::get('/user', [UserController::class, 'show']);
        Route::put('/user/update', [UserController::class, 'update']);

        Route::put('/answer/update', [AnswerController::class, 'update']);
        Route::post('/answer/create', [AnswerController::class, 'store']);
        Route::delete('/answer/delete', [AnswerController::class, 'delete']);

        Route::put('/location/store', [AnswerController::class, 'update']);
        
        Route::post('/option/create', [AnswerController::class, 'store']);
        Route::delete('/option/delete', [AnswerController::class, 'delete']);
        Route::put('/option/update', [AnswerController::class, 'update']);


    }
);
