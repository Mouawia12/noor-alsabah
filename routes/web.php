<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

//use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\VehicleController;

Route::get('show_404', function () {
    return redirect('/404');
})->name('404');


Route::get('show_404', [HomeController::class, 'show_404'])->name('show_404');
Route::get('show_not_allow', [HomeController::class, 'show_not_allow'])->name('show_not_allow');
Route::get('show_enter_data', [HomeController::class, 'show_enter_data'])->name('show_enter_data');
Route::get('show_enter_data_other', [HomeController::class, 'show_enter_data'])->name('show_enter_data_other');
Route::get('edit_profile', [HomeController::class, 'edit_profile'])->name('edit_profile');
Route::post('updateProfile', [HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('load_alerts', [HomeController::class, 'load_alerts'])->name('load_alerts');

Route::post('notify_num', [HomeController::class, 'notify_num'])->name('notify_num');

Route::get('/', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

Route::post('/images_upload', [UploadController::class, 'images'])->name('upload.images');

Route::get('/', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/insert_vehicle',[VehicleController::class,"add"])->name("insert_vehicle");
    Route::post('/store_vehicle',[VehicleController::class,"store"])->name("store_vehicle");
    Route::get('/vehicles',[VehicleController::class,"index"])->name("vehicles.index");

    Route::get('/destroy_vehicle/{id}',[VehicleController::class,"destroy"])->name("vehicles.destroy");
    Route::get('/edit_vehicle/{id}',[VehicleController::class,"add"])->name("vehicles.edit");
    Route::post('/update_vehicle/{id}',[VehicleController::class,"update"])->name("update_vehicle");

    
});


Route::get('/clear', function() {

   Artisan::call('cache:clear');
   Artisan::call('config:clear');
   Artisan::call('config:cache');
   Artisan::call('view:clear');

   return "Cleared!";

});


Route::get('profile', function () {
    return view('users.profile');
})->name('profile');
/*Route::get('edit_profile', function () {
    return view('users.edit_profile');
})->name('edit_profile');*/

require __DIR__ . '/auth.php';
require __DIR__ . '/dashboard.php';
