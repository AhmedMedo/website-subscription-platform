<?php

use App\Http\Controllers\AddSubscriberHandler;
use App\Http\Controllers\CreatePostHandler;
use Illuminate\Support\Facades\Route;


Route::post('/post',CreatePostHandler::Class);
Route::post('/add-subscriber',AddSubscriberHandler::Class);
