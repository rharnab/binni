<?php

use App\Http\Controllers\ParameterSetup\AccountSetupController;
use App\Http\Controllers\ParameterSetup\EmployeeController;
use App\Http\Controllers\ParameterSetup\InventoryController;
use App\Http\Controllers\ParameterSetup\PredefinedGlController;
use App\Http\Controllers\ParameterSetup\UserController;
use App\Http\Controllers\Transaction\ValultTransactionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




/********************************
 *  Parameter Section Start Here
******************************* */
Route::group(['prefix' => 'parameter-setup', 'namespace' => 'ParameterSetup', 'as'=>'parameter_setup.'], function(){

    /******************
     *  User Route Here
    **************** */
    Route::group(['prefix'=>'user-setup', 'as'=> 'user_setup.'], function(){
        Route::get('user-list/index', [UserController::class, 'index'])->name('user_list.index');
        Route::get('user-list/create', [UserController::class, 'create'])->name('user_list.create');
        Route::post('user-list/store', [UserController::class, 'store'])->name('user_list.store');
        Route::get('user-list/{id}/edit', [UserController::class, 'edit'])->name('user_list.edit');
        Route::post('user-list/{id}/update', [UserController::class, 'update'])->name('user_list.update');
        Route::get('user-list/pending', [UserController::class, 'pending'])->name('user_list.pending');
        Route::post('user-lsit/authorize', [UserController::class, 'authorizeUser'])->name('user_list.authorize');
        Route::post('user-lsit/reject', [UserController::class, 'rejectUser'])->name('user_list.reject');
    });


    /******************
     *  Employee Route  
    **************** */
    Route::group(['prefix'=>'employee-setup', 'as' => 'employee_setup.'], function(){
        Route::get('employee-list/index', [EmployeeController::class, 'index'])->name('employee_list.index');
        Route::get('employee-list/create', [EmployeeController::class, 'create'])->name('employee_list.create');
        Route::post('employee-list/employee-info', [EmployeeController::class, 'employeeInfo'])->name('employee_list.employee_info');
        Route::post('employee-list/store', [EmployeeController::class, 'store'])->name('employee_list.store');
        Route::get('employee-list/{id}/edit', [EmployeeController::class, 'edit'])->name('employee_list.edit');
        Route::get('employee-list/pending', [EmployeeController::class, 'pending'])->name('employee_list.pending');
        Route::post('employee-list/authorize', [EmployeeController::class, 'authorizeEmployee'])->name('employee_list.authorize');
        Route::post('employee-list/reject', [EmployeeController::class, 'rejectEmployee'])->name('employee_list.reject');
    });


    /******************
     *  Account Setup 
    **************** */
    Route::group(['prefix' => 'account-setup', 'as' => 'account_setup.'], function(){
        Route::get('/index', [AccountSetupController::class, 'index'])->name('index');
        Route::get('/create', [AccountSetupController::class, 'create'])->name('create');
        Route::post('/search-parent-account', [AccountSetupController::class, 'searchParentAccount'])->name('search_parent_account');
        Route::post('/store', [AccountSetupController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AccountSetupController::class, 'edit'])->name('edit');
        Route::post('/{id}/update', [AccountSetupController::class, 'update'])->name('update');
        Route::get('/pending', [AccountSetupController::class, 'pending'])->name('pending');
        Route::post('/authorize', [AccountSetupController::class, 'authorizeAccount'])->name('authorize');
        Route::post('/reject', [AccountSetupController::class, 'rejectAccount'])->name('reject');
    });


    /******************
     *  Predefined Gl 
    **************** */
    Route::group(['prefix' => 'predefined-gl', 'as' => 'predefined_gl.'], function(){
        Route::get('/index', [PredefinedGlController::class, 'index'])->name('index');
        Route::get('/create', [PredefinedGlController::class, 'create'])->name('create');
        Route::post('/store', [PredefinedGlController::class, 'store'])->name('store');
        Route::get('/pending', [PredefinedGlController::class, 'pending'])->name('pending');
        Route::post('/authorize', [PredefinedGlController::class, 'authorizeGlMapping'])->name('authorize');
        Route::post('/reject', [PredefinedGlController::class, 'rejectGlMapping'])->name('reject');
    });


    /******************
     *  Predefined Gl 
    **************** */
    Route::group(['prefix' => 'inventory', 'as' => 'inventory.'], function(){
        Route::get('/index', [InventoryController::class, 'index'])->name('index');
        Route::get('/create', [InventoryController::class, 'create'])->name('create');
        Route::post('/store', [InventoryController::class, 'store'])->name('store');
        Route::get('/pending', [InventoryController::class, 'pending'])->name('pending');
        Route::post('/authorize', [InventoryController::class, 'authorizeInventory'])->name('authorize');
        Route::post('/reject', [InventoryController::class, 'rejectInventory'])->name('reject');
    });

});
/********************************
 *  Parameter Section End Here
******************************* */



/********************************
 *  Transaction Section Start Here
******************************* */
Route::group(['prefix' => 'transaction', 'namespace' => 'Transaction', 'as' => 'transaction.'], function(){

    /******************
     *  Vault Transaction 
    **************** */
    Route::group(['prefix' => 'vault', 'as' => 'vault.'], function(){
        Route::get('/index', [ValultTransactionController::class, 'index'])->name('index');
        Route::get('/create', [ValultTransactionController::class, 'create'])->name('create');
        Route::post('/store', [ValultTransactionController::class, 'store'])->name('store');
        Route::get('/pending', [ValultTransactionController::class, 'pending'])->name('pending');
        Route::post('/authorize', [ValultTransactionController::class, 'authorizeTranaction'])->name('authorize');
        Route::post('/reject', [ValultTransactionController::class, 'rejectTransaction'])->name('reject');
    });


});