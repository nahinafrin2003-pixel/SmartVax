<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Models\VaccineList;
use App\Models\vaccineReq;
use App\Http\Controllers\RequestvaccineController;
use App\Http\Controllers\CertificateController;
use App\Models\Suggestion; // Import the Suggestion model
use Illuminate\Support\Facades\Auth;

Route::get('/', [Homecontroller::class, 'home']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    // Fetch all available vaccines
    $vaccines = VaccineList::all();

    // Fetch suggestions specifically for the currently logged-in user
    $suggestions = Suggestion::where('user_id', Auth::id())->get();

    // Pass both datasets to the view
    return view('dashboard', compact('vaccines', 'suggestions'));

})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::get('admin/dashboard', [HomeController::class, 'index'])->
    middleware(['auth','admin']);

Route::get('vaccine_list', [AdminController::class, 'vaccine_list'])->
    middleware(['auth','admin']);
Route::post('add_vaccine', [AdminController::class, 'add_vaccine'])->
    middleware(['auth','admin']);
Route::get('delete_expired/{id}', [AdminController::class, 'delete_expired'])->
    middleware(['auth','admin']);
Route::get('postEvent', [AdminController::class, 'postEvent'])->
    middleware(['auth','admin']);
Route::post('up_post', [AdminController::class, 'up_post'])->
    middleware(['auth','admin']);
Route::get('delete_old/{id}', [AdminController::class, 'delete_old'])->
    middleware(['auth','admin']);
Route::get('areas', [AdminController::class, 'areas'])->
    middleware(['auth','admin']);
Route::post('add_area', [AdminController::class, 'add_area'])->
    middleware(['auth','admin']);
Route::get('reqVax', [RequestvaccineController::class, 'reqVax'])->
    middleware(['auth','verified']);

Route::post('post_req', [RequestvaccineController::class, 'post_req'])->
    middleware(['auth','verified']);

Route::get('view_area', [RequestvaccineController::class, 'reqVax'])->
    middleware(['auth','verified']);
Route::get('admin/dashboard', [AdminController::class, 'index'])->
    middleware(['auth','admin']);
Route::get('approve_request/{id}', [AdminController::class, 'approve_request'])->
    middleware(['auth','admin']);
Route::get('reject_request/{id}', [AdminController::class, 'reject_request'])->
    middleware(['auth','admin']);
Route::get('citizens', [AdminController::class, 'citizens'])->
    middleware(['auth','admin']);
// Add this route for deactivating a user
Route::get('delete_citizen/{id}', [AdminController::class, 'delete_citizen'])->
    middleware(['auth','admin']);
Route::get('view_certificate', [CertificateController::class, 'view_certificate'])->
    middleware(['auth']);
Route::post('post_feedback', [CertificateController::class, 'post_feedback'])->
    middleware(['auth']);
Route::get('view_feedback', [AdminController::class, 'view_feedback'])->
    middleware(['auth','admin']);
// Add this route for resolving feedback
Route::get('resolve_feedback/{id}', [AdminController::class, 'resolve_feedback'])->
    middleware(['auth','admin']);
// Add this route for cancelling a request
Route::get('cancel_request/{id}', [RequestvaccineController::class, 'cancel_request'])->
    middleware(['auth']);
// Add this route for the contact form submission
Route::post('/contact', [HomeController::class, 'store_contact']);
// Add this route for solving a contact message
Route::get('solve_contact/{id}', [AdminController::class, 'solve_contact'])->
    middleware(['auth','admin']);
// Add this route for storing admin suggestions
Route::post('store_suggestion', [AdminController::class, 'store_suggestion'])->
    middleware(['auth','admin']);