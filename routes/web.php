<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\RoomController;
use App\Http\Controllers\Customer\ReservationController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MeetingRoomController;
use App\Http\Controllers\Admin\FoodPackageController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Customer\PaymentController;

// === Rute Publik (Pelanggan) ===
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{id}/reserve', [ReservationController::class, 'showForm'])->name('reservation.form');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservation.store');
Route::get('/reservation/buffet-selection', [ReservationController::class, 'showBuffetSelection'])->name('reservation.buffet.selection');
Route::post('/reservation/buffet-selection', [ReservationController::class, 'storeBuffetSelection'])->name('reservation.buffet.store');
Route::get('/reservations/{id}/confirmation', [ReservationController::class, 'confirmation'])->name('reservation.confirmation');
Route::get('/payment/simulate/{id}', [ReservationController::class, 'simulatePayment'])->name('payment.simulate');
Route::post('/payment/notification', [PaymentController::class, 'callback'])->name('payment.notification');
Route::post('/midtrans/callback', [PaymentController::class, 'callback'])->name('midtrans.callback');
Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
Route::get('/payment/{id}', [PaymentController::class, 'pay'])->name('payment.pay');
Route::get('/reservations/{id}/invoice', [ReservationController::class, 'invoice'])->name('reservation.invoice');
Route::get('/api/check-availability', [ReservationController::class, 'checkAvailability'])->name('api.check-availability');
Route::get('/api/booked-dates/{roomId}', [ReservationController::class, 'getBookedDates'])->name('api.booked-dates');

// Halaman Promo
Route::get('/promotions', function () {
    $promotions = \App\Models\Promotion::where('status', true)->get();
    return view('customer.promotions', compact('promotions'));
})->name('promotions.index');

// Halaman Kontak
Route::get('/contact', function () {
    return view('customer.contact');
})->name('contact');

// === Rute Admin ===
Route::prefix('admin')->group(function () {
    // Login & Logout (publik)
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Rute yang membutuhkan autentikasi admin
    Route::middleware(['auth:web', 'admin'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Semua Reservasi
        Route::get('/reservations', [DashboardController::class, 'allReservations'])->name('admin.reservations');

        // Kelola Reservasi
        Route::get('/reservations/{id}', [DashboardController::class, 'show'])->name('admin.reservation.show');
        Route::post('/reservations/{id}/update-status', [DashboardController::class, 'updateStatus'])->name('admin.reservation.update-status');
        Route::get('/reservations/{id}/whatsapp', [DashboardController::class, 'whatsappSimulation'])->name('admin.reservation.whatsapp');
        Route::post('/reservations/{id}/whatsapp/send', [DashboardController::class, 'markWhatsappSent'])->name('admin.reservation.whatsapp.send');
        Route::post('/reservations/{id}/whatsapp/reply', [DashboardController::class, 'simulateCustomerReply'])->name('admin.reservation.whatsapp.reply');

        // 3. Kelola Ruang Meeting
        Route::resource('rooms', MeetingRoomController::class)->names([
            'index' => 'admin.rooms.index',
            'create' => 'admin.rooms.create',
            'store' => 'admin.rooms.store',
            'edit' => 'admin.rooms.edit',
            'update' => 'admin.rooms.update',
            'destroy' => 'admin.rooms.destroy'
        ]);

        // 4. Kelola Paket Makanan
        Route::resource('packages', FoodPackageController::class)->names([
            'index' => 'admin.packages.index',
            'create' => 'admin.packages.create',
            'store' => 'admin.packages.store',
            'edit' => 'admin.packages.edit',
            'update' => 'admin.packages.update',
            'destroy' => 'admin.packages.destroy'
        ]);

        // 4b. Kelola Menu Buffet
        Route::resource('buffet-menus', \App\Http\Controllers\Admin\BuffetMenuController::class)->names([
            'index' => 'admin.buffet-menus.index',
            'create' => 'admin.buffet-menus.create',
            'store' => 'admin.buffet-menus.store',
            'edit' => 'admin.buffet-menus.edit',
            'update' => 'admin.buffet-menus.update',
            'destroy' => 'admin.buffet-menus.destroy'
        ]);

        // 5. Kelola Promosi
        Route::resource('promotions', PromotionController::class)->names([
            'index' => 'admin.promotions.index',
            'create' => 'admin.promotions.create',
            'store' => 'admin.promotions.store',
            'edit' => 'admin.promotions.edit',
            'update' => 'admin.promotions.update',
            'destroy' => 'admin.promotions.destroy'
        ]);

        // 8. Laporan Transaksi
        Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
        Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('admin.reports.export-pdf');
        Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('admin.reports.export-excel');
    });
});

//SOLUSI: Redirect route 'login' ke login admin
Route::redirect('/login', '/admin/login')->name('login');
