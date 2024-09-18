<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [TodoController::class, 'index']);
Route::get('add-task', [TodoController::class, 'AddTask'])->name('add.task');
Route::get('get-todo', [TodoController::class, 'GetTodo'])->name('get.todo');
Route::get('delete', [TodoController::class, 'DeleteData'])->name('delete');
Route::get('update-task', [TodoController::class, 'UpdateTask'])->name('update.task');
Route::get('show-all-task', [TodoController::class, 'ShowAllTask'])->name('show.alltask');






