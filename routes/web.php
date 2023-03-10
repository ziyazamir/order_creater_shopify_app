<?php

use App\Http\Controllers\OrdersCreatesController;
use App\Models\Orders_Creates;
use Illuminate\Support\Facades\Route;

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

Route::get("all_orders", [OrdersCreatesController::class, "all_orders"])->name("all_orders");

Route::get('/', function () {
    return view('welcome');
    $shop = $_GET['shop'];
    echo $shop;
})->middleware(['verify.shopify'])->name('home');

Route::get('/login', function () {
    return view("login");
})->name("login");

Route::post("readcsv", [OrdersCreatesController::class, "readcsv"])->middleware(['verify.shopify'])->name("readcsv");
Route::get("test", function () {
    return view("test");
})->name("test");
Route::get("testing", function () {
    return back()->with("order", "message");
})->name("testing");
