<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\SiteSettingsController;
use App\Http\Controllers\DesignationsController;
use App\Http\Controllers\RegionsController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\OfficeLocationsController;
use App\Http\Controllers\StagingsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\VehiclesController;
use App\Http\Controllers\MachinesController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\SubcategoriesController;
use App\Http\Controllers\MeasurementsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ReportingsequencesController;
use App\Http\Controllers\TadaReportingsequencesController;
use App\Http\Controllers\RequisitionsController;
use App\Http\Controllers\MDRAttendancesController;
use App\Http\Controllers\MerchandisersController;
use App\Http\Controllers\HolidaysController;
use App\Http\Controllers\DistributorsController;

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

// Ajax routes
Route::get('get-reporting-sequences', [AjaxController::class, 'getReportingSequence'])->name('ajax.reporting.getReportingSequence');
Route::get('get-tada-reporting-sequences', [AjaxController::class, 'getTaDaReportingSequence'])->name('ajax.tadareporting.getTaDaReportingSequence');
Route::get('get-application', [AjaxController::class, 'getMDRApplication'])->name('ajax.applications.getMDRApplication');

Route::get('get-item-details', [AjaxController::class, 'getItemDetailsBySeraial'])->name('ajax.items.getItemDetailsBySeraial');
Route::get('get-item-details-merchandiser', [AjaxController::class, 'getItemDetailsBySeraialMerchandiser'])->name('ajax.items.getItemDetailsBySeraialMerchandiser');
Route::get('get-requisition-submitted', [AjaxController::class, 'getRequisitionSubmitted'])->name('ajax.items.getRequisitionSubmitted');
Route::get('get-items-approve', [AjaxController::class, 'getRequisitionApprove'])->name('ajax.items.getRequisitionApprove');
Route::get('get-items-cancel', [AjaxController::class, 'getRequisitionCancel'])->name('ajax.items.getRequisitionCancel');
Route::get('get-items-hold', [AjaxController::class, 'getRequisitionHold'])->name('ajax.items.getRequisitionHold');
Route::get('get-items-return', [AjaxController::class, 'getRequisitionReturn'])->name('ajax.items.getRequisitionReturn');
Route::get('get-items-processing', [AjaxController::class, 'getRequisitionProcessing'])->name('ajax.items.getRequisitionProcessing');
Route::get('get-items-submitted', [AjaxController::class, 'getRequisitionSubmitted'])->name('ajax.items.getRequisitionSubmitted');

Route::get('requisitions/showrequisition/{param}', [LocationsController::class, 'Download'])->name('locations.download');

// Department/Section/Category routes
Route::get('get-section-by-department/{id}', [AjaxController::class, 'getSectionByDepartment'])->name('ajax.sections.getSectionByDepartmentID');
Route::get('get-subcategorybycategory/{id}', [AjaxController::class, 'getSubCategoryByCategory'])->name('ajax.sections.getSubCategoryByCategoryID');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/home', [HomeController::class, 'index']);

    Route::get('dashboard/Job-crud', [HomeController::class, 'store'])->name('dashboards.Job-crud');
    Route::get('dashboard/updateEmployee', [HomeController::class, 'index'])->name('dashboards.index');

    Route::post('example', [HomeController::class, 'example'])->name('example');
    Route::get('pages/{name}', [HomeController::class, 'pages'])->name('template');

    /* ===================== Ajax Routes ================== */
    Route::post('get-district', [AjaxController::class, 'getDistricts']);
    Route::post('get-thanas', [AjaxController::class, 'getThanas']);
    Route::post('get-areas', [AjaxController::class, 'getAreas']);
    Route::get('stage-action-oparation/{id}/{functionName}/{stage}/{module?}', [AjaxController::class, 'stageActionOparation'])->name('ajax.stage.action');
    Route::post('stage-action-oparation-save/{module?}', [AjaxController::class, 'saveStageAction'])->name('ajax.stage.saveAction');

    Route::post('get-multi-district', [AjaxController::class, 'getMultiDistricts'])->name('ajax.getMultiDistricts');
    Route::post('get-multi-thana', [AjaxController::class, 'getMultiThanas'])->name('ajax.getMultiThanas');
    Route::post('get-multi-distributor', [AjaxController::class, 'getMultiDistributor'])->name('ajax.getMultiDistributor');
    Route::post('get-region-wise-depots', [AjaxController::class, 'getRegionWiseDepots'])->name('ajax.getRegionWiseDepots');
    Route::post('get-depot-codes', [AjaxController::class, 'getDepotCodes'])->name('ajax.getDepotCodes');

    Route::get('settlements-ajax/{param}/continue-list', [AjaxController::class, 'continueList'])->name('ajax.settlements.continueList');
    Route::get('settlements-ajax/{param}/closed-list', [AjaxController::class, 'closedList'])->name('ajax.settlements.closedList');

    Route::post('get-multi-technician', [AjaxController::class, 'getMultiTechnician'])->name('ajax.getMultiTechnician');
    Route::post('profile-picture-upload', [AjaxController::class, 'uploadProfilePicture'])->name('ajax.uploadProfilePicture');
    Route::get('get-sms-promotionals/{param?}', [AjaxController::class, 'getPromotionalSmsWithPaginate'])->name('ajax.smsPromotionals.get');
    Route::get('get-distributors', [AjaxController::class, 'getDistributorsWithPaginate'])->name('ajax.distributor.get');
});

Route::get('logout', [LoginController::class, 'logout']);
Auth::routes();

Route::group(['middleware' => ['auth', 'auth.access']], function () {
    Route::resource('site_settings', SiteSettingsController::class)->only(['edit', 'update']);
    Route::resource('roles', RolesController::class)->except(['show']);

    /*============== User Routes ==============*/
    Route::get('/users', [RegisterController::class, 'showUserLists'])->name('users.index');
    Route::get('/users/profile/{params?}', [RegisterController::class, 'showUser'])->name('users.show');
    Route::get('/users/{user}/edit', [RegisterController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [RegisterController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [RegisterController::class, 'destroyUser'])->name('users.destroy');
    Route::any('/password/change-user-password/{user}', [RegisterController::class, 'changeUserPassword'])->name('password.changeUserPassword');
    Route::any('/password/change', [RegisterController::class, 'changePassword'])->name('password.change');
    Route::get('/users/list/download', [RegisterController::class, 'download'])->name('users.download');

    /*============== Location Routes ==============*/
    Route::get('locations/{param?}', [LocationsController::class, 'index'])->name('locations.index');
    Route::get('locations/create/{param?}', [LocationsController::class, 'create'])->name('locations.create');
    Route::get('locations/{location}/edit/{param?}', [LocationsController::class, 'edit'])->name('locations.edit');
    Route::get('locations/download/{param}', [LocationsController::class, 'Download'])->name('locations.download');
    Route::resource('locations', LocationsController::class)->except(['index', 'show', 'create', 'edit']);

    /*============ Designation Routes ============*/
    Route::any('designations-sorting', [DesignationsController::class, 'sort'])->name('designations.sort');
    Route::get('designations/download', [DesignationsController::class, 'download'])->name('designations.download');
    Route::resource('designations', DesignationsController::class)->except(['show']);

    /*============ Department Routes ============*/
    Route::resource('departments', DepartmentsController::class)->except(['show']);
    Route::get('departments/download', [DepartmentsController::class, 'download'])->name('departments.download');

    /*============ Vehicle Routes ============*/
    Route::resource('vehicles', VehiclesController::class)->except(['show']);
    Route::get('vehicles/download', [VehiclesController::class, 'download'])->name('vehicles.download');
    Route::match(['get', 'post'], 'vehicles/uploads', [VehiclesController::class, 'uploadVehicle'])->name('vehicles.uploads');

    /*============ Machine Routes ============*/
    Route::resource('machines', MachinesController::class)->except(['show']);
    Route::get('machines/download', [MachinesController::class, 'download'])->name('machines.download');

    /*============ Section Routes ============*/
    Route::resource('sections', SectionsController::class)->except(['show']);
    Route::get('sections/download', [SectionsController::class, 'download'])->name('sections.download');

    /*============ Employee Routes ============*/
    Route::resource('employees', EmployeesController::class)->except(['show']);
    Route::get('employees/download', [EmployeesController::class, 'download'])->name('employees.download');
    Route::match(['get', 'post'], 'employees/uploads', [EmployeesController::class, 'uploadEmployee'])->name('employees.uploads');

    /*============ Office Location Routes ============*/
    Route::resource('officelocations', OfficeLocationsController::class)->except(['show']);
    Route::get('officelocations/download', [OfficeLocationsController::class, 'download'])->name('officelocations.download');

    /*============ Holiday Routes ============*/
    Route::resource('holidays', HolidaysController::class)->except(['show']);
    Route::get('holidays/download', [HolidaysController::class, 'download'])->name('holidays.download');

    /*============ Distributor Routes ============*/
    Route::resource('distributors', DistributorsController::class)->except(['show']);

    /*============ Category Routes ============*/
    Route::resource('categories', CategoriesController::class)->except(['show']);
    Route::get('categories/download', [CategoriesController::class, 'download'])->name('categories.download');

    /*============ Sub Category Routes ============*/
    Route::resource('subcategories', SubcategoriesController::class)->except(['show']);
    Route::get('subcategories/download', [SubcategoriesController::class, 'download'])->name('subcategories.download');

    /*============ Measurement Routes ============*/
    Route::resource('measurements', MeasurementsController::class)->except(['show']);

    /*============ Product Routes ============*/
    Route::resource('products', ProductsController::class)->except(['show']);
    Route::get('products/get-product-tag/{param}', [ProductsController::class, 'getProductTag'])->name('product.tag.get');
    Route::get('products/download', [ProductsController::class, 'download'])->name('products.download');
    Route::match(['get', 'post'], 'products/uploads', [ProductsController::class, 'uploadProduct'])->name('products.uploads');

    /*============ Reporting Sequence Routes ============*/
    Route::resource('reportingsequences', ReportingsequencesController::class)->except(['show']);
    Route::get('reportingsequences/download', [ReportingsequencesController::class, 'download'])->name('reportingsequences.download');

    /*============ TA/DA Reporting Sequence Routes ============*/
    Route::resource('tada_reportingsequences', TadaReportingsequencesController::class)->except(['show']);
    Route::get('tada_reportingsequences/download', [TadaReportingsequencesController::class, 'download'])->name('tada_reportingsequences.download');

    /*============ Requisition Routes ============*/
    Route::resource('requisitions', RequisitionsController::class)->except(['show']);
    Route::get('requisitions/download', [RequisitionsController::class, 'download'])->name('requisitions.download');
    Route::get('requisitions/ApprovedMDRdownload', [RequisitionsController::class, 'ApprovedMDRdownload'])->name('requisitions.ApprovedMDRdownload');
    Route::match(['get', 'post'], 'update-requisition-entry', [RequisitionsController::class, 'updaterequisition'])->name('requisitions.updateRequisition');
    Route::match(['get', 'post'], 'directsave', [RequisitionsController::class, 'directsave'])->name('requisitions.directsave');
    Route::get('requisitions/approved', [RequisitionsController::class, 'approved'])->name('requisitions.approved');
    Route::get('requisitions/inactive', [RequisitionsController::class, 'mdrInactive'])->name('requisitions.inactive');
    Route::get('requisitions/activelist', [RequisitionsController::class, 'mdrActive'])->name('requisitions.activelist');
    Route::get('requisitions/officerActiveMDR', [RequisitionsController::class, 'officerActiveMDR'])->name('requisitions.officerActiveMDR');
    Route::get('requisitions/explore', [RequisitionsController::class, 'explore'])->name('requisitions.explore');
    Route::get('requisitions/cancelled', [RequisitionsController::class, 'cancelled'])->name('requisitions.cancelled');
    Route::get('requisitions/hold', [RequisitionsController::class, 'hold'])->name('requisitions.hold');
    Route::get('requisitions/submitted', [RequisitionsController::class, 'submitted'])->name('requisitions.submitted');
    Route::get('requisitions/returned', [RequisitionsController::class, 'returned'])->name('requisitions.returned');
    Route::get('requisitions/processing', [RequisitionsController::class, 'processing'])->name('requisitions.processing');
    Route::get('requisitions/requisition_Dowload/{param}', [RequisitionsController::class, 'approveRequisitionDownload'])->name('requisitions.approveRequisitionDownload');
    Route::get('requisitions/agreement_Dowload/{param}', [RequisitionsController::class, 'mdragreementDownload'])->name('requisitions.mdragreementDownload');
    Route::any('requisitions/upload/resign_letter/{param}', [RequisitionsController::class, 'resign_letter'])->name('requisitions.resign_letter');

    /*============ MDR Attendance Routes ============*/
    Route::resource('mdrattendances', MDRAttendancesController::class)->except(['show']);
    Route::get('mdrattendances/download', [MDRAttendancesController::class, 'download'])->name('mdrattendances.download');
    Route::get('mdrattendances/download-Top-Sheet', [MDRAttendancesController::class, 'downloadTopSheet'])->name('mdrattendances.downloadTopSheet');
    Route::get('mdrattendances/CreateInactiveMDR', [MDRAttendancesController::class, 'inactiveMDRcreate'])->name('mdrattendances.inactiveMDRcreate');
    Route::any('mdrattendances/InactiveMDR', [MDRAttendancesController::class, 'InactiveMDR'])->name('mdrattendances.InactiveMDR');
    Route::get('mdrattendances/AttendanceList', [MDRAttendancesController::class, 'AttendanceList'])->name('mdrattendances.attendanceList');
    Route::get('mdrattendances/attendanceview/{id}', [MDRAttendancesController::class, 'attendanceview'])->name('mdrattendances.attendanceview');
    Route::get('mdrattendances/attendanceViewCheck/{id}', [MDRAttendancesController::class, 'attendanceViewCheck'])->name('mdrattendances.attendanceViewCheck');
    Route::post('mdrattendances/downloadAttendanceReport', [MDRAttendancesController::class, 'downloadAttendanceReport'])->name('mdrattendances.downloadAttendanceReport');
    Route::post('mdrattendances/downloadAttendanceTopSheet', [MDRAttendancesController::class, 'downloadAttendanceTopSheet'])->name('mdrattendances.downloadAttendanceTopSheet');
    Route::get('mdrattendances/approveAttendanceDownload/{param}', [MDRAttendancesController::class, 'approveAttendanceDownload'])->name('mdrattendances.approveAttendanceDownload');
    Route::get('mdrattendances/AttendanceProcessing', [MDRAttendancesController::class, 'AttendanceProcessing'])->name('mdrattendances.attendanceProcessing');
    Route::get('mdrattendances/AttendanceSubmitted', [MDRAttendancesController::class, 'AttendanceSubmitted'])->name('mdrattendances.attendanceSubmitted');
    Route::get('mdrattendances/AttendanceAudited', [MDRAttendancesController::class, 'AttendanceAudited'])->name('mdrattendances.attendanceAudited');
    Route::match(['get', 'post'], 'update-mdrattendances-entry', [MDRAttendancesController::class, 'UpdateAttendance'])->name('mdrattendances.updateAttendance');

    /*============ Merchandiser Routes ============*/
    Route::resource('merchandisers', MerchandisersController::class)->except(['show']);
    Route::resource('merchandiserattendances', MerchandisersController::class)->except(['show']);
    Route::get('merchandiserattendances/createMerchandiser', [MerchandisersController::class, 'createMerchandiser'])->name('merchandiserattendances.createMerchandiser');
    Route::post('merchandiserattendances/storeMerchandiser', [MerchandisersController::class, 'storeMerchandiser'])->name('merchandiserattendances.storeMerchandiser');
    Route::get('merchandiserattendances/AttendanceList', [MerchandisersController::class, 'AttendanceList'])->name('merchandiserattendances.attendanceList');
    Route::get('merchandiserattendances/attendanceview/{id}', [MerchandisersController::class, 'attendanceview'])->name('merchandiserattendances.attendanceview');
    Route::get('merchandiserattendances/attendanceViewCheck/{id}', [MerchandisersController::class, 'attendanceViewCheck'])->name('merchandiserattendances.attendanceViewCheck');
    Route::post('merchandiserattendances/downloadAttendanceReport', [MerchandisersController::class, 'downloadAttendanceReport'])->name('merchandiserattendances.downloadAttendanceReport');
    Route::post('merchandiserattendances/downloadAttendanceTopSheet', [MerchandisersController::class, 'downloadAttendanceTopSheet'])->name('merchandiserattendances.downloadAttendanceTopSheet');
    Route::get('merchandiserattendances/approveAttendanceDownload/{param}', [MerchandisersController::class, 'approveAttendanceDownload'])->name('merchandiserattendances.approveAttendanceDownload');
    Route::get('merchandiserattendances/AttendanceProcessing', [MerchandisersController::class, 'AttendanceProcessing'])->name('merchandiserattendances.attendanceProcessing');
    Route::get('merchandiserattendances/AttendanceSubmitted', [MerchandisersController::class, 'AttendanceSubmitted'])->name('merchandiserattendances.attendanceSubmitted');
    Route::get('merchandiserattendances/AttendanceAudited', [MerchandisersController::class, 'AttendanceAudited'])->name('merchandiserattendances.attendanceAudited');
    Route::match(['get', 'post'], 'update-merchandiserattendances-entry', [MerchandisersController::class, 'UpdateAttendance'])->name('merchandiserattendances.updateAttendance');
    Route::get('merchandisers/download', [MerchandisersController::class, 'download'])->name('merchandiserattendances.download');
    Route::get('merchandiserattendances/download-Top-Sheet', [MerchandisersController::class, 'downloadTopSheet'])->name('merchandiserattendances.downloadTopSheet');

    /*============ Region Routes ============*/
    Route::resource('regions', RegionsController::class)->except(['show']);
    Route::get('regions/download', [RegionsController::class, 'download'])->name('regions.download');

    /*============ Staging Routes ============*/
    Route::get('stages/{modules}', [StagingsController::class, 'index'])->name('stages.index');
    Route::get('stages/{modules}/create', [StagingsController::class, 'create'])->name('stages.create');
    Route::post('stages/{modules}', [StagingsController::class, 'store'])->name('stages.store');
    Route::get('stages/{modules}/edit/{stage}', [StagingsController::class, 'edit'])->name('stages.edit');
    Route::put('stages/{modules}/{stage}', [StagingsController::class, 'update'])->name('stages.update');
    Route::delete('stages/{modules}/{stages}', [StagingsController::class, 'destroy'])->name('stages.destroy');
    Route::delete('stage-untag/{modules}/{stageDetail}/{stage}', [StagingsController::class, 'untag'])->name('stage.details.untag');
    Route::any('stage-sorting/{modules}', [StagingsController::class, 'sort'])->name('stages.sort');
});