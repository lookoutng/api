<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReportController;

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

        Route::get('/questions', [QuestionController::class, 'index']);
        Route::get('/questions/edits/{id}', [QuestionController::class, 'index']);
        Route::get('/question/id/{id}', [QuestionController::class, 'show']);
        Route::get('/question/user/default', [QuestionController::class, 'myQuestion']);

        Route::post('/user/logout', [UserController::class, 'logout']);
        Route::get('/user', [UserController::class, 'show']);
        Route::post('/user/update', [UserController::class, 'update']);

        Route::put('/answer/update/{id}', [AnswerController::class, 'update']);
        Route::post('/answer/create/question/{question_id}', [AnswerController::class, 'store']);
        Route::delete('/answer/delete/{id}', [AnswerController::class, 'delete']);
        Route::put('/answer/report/{id}', [ReportController::class, 'store']);

        Route::post('/location/create', [LocationController::class, 'store']);
        
        Route::post('/option/create/question/{question_id}', [OptionController::class, 'store']);
        Route::delete('/option/delete/{id}', [OptionController::class, 'delete']);
        Route::put('/option/update/{id}', [OptionController::class, 'update']);


    }
);
