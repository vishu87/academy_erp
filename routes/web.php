<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentPerformanceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayTypeCategoryController;
use App\Http\Controllers\PayPriceTypeController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\MasterLeadsController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\SMSTemplateController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\SettingsController;



Route::get('/', [UserController::class,'login'])->name("login");
Route::post('/login', [UserController::class, 'postLogin']);
Route::get('/logout', [UserController::class, 'logout']);

Route::post('/uploads/file', [GeneralController::class, 'uploadFile']);

Route::get('/renewals', [WebController::class,'renewals']);
Route::get('/demo-schedule', [WebController::class,'demoShedule']);
Route::get('/lead/{type}', [WebController::class,'lead']);
Route::get('/registrations',[WebController::class,'registrations']);

Route::group(["before"=>"auth","middleware"=>["auth"]], function(){

    Route::get('/payment-details', [UserController::class, 'paymentDetails']);
    Route::get('/dashboard', [UserController::class, 'dashboard']);

    Route::get('/switch/dashboard/{type}', [UserController::class, 'switchDashboard']);

    Route::group(["prefix"=>"users"], function(){
        Route::get('/view',[UserController::class, 'viewUsers']);
        Route::get('/add',[UserController::class, 'viewAddUser']);
        Route::get('/user-roles',[UserController::class, 'viewUserRoles']);
        Route::get('/user-rights',[UserController::class, 'viewAccessRights']);
        Route::get('/edit/{id}',[UserController::class, 'editUser']);
        Route::get('/staff-attendance',[UserController::class, 'staffAttendance']);
    });

    Route::group(["prefix"=>"students"], function(){
        
        Route::get('/',[StudentController::class, 'students']);
        Route::get('/student_details/{id}',[StudentController::class, 'student_personal_detail']);
        Route::get('/add-student',[StudentController::class, 'add_student']);
        Route::get('/edit-student/{id}',[StudentController::class, 'edit_student']);
        Route::get('/payment_receipt/{history_id}/{student_id}',[StudentController::class, 'paymentReceipt']);

        // Route::post('/save-payment-history','StudentController@save_payment_history');

        Route::group(["prefix"=>"performance"], function(){
            Route::get('/',[StudentPerformanceController::class,'index']);
            Route::get('/sessions',[StudentPerformanceController::class,'session']);
        });

        Route::group(["prefix"=>"attendance"], function(){
            Route::get('/',[AttendanceController::class,'index']);
        });

    });

    Route::group(["prefix"=>"payments"], function(){
        Route::get('/',[PaymentController::class,'payment_list']);
    });

    Route::group(["prefix"=>"pay-type-price"], function(){
        Route::get('/',[PayPriceTypeController::class,'type_price_list']);   
    });

    Route::group(["prefix"=>"pay-type-category"], function(){
        Route::get('/',[PayTypeCategoryController::class,'categoryList']);   
    });

    Route::group(["prefix"=>"inventory"], function(){

        Route::get('/item',[InventoryController::class,'index']);
        Route::post('/get-items',[InventoryController::class,'itemsList']);
        Route::post('/save-items',[InventoryController::class,'saveItem']);
        Route::get('/delete-items/{id}',[InventoryController::class,'deleteItems']); 

        Route::group(["prefix"=>"companies"], function(){
            Route::get('/',[CompanyController::class,'index']);
            Route::post('/get-companies',[CompanyController::class,'companiesList']);
            Route::post('/save-company',[CompanyController::class,'saveCompany']);
            Route::get('/delete-companies/{id}',[CompanyController::class,'deleteCompanies']);
        });

        Route::group(["prefix"=>"current-stock"], function(){
            Route::get('/',[StockController::class,'index']);
            Route::post('/get-stock',[StockController::class,'total_stock']);
        });

        Route::group(["prefix"=>"inventory-report"], function(){
            Route::get('/',[InventoryReportController::class,'index']);
        });

        Route::group(["prefix"=>"request"], function(){
            Route::get('/',[RequestController::class,'index']);
            Route::post('/get-companies',[RequestController::class,'companiesList']);
            Route::post('/get-request',[RequestController::class,'requestList']);
            Route::post('/upload-document',[RequestController::class,'uploadDocument']);
            Route::post('/save-request',[RequestController::class,'saveRequest']);  
            Route::get('/add-request/{id}',[RequestController::class,'addRequest']);
            Route::get('/request-data/{id}',[RequestController::class,'requestData']);
            Route::get('/delete-data/{id}',[RequestController::class,'deleteData']);
            Route::post('/all-items',[RequestController::class,'ItemsList']);
            Route::get('/view-data/{id}',[RequestController::class,'viewData']);
        });
    });



    Route::group(["prefix"=>"events"], function(){
        Route::get('/',[EventsController::class,'IndexPage']);   
        Route::get('/add/{id}',[EventsController::class,'addPage']); 
    });

    Route::group(["prefix"=>"leads"], function(){
        Route::get('/',[LeadsController::class,'getIndexPage']);
    }); 

    Route::group(["prefix"=>"master-leads"], function(){
        Route::get('/',[MasterLeadsController::class,'index']);
    }); 

    Route::group(["prefix"=>"city"], function(){
        Route::get('/',[CityController::class,'getCityPage']);
    });

    Route::group(["prefix"=>"centers"], function(){
        Route::get('/',[CenterController::class,'getCentersList']);
        Route::get('/add/{center_id}',[CenterController::class,'addCenter']);
        Route::get('/edit/{center_id}',[CenterController::class,'addCenter']);
    });

    Route::group(["prefix"=>"tax-settings"], function(){
        Route::get('/',[AccountsController::class,'index']);
    });

    Route::group(["prefix"=>"clients"], function(){
        Route::get('/',[ClientsController::class,'index']);
    });

    Route::group(["prefix"=>"upload"], function(){
        Route::post('/photo',[GeneralController::class,'uploadPhoto']);
    });

    Route::group(["prefix"=>"coupons"], function(){
        Route::get('/',[CouponController::class,'coupons']);
    });

    Route::group(["prefix"=>"settings"], function(){
        Route::get('/',[SettingsController::class,'index']);
    });

    Route::group(["prefix"=>"reports"], function(){
        Route::get('/center',[ReportController::class,'centerIndex']);
        Route::get('/sales',[ReportController::class,'salesIndex']);
        Route::get('/students',[ReportController::class,'studentsIndex']);
        Route::get('/leads',[ReportController::class,'leadsIndex']);
    });

    Route::group(["prefix"=>"parameters"], function(){
        Route::get('/',[ParameterController::class,'index']);
    }); 

    Route::group(["prefix"=>"communications"], function(){

        Route::get('/',[CommunicationController::class,'communication']);

        Route::group(["prefix"=>"send-message"], function(){
            Route::get('/',[CommunicationController::class,'index']);
            Route::post('/init',[CommunicationController::class,'init']);
            Route::post('/listing',[CommunicationController::class,'listing']);
            Route::post('/comm_students',[CommunicationController::class,'comm_students']);
            Route::post("getStudents",[CommunicationController::class,'getStudents']);
            Route::post("postMessage",[CommunicationController::class,'postMessage']);
        }); 

        Route::group(["prefix"=>"sms-template"], function(){
            Route::get('/',[SMSTemplateController::class,'index']);
            Route::get('/init',[SMSTemplateController::class,'init']);
            Route::post('/store',[SMSTemplateController::class,'store']);
            Route::get('/delete/{id}',[SMSTemplateController::class,'delete']);
        }); 

        Route::group(["prefix"=>"email-template"], function(){
            Route::get('/',[EmailTemplateController::class,'index']);
            Route::get('/init',[EmailTemplateController::class,'init']);
            Route::post('/store',[EmailTemplateController::class,'store']);
            Route::get('/delete/{id}',[EmailTemplateController::class,'delete']);
        }); 


    }); 


});
