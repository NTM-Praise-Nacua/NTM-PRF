<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PurchaseRequisitionFormController;
use App\Http\Controllers\RequestTypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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

// Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/', function() {
    return redirect('home');
})->name('home');

Auth::routes();
Route::group(['middleware' => 'auth'], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    Route::get('/approval-setup', [PurchaseRequisitionFormController::class, 'index'])->name('approval.setup');
    Route::post('/approval-setup/uploadPDF', [PurchaseRequisitionFormController::class, 'uploadPdf'])->name('approval.upload.pdf');
    Route::post('/approval-setup/add', [PurchaseRequisitionFormController::class, 'store'])->name('prf.add.ordering');
    
    Route::post('/prf-flow/getDataFlow', [PurchaseRequisitionFormController::class, 'getTypeFlow'])->name('type.flow');

    Route::get('/requisition/form', [PurchaseRequisitionFormController::class, 'showForm'])->name('requisition.form');
    Route::post('/requisition/form', [PurchaseRequisitionFormController::class, 'savePRF'])->name('requisition.form.add');
    Route::post('/requisition/form-details', [PurchaseRequisitionFormController::class, 'otherPRFDetails'])->name('requisition.other.details');
    Route::get('/requisition/history', [PurchaseRequisitionFormController::class, 'showHistory'])->name('requisition.history');
    
    Route::get('/users', [UserController::class, 'index'])->name('user.list');
    Route::get('/users/data', [UserController::class, 'getUsersData'])->name('users.data');
    Route::post('/user/detail', [UserController::class, 'getUserInfo'])->name('user.details.get');
    Route::post('/user/add', [UserController::class, 'store'])->name('user.add');
    // Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    // Route::post('/user/view', [UserController::class, 'update'])->name('user.update');
    Route::post('/user/update', [UserController::class, 'update'])->name('user.update');
    
    Route::get('/department', [DepartmentController::class, 'index'])->name('department.list');
    Route::get('/department/data', [DepartmentController::class, 'getDepartmentsData'])->name('department.data');
    Route::post('/department/add', [DepartmentController::class, 'store'])->name('department.add');

    Route::get('/position', [PositionController::class, 'index'])->name('position.list');
    Route::get('/position/data', [PositionController::class, 'getPositionData'])->name('position.data');
    Route::post('/position/add', [PositionController::class, 'store'])->name('position.add');

    Route::post('/request/add', [RequestTypeController::class, 'store'])->name('add.request.type');
});