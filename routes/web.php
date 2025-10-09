<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShipController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return redirect('login');
});

Route::get('login', 'App\Http\Controllers\AuthController@index')->name('login');
Route::post('proses_login', 'App\Http\Controllers\AuthController@proses_login')->name('proses_login');
Route::get('logout', 'App\Http\Controllers\AuthController@logout')->name('logout');
Route::post('logout', 'App\Http\Controllers\AuthController@logout');

Route::get('test/payment/{report}', function ($report) {
    return app(InvoiceController::class)->initiatePayment($report);
});

Route::get('payment/success', [InvoiceController::class, 'success'])->name('payment.success');
Route::get('payment/cancel', [InvoiceController::class, 'cancel'])->name('payment.cancel');
Route::post('payment/callback', [InvoiceController::class, 'callback'])->middleware('web')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])->name('payment.callback');

Route::get('report_surveyor_review/{id}', [ReportController::class, 'report_surveyor_review']);
Route::post('surveyor_approve/{form}', [ReportController::class, 'surveyor_approve']);
Route::post('surveyor_revise/{form}', [ReportController::class, 'surveyor_revise']);
Route::post('surveyor_submit/{id}', [ReportController::class, 'surveyor_submit']);

// tes aja
Route::get('comcerts/{id}', [CompanyController::class, 'comcerts'])->name('comcerts.show');
Route::get('report_print_tcpdf3/{id}', [\App\Http\Controllers\Report\TcpdfController::class, 'data']);
Route::get('report_print_tcpdf4/{id}', [\App\Http\Controllers\Report\TcpdfController::class, 'run']);
Route::get('cek/{id}', [\App\Http\Controllers\ReportOptimizedController::class, 'listPDF']);

Route::group(['middleware' => ['auth']], function () {
    Route::get('profile', [UserController::class, 'profile'])->name('profile.edit');
    Route::post('profile', [UserController::class, 'profile']);
    Route::get('user_list', [UserController::class, 'user_list']);

    Route::group(['middleware' => ['check_profile']], function () {
        Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('inspektor', [AdminController::class, 'index'])->name('inspektor');
        Route::get('supervisor', [AdminController::class, 'index'])->name('supervisor');

        Route::group(['middleware' => ['role:superadmin|administrator']], function () {
            Route::get('profile/{id}', [UserController::class, 'profile']);
            Route::resource('superadmin|administrator', AdminController::class);
            Route::get('sa-dashboard', [AdminController::class, 'index']);
            Route::get('sa-customer', [AdminController::class, 'index_customers']);
            Route::get('sa-user', [AdminController::class, 'index_users']);
            Route::get('sa-permohonanbarangjasa', [AdminController::class, 'index_permohonan_barang_jasa']);
            Route::get('sa-permohonanbarang', [AdminController::class, 'index_permohonan_barang']);
            Route::get('sa-permohonanjasa', [AdminController::class, 'index_permohonan_jasa']);
            Route::get('sa-tagihan', [AdminController::class, 'index_tagihan']);

            Route::get('user_management', [UserController::class, 'user_management']);
            Route::post('user_data', [UserController::class, 'user_data']);
            Route::get('user_data/{id}', [UserController::class, 'show'])->name('user.show');
            Route::get('user_datatables', [UserController::class, 'user_datatables']);

            Route::get('settings/form_type', [SettingController::class, 'form_type']);
            Route::post('settings/form_type_data', [SettingController::class, 'form_type_data']);
            Route::get('settings/form_type_datatables', [SettingController::class, 'form_type_datatables']);

            Route::get('settings/ship_type', [SettingController::class, 'ship_type']);
            Route::get('settings/ship_type_datatables', [SettingController::class, 'ship_type_datatables']);
            Route::post('settings/ship_type_data', [SettingController::class, 'ship_type_data']);

            Route::get('settings/category', [SettingController::class, 'category']);
            Route::get('settings/category_datatables', [SettingController::class, 'category_datatables']);
            Route::post('settings/category_data', [SettingController::class, 'category_data']);

            Route::get('settings/report', [SettingController::class, 'report']);
            Route::post('settings/report_data', [SettingController::class, 'report_data']);
        });

        Route::group(['middleware' => ['role:administrator']], function () {
            Route::resource('administrator', AdminController::class);
            Route::get('a-customer', [AdminController::class, 'index_customer']);
            Route::get('a-permohonanbarangjasa', [AdminController::class, 'index_permohonan_barang_jasa']);
            Route::get('a-tambahbarangjasa', [AdminController::class, 'add_permohonan_barang_jasa']);
            Route::get('a-permohonanbarang', [AdminController::class, 'index_permohonan_barang']);
            Route::get('a-permohonanjasa', [AdminController::class, 'index_permohonan_jasa']);
            Route::get('a-tagihan', [AdminController::class, 'index_tagihan']);
            Route::post('a-tambahpemohonbarangjasa', [AdminController::class, 'save_permohonan_barang_jasa']);
        });

        Route::group(['middleware' => ['role:manajemenproyek']], function () {
            Route::resource('manajemenproyek', UserController::class);
            Route::get('mp-tagihan', [UserController::class, 'index_tagihan']);
        });

        Route::group(['middleware' => ['role:user']], function () {
            Route::resource('user', UserController::class);
            Route::get('u-tagihan', [UserController::class, 'index_tagihan']);
        });

        // Multi-role access routes - for admin roles (superadmin|administrator, administrator, supervisor, inspektor)
        Route::group(['middleware' => ['role:superadmin|administrator|supervisor|inspektor']], function () {
            // Equipment Routes - accessible by admin roles
            Route::get('equipment', [EquipmentController::class, 'index'])->name('equipment.index');
            Route::get('equipment/show/{id}', [EquipmentController::class, 'show'])->name('equipment.show');
            Route::get('equipment/certificates/{equipmentId}', [EquipmentController::class, 'getCertificates'])->name('equipment.certificates.get');
            
            // Company Routes - accessible by admin roles  
            Route::get('company', [CompanyController::class, 'index'])->name('company.index');
            Route::get('company/data', [CompanyController::class, 'data'])->name('company.data');
            Route::get('company/user/data/{company}', [CompanyController::class, 'user_data'])->name('company.users.data');
            Route::get('company/show/{id}', [SettingController::class, 'company_detail'])->name('company.show');
            
            // Report Routes - accessible by admin roles
            Route::get('report', [ReportController::class, 'index'])->name('report.index');
            Route::get('report_detail/{id}', [ReportController::class, 'report_detail'])->name('report.report_detail');
            Route::get('report_print_pdf/{id}', [ReportController::class, 'report_print_pdf']);
            Route::get('report_print_tcpdf/{id}', [ReportController::class, 'report_print_tcpdf']);
            Route::get('report_print_tcpdf2/{id}', [\App\Http\Controllers\ReportOptimizedController::class, 'render']);
            Route::get('report_preview/{id}', [ReportController::class, 'report_preview']);
            Route::get('report_datatables', [ReportController::class, 'report_datatables'])->name('ship.report_datatables');
            
            Route::get('form/edit/{id}', [FormController::class, 'edit']);
            Route::get('form_datatables', [ShipController::class, 'form_datatables'])->name('ship.form_datatables');

            Route::get('ship', [ShipController::class, 'index'])->name('ship.index');
            Route::get('ship_datatables', [ShipController::class, 'ship_datatables'])->name('ship.ship_datatables');
            Route::get('ship_list', [ShipController::class, 'ship_list'])->name('ship.list');
            
            Route::get('user-certificates/{user_id}', [UserController::class, 'getUserCertificates'])->name('user.certificates');
        });

        // High privilege routes - for superadmin|administrator and supervisor only (equipment management)
        Route::group(['middleware' => ['role:superadmin|administrator|supervisor']], function () {
            Route::post('equipment/save/{id}', [EquipmentController::class, 'save'])->name('equipment.save');
            Route::delete('equipment/delete/{id}', [EquipmentController::class, 'delete'])->name('equipment.delete');
            Route::post('equipment', [EquipmentController::class, 'store'])->name('equipment.store');
            Route::post('equipment/certificate/add', [EquipmentController::class, 'addCertificate'])->name('equipment.certificate.add');
            Route::delete('equipment/certificate/delete/{id}', [EquipmentController::class, 'deleteCertificate'])->name('equipment.certificate.delete');
            Route::put('equipment/certificate/update/{id}', [EquipmentController::class, 'updateCertificate'])->name('equipment.certificate.update');
            Route::post('equipment/certificate/toggle-active/{id}', [EquipmentController::class, 'toggleCertificateActive'])->name('equipment.certificate.toggle-active');
            
            Route::post('company', [CompanyController::class, 'store'])->name('company.store');
            Route::post('company/save/{id}', [SettingController::class, 'company_setting_save'])->name('company.save');
            Route::post('company/certificate/save/{id}', [SettingController::class, 'company_certificate_save'])->name('company.certificate.save');
            Route::put('company/{id}', [CompanyController::class, 'update'])->name('company.update');
            Route::delete('company/{id}', [CompanyController::class, 'destroy'])->name('company.destroy');
            
            // Inspector management routes
            Route::post('company/inspector/store', [CompanyController::class, 'storeInspector'])->name('company.inspector.store');
            Route::post('company/inspector/update/{id}', [CompanyController::class, 'updateInspector'])->name('company.inspector.update');
            Route::delete('company/inspector/delete/{id}', [CompanyController::class, 'deleteInspector'])->name('company.inspector.delete');
            
            // Competency management routes
            Route::post('company/competency/store', [CompanyController::class, 'storeCompetency'])->name('company.competency.store');
            Route::delete('company/competency/delete/{id}', [CompanyController::class, 'deleteCompetency'])->name('company.competency.delete');
        });

        // Report submission and modification routes - for inspektor and supervisor
        Route::group(['middleware' => ['role:inspektor|supervisor']], function () {
            Route::post('report_data', [ReportController::class, 'report_data'])->name('report.report_data');
            Route::post('report_image', [ReportController::class, 'report_image'])->name('report.report_image');
            Route::post('report_submit', [ReportController::class, 'report_submit'])->name('report.report_submit');
            Route::get('report/edit-general-particular/{id}', [ReportController::class, 'editGeneralParticular'])->name('report.edit_general_particular');
            Route::put('report/update-general-particular/{id}', [ReportController::class, 'updateGeneralParticular'])->name('report.update_general_particular');
            
            Route::post('form_data', [FormController::class, 'form_data'])->name('form.form_data');
            Route::post('ship_data', [ShipController::class, 'ship_data'])->name('ship.ship_data');
            Route::post('upload-certificate', [UserController::class, 'uploadCertificate'])->name('upload.certificate');
        });

        // Supervisor specific routes
        Route::group(['middleware' => ['role:supervisor']], function () {
            Route::get('supervisor_review/{id}', [ReportController::class, 'report_supervisor_review'])->name('supervisor.review');
            Route::post('supervisor_approve/{form}', [ReportController::class, 'supervisor_approve'])->name('supervisor.approve');
            Route::post('supervisor_revise/{form}', [ReportController::class, 'supervisor_revise'])->name('supervisor.revise');
            Route::post('supervisor_submit/{id}', [ReportController::class, 'supervisor_submit'])->name('supervisor.submit');
            
            Route::get('company/user/update/{company}/{user}', [CompanyController::class, 'user_update'])->name('company.users.update');
        });

        // Sync routes - available for authorized users
        Route::group(['middleware' => ['role:superadmin|administrator']], function () {
            Route::get('sync_ship', [ShipController::class, 'sync_ship'])->name('ship.sync');
            Route::get('sync_user', [UserController::class, 'sync_user'])->name('ship.user');
        });
    });
});
