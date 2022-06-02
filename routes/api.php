<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\UserController;
use App\Http\Controllers\UserAPIController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\StudentPerformanceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayTypeCategoryController;
use App\Http\Controllers\PayPriceTypeController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\MasterLeadsController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InventoryController;

use App\Http\Controllers\AppAPIController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\WebApiController;
use App\Http\Controllers\RenewalWebController;

use App\Http\Controllers\RequestController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\InventoryReportController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SMSTemplateController;
use App\Http\Controllers\EmailTemplateController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([], function(){

    Route::post('/getAge',[LeadsController::class, 'getAge']);
    Route::post('/checkLogin',[UserController::class, 'checkLogin']);
    
    Route::get('/get-city-list',[GeneralController::class, 'getCityListData']);

    Route::get('/states',[GeneralController::class, 'getStates']);
    Route::get('/cities/{state_id}',[GeneralController::class, 'getCities']);

    Route::post('/get-state-city-center-data',[GeneralController::class, 'get_state_city_center_data']);
    Route::post('/get-locations',[GeneralController::class, 'get_state_city_center_data']);

    Route::group(["prefix"=>"upload"], function(){
        Route::post('/photo',[GeneralController::class, 'uploadPhoto']);
    });

    Route::group(["prefix"=>"student"], function(){
        
        Route::post('/get-list',[StudentController::class, 'studentList']);
        Route::get('/get-info/{id}',[StudentController::class, 'getStudentInfo']);
        Route::post('/get-document-type',[StudentController::class, 'getDocType']);
        Route::post('/get-inactive-reason',[StudentController::class, 'getInactiveReason']);

        Route::post('/details',[StudentController::class, 'studentDetails']);
        
        // Route::post('/get-student-details-for-update',[StudentController::class, 'get_update_student']);
        
        Route::post('/edit-student',[StudentController::class, 'editStudent']);
        Route::post('/delete-student',[StudentController::class, 'deleteStudent']);

        Route::post('/group-change',[StudentController::class, 'groupChange']);
        Route::post('/save-inactive',[StudentController::class, 'saveInactive']);
        Route::post('/delete-inactive',[StudentController::class, 'deleteInactive']);
        Route::post('/save-injury',[StudentController::class, 'saveInjury']);
        Route::post('/delete-injury',[StudentController::class, 'deleteInjury']);
        Route::post('/save-documents',[StudentController::class, 'saveDocuments']);
        Route::post('/delete-document',[StudentController::class, 'deleteDocument']); 

        Route::post('/add-student',[StudentController::class, 'add_student_data']);
        Route::post('/change-profile-pic',[StudentController::class, 'changeProfilePicture']);
        
        // Route::post('/getfull-details',[StudentController::class, 'student_full_detail']);
        // Route::post('/delete-group-shift',[StudentController::class, 'deleteGroupShift']); 


        Route::group(["prefix"=>"performance"], function(){
            Route::post('/students',[StudentPerformanceController::class, 'getStudents']);
            Route::post('/student-record',[StudentPerformanceController::class, 'getStudentRecord']);

            Route::post('/save-score',[StudentPerformanceController::class, 'saveScore']);
            Route::post('/update-score',[StudentPerformanceController::class, 'updateScore']);
            Route::post('/add-session',[StudentPerformanceController::class, 'addSession']);
            Route::get('/get-session-list',[StudentPerformanceController::class, 'getSessionList']);
            Route::post('/delete-session',[StudentPerformanceController::class, 'deleteSession']);
        });

        Route::group(["prefix"=>"attendance"], function(){
            Route::post('/',[AttendanceController::class, 'init']);
            Route::post('/save-attendance',[AttendanceController::class, 'saveAttendance']);
        });

        Route::group(["prefix"=>"payment"], function(){
            Route::post('/init',[PaymentController::class, 'paymentInit']);
            Route::get('/get-type/{sport_id}',[PaymentController::class, 'getPayType']);
            Route::post('/get-amount',[PaymentController::class, 'getAmount']);
            Route::post('/save-payment',[PaymentController::class, 'savePayment']);
            Route::post('/edit-payment',[PaymentController::class, 'editPayment']);
            Route::get('/view-payment/{payment_id}',[PaymentController::class, 'viewPayment']);

            Route::post('/get-coupons',[PaymentController::class, 'getCoupons']);
            Route::post('/apply-coupon',[PaymentController::class, 'applyCoupon']);
        });

        Route::group(["prefix"=>"subscription"], function(){
            Route::get('/view/{id}',[PaymentController::class, 'subscriptionDetails']);
            Route::post('/save',[PaymentController::class, 'subscriptionAdd']);
            Route::post('/approve',[PaymentController::class, 'approvePauseRequest']);
            Route::post('/delete',[PaymentController::class, 'deletePauseRequest']);
        });
    });

    Route::group(["prefix"=>"payments"], function(){
        Route::post('/',[PaymentController::class, 'paymentList']);
    });

    Route::group(["prefix"=>"users"], function(){

        Route::get('/get-roles',[UserAPIController::class, 'getRoles']);
        Route::post('/list',[UserAPIController::class, 'getUsers']);
        Route::post('/delete',[UserAPIController::class, 'deleteUser']);

        Route::get('/edit/{user_id}',[UserAPIController::class, 'editUser']);
        Route::post('/save',[UserAPIController::class, 'saveUser']);
        
        
        Route::post('/access-rights-loc',[UserAPIController::class, 'accessRightsLocation']);
        Route::post('/access-rights-loc/add',[UserAPIController::class, 'addAccessRightsLocation']);
        Route::post('/access-rights-loc/delete',[UserAPIController::class, 'deleteAccessRightsLocation']);
        Route::post('/access-rights-loc/copy',[UserAPIController::class, 'copyAccessRightsLocation']);

        Route::group(["prefix"=>"attendance"], function(){
            Route::post('/',[AttendanceController::class, 'initStaff']);
            Route::post('/save-attendance',[AttendanceController::class, 'saveStaffAttendance']);
        });

        Route::group(["prefix"=>"roles"], function(){
            Route::post('/list',[UserAPIController::class, 'listRoles']);
            Route::post('/add',[UserAPIController::class, 'addRoles']);
            Route::post('/delete',[UserAPIController::class, 'deleteRoles']);
        });

        // Route::group(["prefix"=>"access-rights"], function(){
        //  Route::post('list',[UserController::class, 'getRightsListForRoles']);
        //  Route::post('add',[UserController::class, 'addAccessRights']);
        //  Route::post('update',[UserController::class, 'updateAccessRights']);
        //  Route::post('delete',[UserController::class, 'deleteAccessRights']);
            
        // });

    });

    Route::group(["prefix"=>"centers"], function(){
        Route::get('/params',[CenterController::class, 'getCentersParams']);

        Route::post('/list',[CenterController::class, 'getCentersListData']);
        Route::post('/add',[CenterController::class, 'createNewCenter']);

        Route::post('/edit',[CenterController::class, 'editCenter']);
        Route::get('/images/{center_id}',[CenterController::class, 'images']);
        Route::get('/groups/{center_id}',[CenterController::class, 'groups']);
        Route::post('/add-group',[CenterController::class, 'addGroup']);
        Route::get('/edit-group/{group_id}',[CenterController::class, 'editGroup']);

        Route::post('/remove-image',[CenterController::class, 'removeImage']);
        Route::post('/save-image',[CenterController::class, 'saveImage']);

        Route::post('/update/',[CenterController::class, 'updateCenter']);
        
        Route::get('/delete-center/{id}',[CenterController::class, 'deleteCenter']);
        Route::post('/group/schedule',[CenterController::class, 'groupSchedule']);
        Route::post('/addGroupTiming',[CenterController::class, 'addGroupTiming']);
        Route::post('/deleteTiming',[CenterController::class, 'deleteTiming']);
        Route::post('/add-contact-person',[CenterController::class, 'addContactPerson']);

        });

    Route::group(["prefix"=>"groups"],function(){
        Route::post('/init',[GroupController::class, 'init']);
        Route::post('/add',[GroupController::class, 'add']);
        Route::post('/delete',[GroupController::class, 'delete']);
    });

    Route::group(["prefix"=>"pay-type-price"], function(){
        Route::post('/get-pay-type-data',[PayPriceTypeController::class, 'getPayType']);  
        Route::post('/list',[PayPriceTypeController::class, 'getList']);  
        Route::post('/add',[PayPriceTypeController::class, 'add']);   
        Route::post('/delete',[PayPriceTypeController::class, 'delete']); 
        Route::post('update',[PayPriceTypeController::class, 'update']);  
    });

    Route::group(["prefix"=>"pay-type-category"], function(){
        Route::post('/list',[PayTypeCategoryController::class, 'getList']);   
        Route::post('/add-category',[PayTypeCategoryController::class, 'addCategory']);   
        Route::post('/disable-category',[PayTypeCategoryController::class, 'disableCategory']);   
        Route::post('/delete-category',[PayTypeCategoryController::class, 'deleteCategory']); 

        Route::post('/add',[PayTypeCategoryController::class, 'add']);    
        Route::post('/update',[PayTypeCategoryController::class, 'add']); 
        Route::post('/delete',[PayTypeCategoryController::class, 'delete']);  
        
    });

    Route::group(["prefix"=>"coupons"], function(){
        Route::post('/get-coupons-list',[CouponController::class, 'getCouponList']);
        Route::post('/add',[CouponController::class, 'addCoupon']);
        Route::get('/delete-coupon/{id}',[CouponController::class, 'deleteCoupon']);
        Route::post('/add-availibility',[CouponController::class, 'addAvailibility']);
        Route::get('/delete-availibility/{id}',[CouponController::class, 'deleteAvailibility']);
    });

    Route::group(["prefix"=>"inventory"], function(){
        Route::post('/items-list',[InventoryController::class, 'itemsList']);
        Route::post('/get-units',[InventoryController::class, 'getUnits']);   
        Route::post('/add-items',[InventoryController::class, 'addItems']);   
        Route::post('/delete-items',[InventoryController::class, 'deleteItems']); 
    });

    Route::group(["prefix"=>"events"], function(){
        Route::post('/getList',[EventsController::class, 'getList']); 
        Route::post('/init',[EventsController::class, 'init']);   
        Route::post('/add',[EventsController::class, 'add']); 
        Route::post('/uploadFile',[EventsController::class, 'uploadFile']);
        Route::post('/upload-galary-image',[EventsController::class, 'uploadGalaryImage']);
    });

    Route::group(["prefix"=>"leads"], function(){
        Route::post('/',[LeadsController::class, 'init']);
        Route::get('/params',[LeadsController::class, 'parameters']);
        Route::post('/store',[LeadsController::class, 'storeLead']);  
        Route::post('/history/{lead_id}',[LeadsController::class, 'history']);

        // Route::post('/getfull-details',[LeadsController::class, 'getLeadsFullDetails']);
        // Route::post('/filter',[LeadsController::class, 'filter']); 
        Route::post('/autoFillByPincode',[LeadsController::class, 'autoFillByPincode']);  
        
        Route::post('/getCampaignId',[LeadsController::class, 'getCampaignId']);  
        
        Route::post('/updateLead',[LeadsController::class, 'updateLead']);    
        Route::post('/addNote',[LeadsController::class, 'addNote']);
        Route::post('/transferLead',[LeadsController::class, 'transferLead']);
        Route::post('check_advance_options',[LeadsController::class, 'checkAdvanceOptions']);
        Route::post('/bulk-lead',[LeadsController::class, 'bulk_upload']);
        Route::post('selectAllFilterLeads',[LeadsController::class, 'selectAllFilterLeads']);

    });

    Route::group(["prefix"=>"master-leads"], function(){
        Route::get('/leads-for',[MasterLeadsController::class, 'leadsFor']);
        Route::get('/lead-status',[MasterLeadsController::class, 'leadsStatus']);
        Route::get('/lead-reasons',[MasterLeadsController::class, 'leadsReasons']);
        Route::get('/lead-sources',[MasterLeadsController::class, 'leadsSources']);
        Route::post('/lead-for-store',[MasterLeadsController::class, 'leadForStore']);
        Route::get('/lead-for-delete/{lead_for_id}',[MasterLeadsController::class, 'leadsForDelete']);
        Route::post('/lead-status',[MasterLeadsController::class, 'leadStatusStore']);
        Route::post('/lead-reason',[MasterLeadsController::class, 'leadReasonStore']);
        Route::get('/lead-reason-delete/{lead_for_id}',[MasterLeadsController::class, 'leadsReasonDelete']);
        Route::post('/lead-source',[MasterLeadsController::class, 'leadSourceStore']);
        Route::get('/lead-source-delete/{lead_source_id}',[MasterLeadsController::class, 'leadsSourceDelete']);
    }); 


    Route::group(["prefix"=>"accounts"], function(){
        Route::post('/list',[AccountsController::class, 'listData']); 
        Route::post('/save',[AccountsController::class, 'save']); 
        Route::post('/delete',[AccountsController::class, 'delete']); 
    });

    Route::group(["prefix"=>"clients"], function(){
        Route::post('/list',[ClientsController::class, 'getList']);
        Route::post('/save',[ClientsController::class, 'save']);
        Route::post('/delete',[ClientsController::class, 'delete']);
    });

    Route::group(["prefix"=>"reports"], function(){
        Route::post('/revenueReport',[ReportController::class, 'revenueReport']);
        Route::post('/center/revenue',[ReportController::class, 'revenue']);
    });

    Route::group(["prefix"=>"sales-dashboard"],function(){
        Route::post('/init',[ReportController::class, 'report']);
    });

    Route::group(["prefix"=>"city"], function(){
        Route::post('/list',[CityController::class, 'getCityList']);
        Route::post('/save',[CityController::class, 'saveCity']);
        Route::post('/delete',[CityController::class, 'deleteCity']);
    });

    Route::group(["prefix"=>"parameters"], function(){
        Route::get('/get-parameters/{sport_id}',[ParameterController::class, 'parameters']);
        Route::post('/save-category',[ParameterController::class, 'saveCategory']);
        Route::get('/delete-category/{id}',[ParameterController::class, 'deleteCategory']);
        Route::post('/save-attribute',[ParameterController::class, 'saveAttribute']);
        Route::get('/delete-attribute/{id}',[ParameterController::class, 'deleteAttribute']);
    });

});


Route::get('/get-state-city-center',[WebApiController::class, 'stateCityCenter']);
Route::get('/get-state-city/{state_id}',[WebApiController::class, 'stateCity']);
Route::group(["prefix"=>"registrations"], function(){
    Route::post('/store',[WebApiController::class, 'store']);
    Route::post('/store-demo',[WebApiController::class, 'storeDemo']);
    Route::post('/store-lead',[WebApiController::class, 'storeLead']);
    Route::get('/get-schedule/{group_id}',[WebApiController::class, 'getSchedule']);
});

Route::group(["prefix"=>"subscriptions"], function(){
    Route::post('/get-payment-options',[SubscriptionController::class, 'getPaymentOptions']);
    Route::post('/get-payment-items',[SubscriptionController::class, 'paymentItems']);
    Route::post('/create-order',[SubscriptionController::class, 'createOrder']);
    Route::post('/process-order',[SubscriptionController::class, 'createOrder']);
});


Route::group(["prefix"=>"renewal"], function(){
    Route::post('/search',[RenewalWebController::class, 'searchStudent']);
});



Route::group(["prefix"=>"inventory"], function(){

    Route::post('/get-items',[InventoryController::class,'itemsList']);
    Route::post('/save-items',[InventoryController::class,'saveItem']);
    Route::get('/delete-items/{id}',[InventoryController::class,'deleteItems']); 

    Route::group(["prefix"=>"companies"], function(){
        Route::post('/get-companies',[CompanyController::class,'companiesList']);
        Route::post('/save-company',[CompanyController::class,'saveCompany']);
        Route::get('/delete-companies/{id}',[CompanyController::class,'deleteCompanies']);
    });

    Route::group(["prefix"=>"current-stock"], function(){
        Route::post('/get-stock',[StockController::class,'total_stock']);
    });

    Route::group(["prefix"=>"request"], function(){
        Route::post('/get-companies',[RequestController::class,'companiesList']);
        Route::post('/get-request',[RequestController::class,'requestList']);
        Route::post('/upload-document',[RequestController::class,'uploadDocument']);
        Route::post('/save-request',[RequestController::class,'saveRequest']);  
        Route::get('/request-data/{id?}',[RequestController::class,'requestData']);
        Route::get('/delete-data/{id}',[RequestController::class,'deleteData']);
        Route::post('/all-items',[RequestController::class,'ItemsList']);
        Route::get('/view-data/{id}',[RequestController::class,'viewData']);
        Route::post('/approve-or-reject',[RequestController::class,'approveOrReject']);
    });
});


    Route::group(["prefix"=>"communications"], function(){

        // Route::group(["prefix"=>"send-message"], function(){
        //     Route::get('/',[CommunicationController::class,'index']);
        //     Route::post('/init',[CommunicationController::class,'init']);
        //     Route::post('/listing',[CommunicationController::class,'listing']);
        //     Route::post('/comm_students',[CommunicationController::class,'comm_students']);
        //     Route::post("getStudents",[CommunicationController::class,'getStudents']);
        //     Route::post("postMessage",[CommunicationController::class,'postMessage']);
        // }); 

        Route::group(["prefix"=>"sms-template"], function(){
            Route::get('/init',[SMSTemplateController::class,'init']);
            Route::post('/store',[SMSTemplateController::class,'store']);
            Route::get('/delete/{id}',[SMSTemplateController::class,'delete']);
        }); 

        Route::group(["prefix"=>"email-template"], function(){
            Route::get('/init',[EmailTemplateController::class,'init']);
            Route::post('/store',[EmailTemplateController::class,'store']);
            Route::get('/delete/{id}',[EmailTemplateController::class,'delete']);
        }); 
    }); 




Route::group(["prefix"=>"app"], function(){
    Route::post('/login',[AppAPIController::class, 'login']);
    Route::get('/user/profile',[AppAPIController::class, 'getUser']);
    Route::get('/academy-data',[AppAPIController::class, 'academyData']);
    Route::post('/check-location',[AppAPIController::class, 'getLocation']);
    Route::post('/user-profile/upload/{user_id}',[AppAPIController::class, 'uploadProfile']);
    Route::post('/user-update',[AppAPIController::class, 'updateUser']);
    Route::get('/coach/attendance-list',[AppAPIController::class, 'coachAttendList']);
    Route::post('/user/update-password/{user_id}',[AppAPIController::class, 'changePassword']);

    Route::group(["prefix"=>"events"], function(){
        Route::post('/list',[AppAPIController::class, 'getEventsList']);
        Route::get('/centers-list',[AppAPIController::class, 'getCenterList']);
        Route::get('/groups-list/{center_id}',[AppAPIController::class, 'getGroupList']);
        Route::get('/cancel',[AppAPIController::class, 'getGroupList']);
        Route::get('/fetct-reason',[AppAPIController::class, 'getReasons']);
        Route::post('/save-cancelled',[AppAPIController::class, 'cancelEvent']);
        Route::post('/players',[AppAPIController::class, 'eventPlayers']);
        Route::post('/save-player-attendance',[AppAPIController::class, 'savePlayerAttendance']);
        Route::post('/guest-player-save',[AppAPIController::class, 'saveGuestPlayer']);
        Route::get('/guest-student-remove/{student_id}',[AppAPIController::class, 'guestStudentRemove']);

    });

    Route::group(["prefix"=>"student"], function(){
        Route::post('/get-all-list',[AppAPIController::class, 'allStudentList']);
        Route::post('/info',[AppAPIController::class, 'studentInfo']);
        Route::post('/save-tags',[AppAPIController::class, 'saveTags']);
        Route::get('/edit/{student_id}',[AppAPIController::class, 'studentEdit']);
        Route::get('/state-list',[AppAPIController::class, 'getStateList']);
        Route::get('/city-list/{state_id}',[AppAPIController::class, 'getCityList']);
        Route::post('/upload-student-profile/{student_id}',[AppAPIController::class,'uploadStuPic']);
        Route::post('/save-student/{student_id}',[AppAPIController::class,'saveStudent']); 
        Route::post('/group-shift/{student_id}',[AppAPIController::class, 'groupShift']);
        Route::post('/view-attendance/{student_id}',[AppAPIController::class, 'studAttndList']);
        Route::get('/get-reasion',[AppAPIController::class, 'getInActiveReasons']);
        Route::post('/mark-inactive',[AppAPIController::class, 'markInActive']);
        Route::get('/groups-details/{center_id}',[AppAPIController::class, 'groupDetail']);
        
    });

    Route::group(["prefix"=>"performance"], function(){
        Route::get('/get-student-list/{group_id}',[AppAPIController::class, 'performStudentList']);
        Route::get('/categories/{student_id}',[AppAPIController::class, 'performCategories']);
        Route::post('/save-marks',[AppAPIController::class, 'saveMarks']);
        
        
    });

});

