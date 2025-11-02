<?php

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | contains the "web" middleware group. Now create something great!
 * |
 */
//use App\Http\Controllers\RequisitionsController;

//problem entry form open with item detils
    //-----------------------Reporting Sequence Start--------------------------
     Route::get('get-reporting-sequences', 'AjaxController@getReportingSequence')->name('ajax.reporting.getReportingSequence');

     Route::get('get-tada-reporting-sequences', 'AjaxController@getTaDaReportingSequence')->name('ajax.tadareporting.getTaDaReportingSequence');

     Route::get('get-application', 'AjaxController@getMDRApplication')->name('ajax.applications.getMDRApplication');

     //-----------------------Reporting Sequence End--------------------------

    Route::get('get-item-details', 'AjaxController@getItemDetailsBySeraial')->name('ajax.items.getItemDetailsBySeraial');
    Route::get('get-item-details-merchandiser', 'AjaxController@getItemDetailsBySeraialMerchandiser')->name('ajax.items.getItemDetailsBySeraialMerchandiser');

    Route::get('get-requisition-submitted', 'AjaxController@getRequisitionSubmitted')->name('ajax.items.getRequisitionSubmitted');

    Route::get('get-items-approve', 'AjaxController@getRequisitionApprove')->name('ajax.items.getRequisitionApprove');
    Route::get('get-items-cancel', 'AjaxController@getRequisitionCancel')->name('ajax.items.getRequisitionCancel');
    Route::get('get-items-hold', 'AjaxController@getRequisitionHold')->name('ajax.items.getRequisitionHold');
    Route::get('get-items-return', 'AjaxController@getRequisitionReturn')->name('ajax.items.getRequisitionReturn');
    Route::get('get-items-processing', 'AjaxController@getRequisitionProcessing')->name('ajax.items.getRequisitionProcessing');
    Route::get('get-items-submitted', 'AjaxController@getRequisitionSubmitted')->name('ajax.items.getRequisitionSubmitted');
    
    Route::get("requisitions/showrequisition/{param}", array(
        'uses' => 'LocationsController@Download',
        'as' => 'locations.download',
    ));

    //---------Department/ Section / Category / Sub CategoryStart----------------
    Route::get('get-section-by-department/{id}', 'AjaxController@getSectionByDepartment')->name('ajax.sections.getSectionByDepartmentID');

    Route::get('get-subcategorybycategory/{id}', 'AjaxController@getSubCategoryByCategory')->name('ajax.sections.getSubCategoryByCategoryID');

    //--------Department/ Section / Category / Sub CategoryStart-----------------

    Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('dashboard');
    Route::get('/home', 'HomeController@index');

    Route::get('dashboard/Job-crud', [
        'as' => 'dashboards.Job-crud',
        'uses' => 'HomeController@store',
    ]);
    Route::get('dashboard/updateEmployee', [
        'as' => 'dashboards.index',
        'uses' => 'HomeController@index',
    ]);
    //Route::get('dashboards', 'HomeController@index');


    Route::post('example', 'HomeController@example')->name('example');

    // start for template all page , it should be remove for production
    Route::get('pages/{name}', 'HomeController@pages')->name('template');
    // end for template all page , it should be remove for production

    /* =====================Ajax Route Start================== */
    Route::post('get-district', 'AjaxController@getDistricts');
    Route::post('get-thanas', 'AjaxController@getThanas');
    Route::post('get-areas', 'AjaxController@getAreas');
    Route::get('stage-action-oparation/{id}/{functionName}/{stage}/{module?}', 'AjaxController@stageActionOparation')->name('ajax.stage.action');
    Route::post('stage-action-oparation-save/{module?}', 'AjaxController@saveStageAction')->name('ajax.stage.saveAction');

    Route::post('get-multi-district', 'AjaxController@getMultiDistricts')->name('ajax.getMultiDistricts');
    Route::post('get-multi-thana', 'AjaxController@getMultiThanas')->name('ajax.getMultiThanas');
    Route::post('get-multi-distributor', 'AjaxController@getMultiDistributor')->name('ajax.getMultiDistributor');
    Route::post('get-region-wise-depots', 'AjaxController@getRegionWiseDepots')->name('ajax.getRegionWiseDepots');
    Route::post('get-depot-codes', 'AjaxController@getDepotCodes')->name('ajax.getDepotCodes');

    Route::get('settlements-ajax/{param}/continue-list', 'AjaxController@continueList')->name('ajax.settlements.continueList');
    Route::get('settlements-ajax/{param}/closed-list', 'AjaxController@closedList')->name('ajax.settlements.closedList');

    Route::post('get-multi-technician', 'AjaxController@getMultiTechnician')->name('ajax.getMultiTechnician');
    Route::post('profile-picture-upload', 'AjaxController@uploadProfilePicture')->name('ajax.uploadProfilePicture');
    Route::get('get-sms-promotionals/{param?}', 'AjaxController@getPromotionalSmsWithPaginate')->name('ajax.smsPromotionals.get');
    Route::get('get-distributors', 'AjaxController@getDistributorsWithPaginate')->name('ajax.distributor.get');
    /* =====================Ajax route End==================== */
});

Route::get('logout', 'Auth\LoginController@logout');
Auth::routes();

Route::group(['middleware' => ['auth', 'auth.access']], function () {

    Route::resource('site_settings', 'SiteSettingsController',
        ['only' => ['edit', 'update']]);
    Route::resource('roles', 'RolesController', ['except' => 'show']);

    /*==============User start here==============*/
    Route::get('/users', 'Auth\RegisterController@showUserLists')->name('users.index');
    Route::get('/users/profile/{params?}', 'Auth\RegisterController@showUser')->name('users.show');
    Route::get('/users/{user}/edit', 'Auth\RegisterController@editUser')->name('users.edit');
    Route::put('/users/{user}', 'Auth\RegisterController@updateUser')->name('users.update');
    Route::delete('/users/{user}', 'Auth\RegisterController@destroyUser')->name('users.destroy');
    Route::any('/password/change-user-password/{user}', 'Auth\RegisterController@changeUserPassword')->name('password.changeUserPassword');
    Route::any('/password/change', 'Auth\RegisterController@changePassword')->name('password.change');
    Route::get('/users/list/download', 'Auth\RegisterController@download')->name('users.download');

    /*==============User start here==============*/

    /*==============location start here==============*/
    Route::get('locations/{param?}', [
        'as' => 'locations.index',
        'uses' => 'LocationsController@index',
    ]);
    Route::get('locations/create/{param?}', [
        'as' => 'locations.create',
        'uses' => 'LocationsController@create',
    ]);
    Route::get('locations/{location}/edit/{param?}', [
        'as' => 'locations.edit',
        'uses' => 'LocationsController@edit',
    ]);

    Route::get("locations/download/{param}", array(
        'uses' => 'LocationsController@Download',
        'as' => 'locations.download',
    ));
    Route::resource('locations', 'LocationsController',
        ['except' => ['index', 'show', 'create', 'edit']]);

    /*==========location end here=============*/

    /*============designations start here========================*/
    Route::any('designations-sorting', [
        'as' => 'designations.sort',
        'uses' => 'DesignationsController@sort',
    ]);
    Route::get('designations/download', [
        'as' => 'designations.download',
        'uses' => 'DesignationsController@download',
    ]);
    Route::resource('designations', 'DesignationsController',
        ['except' => ['show']]);
    /*============designations end here========================*/

    /*============departments start here========================*/
    Route::resource('departments', 'DepartmentsController',
        ['except' => ['show']]);

    Route::get('departments/download', [
        'as' => 'departments.download',
        'uses' => 'DepartmentsController@download',
    ]);
    
    /*============departments end here========================*/

    /*============Vehicles start here========================*/
    Route::resource('vehicles', 'VehiclesController',
        ['except' => ['show']]);

    Route::get('vehicles/download', [
        'as' => 'vehicles.download',
        'uses' => 'VehiclesController@download',
    ]);

    Route::match(['get', 'post'],'vehicles/uploads', [
        'as' => 'vehicles.uploads',
        'uses' => 'VehiclesController@uploadVehicle',
    ]);
    
    /*============Vehicles end here========================*/

    /*============machines start here========================*/
    Route::resource('machines', 'MachinesController',
        ['except' => ['show']]);

    Route::get('machines/download', [
        'as' => 'machines.download',
        'uses' => 'MachinesController@download',
    ]);
    
    /*============machines end here========================*/

    /*============sections start here========================*/
    Route::resource('sections', 'SectionsController',
        ['except' => ['show']]);

    Route::get('sections/download', [
        'as' => 'sections.download',
        'uses' => 'SectionsController@download',
    ]);
    
    /*============sections end here========================*/

     /*============employees start here========================*/
    Route::resource('employees', 'EmployeesController',
        ['except' => ['show']]);

    Route::get('employees/download', [
        'as' => 'employees.download',
        'uses' => 'EmployeesController@download',
    ]);

    Route::match(['get', 'post'],'employees/uploads', [
        'as' => 'employees.uploads',
        'uses' => 'EmployeesController@uploadEmployee',
    ]);
    
    /*============employees end here========================*/

    /*============Office locations start here========================*/
    Route::resource('officelocations', 'OfficeLocationsController',
        ['except' => ['show']]);

    Route::get('officelocations/download', [
        'as' => 'officelocations.download',
        'uses' => 'OfficeLocationsController@download',
    ]);

    /*============Office locations end here========================*/

    /*============Holiday Setup start here========================*/
    Route::resource('holidays', 'HolidaysController',
        ['except' => ['show']]);

    Route::get('holidays/download', [
        'as' => 'holidays.download',
        'uses' => 'HolidaysController@download',
    ]);

    /*============Holiday Setup end here========================*/

    /*============distributors start here========================*/
    Route::resource('distributors', 'DistributorsController',
        ['except' => ['show']]);

    /*============distributors end here========================*/

    /*============Categories start here========================*/
    Route::resource('categories', 'CategoriesController',
        ['except' => ['show']]);

    Route::get('categories/download', [
        'as' => 'categories.download',
        'uses' => 'CategoriesController@download',
    ]);
    
    /*============Categories end here========================*/

     /*============Sub Category start here========================*/
    Route::resource('subcategories', 'SubcategoriesController',
        ['except' => ['show']]);

    Route::get('subcategories/download', [
        'as' => 'subcategories.download',
        'uses' => 'SubcategoriesController@download',
    ]);
    
    /*============Sub Category end here========================*/

    /*============Measurement start here========================*/
    Route::resource('measurements', 'MeasurementsController',
        ['except' => ['show']]);

    /*============Measurement end here========================*/

    /*============Products start here========================*/
    Route::resource('products', 'ProductsController',
        ['except' => ['show']]);

    Route::get('products/get-product-tag/{param}', 'ProductsController@getProductTag')->name('product.tag.get');

    Route::get('products/download', [
        'as' => 'products.download',
        'uses' => 'ProductsController@download',
    ]);

    Route::match(['get', 'post'],'products/uploads', [
        'as' => 'products.uploads',
        'uses' => 'ProductsController@uploadProduct',
    ]);

    /*============Products end here========================*/

    /*============Reporting Sequences start here========================*/
    Route::resource('reportingsequences', 'ReportingsequencesController',
        ['except' => ['show']]);

    Route::get('reportingsequences/download', [
        'as' => 'reportingsequences.download',
        'uses' => 'ReportingsequencesController@download',
    ]);

    /*============Reporting Sequences end here========================*/

    /*============TA/DA Reporting Sequences start here========================*/
    Route::resource('tada_reportingsequences', 'TadaReportingsequencesController',
        ['except' => ['show']]);

    Route::get('tada_reportingsequences/download', [
        'as' => 'tada_reportingsequences.download',
        'uses' => 'TadaReportingsequencesController@download',
    ]);

    /*============TA/DA Reporting Sequences end here========================*/

    /*============Requisition start here========================*/
    Route::resource('requisitions', 'RequisitionsController',
        ['except' => ['show']]);

    Route::get('requisitions/download', [
        'as' => 'requisitions.download',
        'uses' => 'RequisitionsController@download',
    ]);

    Route::get('requisitions/ApprovedMDRdownload', [
        'as' => 'requisitions.ApprovedMDRdownload',
        'uses' => 'RequisitionsController@ApprovedMDRdownload',
    ]);

    Route::match(array('GET', 'POST'), 'update-requisition-entry', [
        'as' => 'requisitions.updateRequisition',
        'uses' => 'RequisitionsController@updaterequisition',
    ]);

    Route::match(array('GET', 'POST'), 'directsave', [
        'as' => 'requisitions.directsave',
        'uses' => 'RequisitionsController@directsave',
    ]);

    Route::get('requisitions/approved', [
        'as' => 'requisitions.approved',
        'uses' => 'RequisitionsController@approved',
    ]);

    Route::get('requisitions/inactive', [
        'as' => 'requisitions.inactive',
        'uses' => 'RequisitionsController@mdrInactive',
    ]);

    Route::get('requisitions/activelist', [
        'as' => 'requisitions.activelist',
        'uses' => 'RequisitionsController@mdrActive',
    ]);

    Route::get('requisitions/officerActiveMDR', [
        'as' => 'requisitions.officerActiveMDR',
        'uses' => 'RequisitionsController@officerActiveMDR',
    ]);

    Route::get('requisitions/explore', [
        'as' => 'requisitions.explore',
        'uses' => 'RequisitionsController@explore',
    ]);
    Route::get('requisitions/cancelled', [
        'as' => 'requisitions.cancelled',
        'uses' => 'RequisitionsController@cancelled',
    ]);
    Route::get('requisitions/hold', [
        'as' => 'requisitions.hold',
        'uses' => 'RequisitionsController@hold',
    ]);
    Route::get('requisitions/submitted', [
        'as' => 'requisitions.submitted',
        'uses' => 'RequisitionsController@submitted',
    ]);
    Route::get('requisitions/returned', [
        'as' => 'requisitions.returned',
        'uses' => 'RequisitionsController@returned',
    ]);
    Route::get('requisitions/processing', [
        'as' => 'requisitions.processing',
        'uses' => 'RequisitionsController@processing',
    ]);
    Route::get('requisitions/requisition_Dowload/{param}', [
        'as' => 'requisitions.approveRequisitionDownload',
        'uses' => 'RequisitionsController@approveRequisitionDownload',
    ]);

    Route::get('requisitions/agreement_Dowload/{param}', [
        'as' => 'requisitions.mdragreementDownload',
        'uses' => 'RequisitionsController@mdragreementDownload',
    ]);

    Route::any('requisitions/upload/resign_letter/{param}', [
        'as' => 'requisitions.resign_letter',
        'uses' => 'RequisitionsController@resign_letter',
    ]); 

    /*============Requisition end here========================*/

    /*============ MDR Attendances start here========================*/
    Route::resource('mdrattendances', 'MDRAttendancesController',
        ['except' => ['show']]);

    Route::get('mdrattendances/download', [
        'as' => 'mdrattendances.download',
        'uses' => 'MDRAttendancesController@download',
    ]);

    Route::get('mdrattendances/download-Top-Sheet', [
        'as' => 'mdrattendances.downloadTopSheet',
        'uses' => 'MDRAttendancesController@downloadTopSheet',
    ]);
    Route::get('mdrattendances/CreateInactiveMDR', [
        'as' => 'mdrattendances.inactiveMDRcreate',
        'uses' => 'MDRAttendancesController@inactiveMDRcreate',
    ]);
    Route::any('mdrattendances/InactiveMDR', [
        'as' => 'mdrattendances.InactiveMDR',
        'uses' => 'MDRAttendancesController@InactiveMDR',
    ]);
    Route::get('mdrattendances/AttendanceList', [
        'as' => 'mdrattendances.attendanceList',
        'uses' => 'MDRAttendancesController@AttendanceList',
    ]);

    Route::get('mdrattendances/attendanceview/{id}', [
        'as' => 'mdrattendances.attendanceview',
        'uses' => 'MDRAttendancesController@attendanceview',
    ]);

    Route::get('mdrattendances/attendanceViewCheck/{id}', [
        'as' => 'mdrattendances.attendanceViewCheck',
        'uses' => 'MDRAttendancesController@attendanceViewCheck',
    ]);

    Route::post('mdrattendances/downloadAttendanceReport', [
        'as' => 'mdrattendances.downloadAttendanceReport',
        'uses' => 'MDRAttendancesController@downloadAttendanceReport',
    ]);

    Route::post('mdrattendances/downloadAttendanceTopSheet', [
        'as' => 'mdrattendances.downloadAttendanceTopSheet',
        'uses' => 'MDRAttendancesController@downloadAttendanceTopSheet',
    ]);

    Route::get('mdrattendances/approveAttendanceDownload/{param}', [
        'as' => 'mdrattendances.approveAttendanceDownload',
        'uses' => 'MDRAttendancesController@approveAttendanceDownload',
    ]);

    Route::get('mdrattendances/AttendanceProcessing', [
        'as' => 'mdrattendances.attendanceProcessing',
        'uses' => 'MDRAttendancesController@AttendanceProcessing',
    ]);

    Route::get('mdrattendances/AttendanceSubmitted', [
        'as' => 'mdrattendances.attendanceSubmitted',
        'uses' => 'MDRAttendancesController@AttendanceSubmitted',
    ]);

    Route::get('mdrattendances/AttendanceAudited', [
        'as' => 'mdrattendances.attendanceAudited',
        'uses' => 'MDRAttendancesController@AttendanceAudited',
    ]);

    Route::match(array('GET', 'POST'), 'update-mdrattendances-entry', [
        'as' => 'mdrattendances.updateAttendance',
        'uses' => 'MDRAttendancesController@UpdateAttendance',
    ]);

    /*============ MDR Attendances end here========================*/

    /*============ Merchandiser start here========================*/

    Route::resource('merchandisers', 'MerchandisersController',
        ['except' => ['show']]);

    Route::resource('merchandiserattendances', 'MerchandisersController',
        ['except' => ['show']]);

    Route::get('merchandiserattendances/createMerchandiser', [
        'as' => 'merchandiserattendances.createMerchandiser',
        'uses' => 'MerchandisersController@createMerchandiser',
    ]);

    Route::post('merchandiserattendances/storeMerchandiser', [
        'as' => 'merchandiserattendances.storeMerchandiser',
        'uses' => 'MerchandisersController@storeMerchandiser',
    ]);

    Route::get('merchandiserattendances/AttendanceList', [
        'as' => 'merchandiserattendances.attendanceList',
        'uses' => 'MerchandisersController@AttendanceList',
    ]);

    Route::get('merchandiserattendances/attendanceview/{id}', [
        'as' => 'merchandiserattendances.attendanceview',
        'uses' => 'MerchandisersController@attendanceview',
    ]);

    Route::get('merchandiserattendances/attendanceViewCheck/{id}', [
        'as' => 'merchandiserattendances.attendanceViewCheck',
        'uses' => 'MerchandisersController@attendanceViewCheck',
    ]);

    Route::post('merchandiserattendances/downloadAttendanceReport', [
        'as' => 'merchandiserattendances.downloadAttendanceReport',
        'uses' => 'MerchandisersController@downloadAttendanceReport',
    ]);

    Route::post('merchandiserattendances/downloadAttendanceTopSheet', [
        'as' => 'merchandiserattendances.downloadAttendanceTopSheet',
        'uses' => 'MerchandisersController@downloadAttendanceTopSheet',
    ]);

    Route::post('merchandiserattendances/downloadAttendanceReport', [
        'as' => 'merchandiserattendances.downloadAttendanceReport',
        'uses' => 'MerchandisersController@downloadAttendanceReport',
    ]);

    Route::post('merchandiserattendances/downloadAttendanceTopSheet', [
        'as' => 'merchandiserattendances.downloadAttendanceTopSheet',
        'uses' => 'MerchandisersController@downloadAttendanceTopSheet',
    ]);

    Route::get('merchandiserattendances/approveAttendanceDownload/{param}', [
        'as' => 'merchandiserattendances.approveAttendanceDownload',
        'uses' => 'MerchandisersController@approveAttendanceDownload',
    ]);

    Route::get('merchandiserattendances/approveAttendanceDownload/{param}', [
        'as' => 'merchandiserattendances.approveAttendanceDownload',
        'uses' => 'MerchandisersController@approveAttendanceDownload',
    ]);

    Route::get('merchandiserattendances/AttendanceProcessing', [
        'as' => 'merchandiserattendances.attendanceProcessing',
        'uses' => 'MerchandisersController@AttendanceProcessing',
    ]);

    Route::get('merchandiserattendances/AttendanceSubmitted', [
        'as' => 'merchandiserattendances.attendanceSubmitted',
        'uses' => 'MerchandisersController@AttendanceSubmitted',
    ]);

    Route::get('merchandiserattendances/AttendanceAudited', [
        'as' => 'merchandiserattendances.attendanceAudited',
        'uses' => 'MerchandisersController@AttendanceAudited',
    ]);

    Route::match(array('GET', 'POST'), 'update-merchandiserattendances-entry', [
        'as' => 'merchandiserattendances.updateAttendance',
        'uses' => 'MerchandisersController@UpdateAttendance',
    ]);

    Route::get('merchandisers/download', [
        'as' => 'merchandiserattendances.download',
        'uses' => 'MerchandisersController@download',
    ]);

    Route::get('merchandiserattendances/download-Top-Sheet', [
        'as' => 'merchandiserattendances.downloadTopSheet',
        'uses' => 'MerchandisersController@downloadTopSheet',
    ]);

    /*============ Merchandiser End here========================*/

    /*============Region start here========================*/
    Route::resource('regions', 'RegionsController',
        ['except' => ['show']]);

    Route::get('regions/download', [
        'as' => 'regions.download',
        'uses' => 'RegionsController@download',
    ]);

    /*============Region end here========================*/

    /*
    ============staging start here========================
    */
    Route::get('stages/{modules}', [
        'as' => 'stages.index',
        'uses' => 'StagingsController@index',
    ]);
    Route::get('stages/{modules}/create', [
        'as' => 'stages.create',
        'uses' => 'StagingsController@create',
    ]);
    Route::post('stages/{modules}', [
        'as' => 'stages.store',
        'uses' => 'StagingsController@store',
    ]);
    Route::get('stages/{modules}/edit/{stage}', [
        'as' => 'stages.edit',
        'uses' => 'StagingsController@edit',
    ]);
    Route::put('stages/{modules}/{stage}', [
        'as' => 'stages.update',
        'uses' => 'StagingsController@update',
    ]);
    Route::delete('stages/{modules}/{stages}', [
        'as' => 'stages.destroy',
        'uses' => 'StagingsController@destroy',
    ]);
    Route::delete('stage-untag/{modules}/{stageDetail}/{stage}', [
        'as' => 'stage.details.untag',
        'uses' => 'StagingsController@untag',
    ]);

    Route::any('stage-sorting/{modules}', [
        'as' => 'stages.sort',
        'uses' => 'StagingsController@sort',
    ]);
    /*
    ============staging end here========================
     */
    
       
});