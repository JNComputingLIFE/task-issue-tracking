<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/projects');

Route::resource('projects', ProjectController::class);
Route::resource('issues', IssueController::class);
Route::resource('tags', TagController::class)->only(['index', 'store']);

// Additional Routes (so no reloading, ajax)
Route::get('/issues/{issue}/comments', [CommentController::class, 'index']);
Route::post('/issues/{issue}/comments', [CommentController::class, 'store']);
Route::post('/issues/{issue}/tags/{tag}', [IssueController::class, 'attachTag']);
Route::delete('/issues/{issue}/tags/{tag}', [IssueController::class, 'detachTag']);

// More routes for auth
Route::post('/issues/{issue}/toggle-user', [IssueUserController::class, 'toggle'])->name('issues.toggle-user');

// UserAttachments
Route::post('/issues/{issue}/users/{user}', [IssueController::class, 'attachUser'])->name('issues.attach-user');
Route::delete('/issues/{issue}/users/{user}', [IssueController::class, 'detachUser'])->name('issues.detach-user');