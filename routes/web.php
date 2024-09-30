<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentLikeController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminAuthentication;
use App\Http\Middleware\ValidUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelController;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

// Public Routes
Route::get('/', function () {
    return view('Auth.login');
})->name('home');

Route::view('register', 'auth.register')->name('register');
Route::view('/adminlogin', 'Admin.Adminlogin')->name('admin.login');
Route::view('login', 'auth.login')->name('login');

// Google Auth Routes
Route::controller(GoogleAuthController::class)->group(function () {
    Route::get('auth/google', 'redirect')->name('authgoogle');
    Route::get('auth/google/call-back', 'callback');
});

// Auth Routes
Route::controller(AuthController::class)->group(function () {
    Route::post('loginmatch', 'loginmatch')->name('loginmatch');
    Route::get('checklogin', 'checklogin')->name('checklogin');
    Route::get('logout', 'logout')->name('logout');
});

Route::middleware([ValidUser::class, AdminAuthentication::class])->group(function () {

    // User Routes
    Route::resource('/user', UserController::class);
    Route::view('users/profile', 'Users.Profile')->name('user.profile');
    Route::view('masterpage', 'masterpage')->name('masterpage');

    // Post Routes
    Route::view('createposts', 'Posts.CreatePosts')->name('createposts');
    Route::post('/posts/{post}/like', [LikeController::class, 'toggleLike'])->name('posts.like');
    Route::post('/comments/{comment}/like', [CommentLikeController::class, 'toggleLike'])->name('comments.like');

    Route::controller(CommentController::class)->group(function () {
        Route::post('/posts/{post}/comments', 'store')->name('comments.store');
        Route::delete('posts/{post}/comments/{comment}', 'destroy')->name('comments.destroy');
        Route::delete('/posts/{post}/comments/{comment}/reply/{reply}', 'destroyReply')
            ->name('comments.destroyReply');
    });

    Route::resource('/newposts', PostController::class);

    Route::controller(PostController::class)->group(function () {
        Route::get('/posts/tag/{tagId}', 'filterposts')->name('posts.byTag');
        Route::get('/posts/filter-by-tag', 'filterByTag')->name('posts.filterByTag');
        Route::get('/homepictures', 'homepage')->name('homepictures');
    });

});

// Admin Routes
Route::view('/dashboard', 'Admin.Dashboard')->name('dashboard');

Route::resource('/admin', AdminController::class);
Route::get('/getusers', [AdminController::class, 'getusers'])->name('getusers');

Route::controller(AdminAuthController::class)->group(function () {
    Route::post('adminloginmatch', 'adminloginmatch')->name('adminloginmatch');
    Route::get('adminchecklogin', 'adminchecklogin')->name('adminchecklogin');
    Route::get('adminlogout', 'adminlogout')->name('adminlogout');
});

Route::post('/users/import', [ExcelController::class, 'import'])->name('users.import');

Route::get('export-users', function () {
    return Excel::download(new UsersExport, 'users.xlsx');
})->name('export.users');
