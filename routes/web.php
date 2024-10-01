<?php

use App\Livewire\Category;
use App\Livewire\Dashboard;
use App\Livewire\Login;
use App\Livewire\Logout;
use App\Livewire\Post;
use App\Livewire\Register;
use App\Livewire\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->to('/login');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', Register::class)->name('register');
    Route::get('/login', Login::class)->name('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/posts', Post::class)->name('posts');
    Route::get('/categories', Category::class)->name('categories');
    Route::get('/products', Product::class)->name('products');
    Route::get('/logout', Logout::class)->name('logout');
});
