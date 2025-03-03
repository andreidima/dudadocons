<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AcasaController;
use App\Http\Controllers\MembruController;
use App\Http\Controllers\SubcontractantController;
use App\Http\Controllers\ProiectController;
use App\Http\Controllers\FisierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailController;



Auth::routes(['register' => false, 'password.request' => false, 'reset' => false]);

Route::redirect('/', '/acasa');

Route::middleware(['auth', 'checkUserActiv'])->group(function () {
    Route::get('/acasa', [AcasaController::class, 'acasa'])->name('acasa');

    Route::resource('membri', MembruController::class)->parameters(['membri' => 'membru']);
    Route::resource('subcontractanti', SubcontractantController::class)->parameters(['subcontractanti' => 'subcontractant']);

    Route::group([
        'prefix' => 'proiecte/{tipProiect}',
        'as' => 'proiecte.'
    ], function () {
        Route::get('{proiect}/emailuri/{destinatar_type}/{destinatar_id}', [ProiectController::class, 'showEmailuri'])
            ->name('show.emailuri');
        Route::resource('', ProiectController::class)->parameters(['' => 'proiect']);
    });

    // Custom route for managing files (using query parameters for owner info)
    Route::get('/fisiere/manage', [FisierController::class, 'manage'])->name('fisiere.manage');
    Route::get('/fisiere/{fisier}/view', [FisierController::class, 'view'])->name('fisiere.view');
    Route::resource('fisiere', FisierController::class)->only(['store', 'destroy'])->parameters(['fisiere' => 'fisier']);

    Route::resource('/utilizatori', UserController::class)->parameters(['utilizatori' => 'user'])->names('users')
        // ->middleware('checkUserRole:admin,superadmin');
        ->middleware('checkUserRole:Admin,SuperAdmin');

    Route::post('/send-email', [EmailController::class, 'sendEmail'])->name('email.send');
});
