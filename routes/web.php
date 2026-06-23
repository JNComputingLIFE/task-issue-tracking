<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/projects');

Route::resource('projects', ProjectController::class);
Route::resource('issues', IssueController::class);
Route::resource('tags', TagController::only(['index', 'store']));

// Additional Routes (so no reloading, ajax)
Route::get('/issues/{issue}/comments', [CommentController::class, 'index']);
Route::post('/issues/{issue}/comments', [CommentController::class, 'store']);
Route::post('/issues/{issue}/tags/{tag}', [IssueController::class, 'attachTag']);
Route::delete('/issues/{issue}/tags/{tag}', [IssueController::class, 'detachTag']);