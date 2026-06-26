<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\ReportController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__.'/auth.php';



Route::middleware('auth')->group(function () {




    // Admin only
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::resource('users', UsersController::class);
        
        });
        
        



    // Team leader + admin
    Route::middleware(['auth', 'role:admin,teamleader'])->prefix('tl')->name('tl.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'teamleader'])->name('dashboard');
      
    });

    // All logged in users
    Route::middleware(['auth', 'role:telecaller'])->prefix('caller')->name('telecaller.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'telecaller'])->name('dashboard');

        Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
        Route::get('leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
        Route::get('leads/{lead}/edit', [LeadController::class, 'edit'])->name('leads.edit');
        Route::patch('leads/{lead}', [LeadController::class, 'update'])->name('leads.update');

    });


       // ----- Leads — all roles, one place -----

    //   Route::middleware('role:admin,teamleader')->group(function () {
    //     Route::get('leads/import',  [ExcelImportController::class, 'index'])->name('leads.import');
    //     Route::post('leads/import', [ExcelImportController::class, 'store'])->name('leads.import.store');
    // });


    // Route::resource('leads', LeadController::class);
   
     Route::post('followups', [FollowUpController::class, 'store'])->name('followups.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->group(function () {

    // no role middleware here
    Route::get('leads/import',  [ExcelImportController::class, 'index'])->name('leads.import');
    Route::post('leads/import', [ExcelImportController::class, 'store'])->name('leads.import.store');

    Route::resource('leads', LeadController::class);

});

Route::middleware(['auth'])->group(function () {
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
});