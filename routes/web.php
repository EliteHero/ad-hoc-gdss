<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home')->name('home');
Route::livewire('/sessions/{code}/lobby', 'pages::session.lobby')->name('lobby');
Route::livewire('/sessions/{code}/weight', 'pages::session.weight')->name('weight');
Route::livewire('/sessions/{code}/rate', 'pages::session.rate')->name('rate');
Route::livewire('/sessions/{code}/results', 'pages::session.results')->name('results');