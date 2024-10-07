<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});


Route::get('/downloadApplication' , function (){
    $filePath = storage_path('app/public/house_of_geniuses.apk');

    if (!file_exists($filePath)) {
        abort(404, 'File not found.');
    }

    return response()->download($filePath, 'house_of_geniuses.apk', [
        'Content-Type' => 'application/vnd.android.package-archive',
    ]);
})->name("downloadApk");

Route::get('/download' , function (){
    return view('download_app.download');
});
