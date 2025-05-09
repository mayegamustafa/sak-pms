<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\LeaseController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PropertyController;
use App\Exports\PropertiesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentAnalysisController;


use App\Models\Unit;

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
/*
Route::get('/', function () {
    return view('welcome');
});  */

Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';




Route::middleware(['auth', 'role:' . User::ROLE_SUPER_ADMIN])->group(function () {
    Route::get('/admin-dashboard', function () {
        return view('admin.dashboard');
    });
});

Route::middleware(['auth', 'role:' . User::ROLE_MANAGER])->group(function () {
    Route::get('/manager-dashboard', function () {
        return view('manager.dashboard');
    });
});

Route::middleware(['auth', 'role:' . User::ROLE_OWNER])->group(function () {
    Route::get('/owner-dashboard', function () {
        return view('owner.dashboard');
    });
});



Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/manager/dashboard', [ManagerController::class, 'index'])->name('manager.dashboard');
    Route::get('/owner/dashboard', [OwnerController::class, 'index'])->name('owner.dashboard');
});




Route::middleware(['auth'])->group(function () {
    Route::resource('properties', PropertyController::class);
});
// Unit routes (nested under properties)
Route::resource('properties.units', UnitController::class);

// routes/web.php

Route::get('properties/export', function () {
    return Excel::download(new PropertiesExport, 'properties.xlsx');
});

// routes/web.php
Route::get('properties/export-pdf', [PropertyController::class, 'exportPdf']);
// routes/web.php
Route::get('properties/report', [PropertyController::class, 'generateReport']);
// routes/web.php
Route::get('properties/performance-chart', [PropertyController::class, 'showPerformanceChart']);
Route::get('/properties/report', [PropertyController::class, 'report'])->name('properties.report');
Route::get('/properties/performance-chart', [PropertyController::class, 'performanceChart'])->name('properties.performance-chart');
Route::get('/properties/export-pdf', [PropertyController::class, 'exportPdf'])->name('properties.export-pdf');
//Route::get('/properties/{id}', [PropertyController::class, 'show'])->name('properties.show');
// Example of fixing the conflict
Route::get('properties/{id}', [PropertyController::class, 'show'])->name('properties.show');
Route::get('properties/{id}/edit', [PropertyController::class, 'edit'])->name('properties.edit');


//Route::get('/properties/export', [PropertyController::class, 'export'])->name('properties.export');

//ROEUEEUEUH


Route::get('/properties/report', [PropertyController::class, 'report'])->name('properties.report');
Route::get('/properties/performance-chart', [PropertyController::class, 'performanceChart'])->name('properties.performance-chart');
//Route::get('/properties/export', [PropertyController::class, 'export'])->name('properties.export');
//Route::get('/properties/export-pdf', [PropertyController::class, 'exportPDF'])->name('properties.export-pdf');

Route::get('/properties/export/pdf', [PropertyController::class, 'exportPdf'])->name('properties.export.pdf');
Route::get('/properties/export/excel', [PropertyController::class, 'exportExcel'])->name('properties.export.excel');

Route::resource('units', UnitController::class);

Route::resource('tenants', TenantController::class);

// Fetch Units based on selected Property
//Route::get('/get-units/{property_id}', [TenantController::class, 'getUnits']);
Route::get('/get-units/{property_id}', [TenantController::class, 'getUnits']);


// routes/web.php

Route::resource('invoices', InvoiceController::class);

// routes/web.php

Route::post('invoices/{invoice}/pay', [InvoiceController::class, 'payInvoice'])->name('invoices.pay');
// routes/web.php

//Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'generateInvoicePDF'])->name('invoices.pdf');
// routes/web.php

Route::get('invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
// routes/web.php

Route::get('invoices/{id}/pdf', [InvoiceController::class, 'generateInvoicePDF'])->name('invoices.pdf');

//Route::resource('leases', LeaseController::class);
//Route::resource('payments', PaymentController::class);
Route::get('report/payments', [PaymentAnalysisController::class, 'report'])->name('payments.report');


Route::get('invoices/{invoice}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
Route::post('invoices/{invoice}/payments', [PaymentController::class, 'store'])->name('payments.store');

// routes/web.php

Route::get('/invoices/{id}/download', [InvoiceController::class, 'download'])->name('invoices.download');

Route::get('/invoices/send-sms-reminders', [InvoiceController::class, 'sendSMSReminders'])->name('invoices.smsReminders');
Route::get('/tenants/{tenant}/send-sms', [TenantController::class, 'sendSmsToTenant'])->name('tenants.sendSms');



Route::get('/properties/{propertyId}/vacant-units', function ($propertyId) {
    // Fetch vacant units for the selected property
    $vacantUnits = Unit::where('property_id', $propertyId)
                       ->where('status', 'Vacant')  // Assuming 'Vacant' is the status for available units
                       ->get();

    return response()->json([
        'units' => $vacantUnits
    ]);
});


Route::get('/properties/{property}/vacant-units', function ($propertyId) {
    $units = \App\Models\Unit::where('property_id', $propertyId)
        ->where('status', 'Vacant')
        ->get(['id', 'unit_number', 'rent_amount']);

    return response()->json(['units' => $units]);
});



Route::post('/tenants/send-bulk-sms', [TenantController::class, 'sendBulkSms'])->name('tenants.sendBulkSms');



Route::resource('leases', LeaseController::class);
Route::put('/leases/{id}/renew', [LeaseController::class, 'renew'])->name('leases.renew');
Route::put('/leases/{id}/terminate', [LeaseController::class, 'terminate'])->name('leases.terminate');

Route::get('/payments/create1', [PaymentController::class, 'create1'])->name('payments.create1');
Route::post('/payments/store1', [PaymentController::class, 'store1'])->name('payments.store1');
Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/export/{type}', [OwnerController::class, 'export'])->name('owner.export');
});
