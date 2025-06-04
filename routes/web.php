<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TopupController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonateController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MyDonationsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', function () {
    return view('landing-page');
})->name('landing');

// Authentication Routes
Route::get('/sign-in', [AuthController::class, 'showSignIn'])->name('sign-in');
Route::post('/sign-in', [AuthController::class, 'signIn'])->name('sign-in.post');
Route::get('/sign-up', [AuthController::class, 'showSignUp'])->name('sign-up');
Route::post('/sign-up', [AuthController::class, 'signUp'])->name('sign-up.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Placeholder routes for terms, privacy, forgot password
Route::get('/terms', function() {
    return view('legal.terms');
})->name('terms');

Route::get('/privacy', function() {
    return view('legal.privacy');
})->name('privacy');

Route::get('/forgot-password', function() {
    return view('auth.forgot-password');
})->name('forgot-password');

// Dashboard Routes (protected)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Account Routes
Route::get('/account', [AccountController::class, 'index'])->name('account');
Route::get('/account/editProfile', [AccountController::class, 'editProfile'])->name('account.editProfile');
Route::post('/account/update', [AccountController::class, 'update'])->name('account.update');

// Change Password Routes
Route::get('/account/changePassword', [AccountController::class, 'changePassword'])->name('account.changePassword');
Route::post('/account/updatePassword', [AccountController::class, 'updatePassword'])->name('account.updatePassword');

// Settings Routes
Route::get('/account/setting', [AccountController::class, 'setting'])->name('account.setting');
Route::post('/account/updateSettings', [AccountController::class, 'updateSettings'])->name('account.updateSettings');
Route::post('/account/updateLanguage', [AccountController::class, 'updateLanguage'])->name('account.updateLanguage');
Route::post('/account/delete', [AccountController::class, 'delete'])->name('account.delete');

// Topup Routes
Route::get('/topup', [TopupController::class, 'index'])->name('topup');
Route::post('/topup/process', [TopupController::class, 'process'])->name('topup.process');
Route::get('/topup/instruction', [TopupController::class, 'instruction'])->name('topup.instruction');
Route::match(['get', 'post'], '/topup/confirm', [TopupController::class, 'confirmPayment'])->name('topup.confirm');
Route::get('/topup/success', [TopupController::class, 'success'])->name('topup.success');

// Create Campaign Routes
Route::get('/campaign/create', [CampaignController::class, 'create'])->name('campaign.create');
Route::post('/campaign/store', [CampaignController::class, 'store'])->name('campaign.store');
Route::post('/campaign/generate-description', [CampaignController::class, 'generateDescription'])->name('campaign.generate-description');

// Campaign Routes
Route::get('/campaign/{id}', [CampaignController::class, 'detail'])->name('campaign.detail');
Route::get('/campaign/{id}/donate', [CampaignController::class, 'donate'])->name('campaign.donate');

// Donate Routes
Route::get('/donate/{campaignId}', [DonateController::class, 'index'])->name('donate');
Route::post('/donate/process', [DonateController::class, 'process'])->name('donate.process');
Route::get('/donate/instruction', [DonateController::class, 'instruction'])->name('donate.instruction');
Route::match(['get', 'post'], '/donate/confirm', [DonateController::class, 'confirmPayment'])->name('donate.confirm');
Route::get('/donate/success', [DonateController::class, 'success'])->name('donate.success');

// Other Routes
Route::get('/history', [HistoryController::class, 'index'])->name('history');
Route::get('/history/search', [HistoryController::class, 'search'])->name('history.search');

Route::get('/my-donations', [MyDonationsController::class, 'index'])->name('my-donations');

Route::get('/donation-reminder', function() {
    return view('donation-reminder');
})->name('donation-reminder');

// Campaign Routes
Route::get('/campaigns/category/{category}', function($category) {
    return view('campaigns.category', compact('category'));
})->name('campaigns.category');

// Search Route
Route::get('/search', function() {
    $query = request('q');
    return response()->json(['results' => []]);
})->name('search');
