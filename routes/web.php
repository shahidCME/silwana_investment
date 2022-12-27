<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\admin\Login;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\admin\Dashboard;
use App\Http\Controllers\admin\SalesPerson;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\SchemaController;
use App\Http\Controllers\admin\InvestmentController;
use App\Http\Controllers\admin\ReportController;
use App\Http\Controllers\admin\ChangeController;
use App\Http\Controllers\admin\FinancePersonController;
use App\Http\Controllers\admin\ApproverController;
use App\Http\Controllers\MyinvestmentController;
use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\CheckUserRole;


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

Route::group(['middleware' => AdminAuth::Class ], function () {
    Route::get('/dashboard', [Dashboard::class, 'index']);
    // Start Sales Module
    Route::get('/salesPerson', [SalesPerson::class, 'index']);
    Route::get('/add_sales_person', [SalesPerson::class, 'add']);
    Route::post('/add_sales_person', [SalesPerson::class, 'add']);
    Route::post('/getSalesPersonDataTable', [SalesPerson::class, 'getSalesPersonDataTable']);
    Route::get('/salesPersonEdit/{id}', [SalesPerson::class, 'edit']);
    Route::post('/salesPersonEdit', [SalesPerson::class, 'edit']);
    Route::get('/salesPersonDelete/{id}', [SalesPerson::class, 'delete']);
    Route::get('/salesStatus/{id}', [SalesPerson::class, 'salesStatus']);

    // End Sales Module

    // Start FinancePerson Module
    Route::get('/financePerson', [FinancePersonController::class, 'index']);
    Route::get('/addFinancePerson', [FinancePersonController::class, 'add']);
    Route::post('/addFinancePerson', [FinancePersonController::class, 'add']);
    Route::post('/getFinancePersonDataTable', [FinancePersonController::class, 'getFinancePersonDataTable']);
    Route::get('/financePersonEdit/{id}', [FinancePersonController::class, 'edit']);
    Route::post('/financePersonEdit', [FinancePersonController::class, 'edit']);
    Route::get('/financePersonDelete/{id}', [FinancePersonController::class, 'delete']);
    Route::get('/financeStatus/{id}', [FinancePersonController::class, 'financeStatus']);
    // End FinancePerson Module
    
    // Start Approver Module
    Route::get('/approver', [ApproverController::class, 'index']);
    Route::get('/addApprover', [ApproverController::class, 'add']);
    Route::post('/addApprover', [ApproverController::class, 'add']);
    Route::post('/getApproverDataTable', [ApproverController::class, 'getApproverDataTable']);
    Route::get('/approverEdit/{id}', [ApproverController::class, 'edit']);
    Route::post('/approverEdit', [ApproverController::class, 'edit']);
    Route::get('/approverDelete/{id}', [ApproverController::class, 'delete']);
    Route::get('/approverStatus/{id}', [ApproverController::class, 'approverStatus']);
    // End Approver Module

    // Start Customer Module
    Route::get('/customer', [UserController::class, 'index']);
    Route::get('/addCustomer', [UserController::class, 'add']);
    Route::post('/addCustomer', [UserController::class, 'add']);
    Route::post('/getCustomerDataTable', [UserController::class, 'getCustomerDataTable']);
    Route::get('/customerDelete/{id}', [UserController::class, 'delete']);
    Route::get('/customerEdit/{id}', [UserController::class, 'edit']);
    Route::post('/customerEdit', [UserController::class, 'edit']);
    Route::get('/customerStatus/{id}', [UserController::class, 'customerStatus']);
    Route::get('/kyc_doc/{id}', [UserController::class, 'kyc_doc']);
    Route::get('/gerNationality', [UserController::class, 'gerNationality']);
    // End start Customer Module
    
    // Start Schema Module
    Route::get('/Schema', [SchemaController::class, 'index']);
    Route::get('/addSchema', [SchemaController::class, 'add']);
    Route::post('/addSchema', [SchemaController::class, 'add']);
    Route::post('/getSchemaDataTable', [SchemaController::class, 'getSchemaDataTable']);
    Route::get('/SchemaDelete/{id}', [SchemaController::class, 'delete']);
    Route::get('/SchemaEdit/{id}', [SchemaController::class, 'edit']);
    Route::post('/SchemaEdit', [SchemaController::class, 'edit']);
    // End Schema Module
    
    // Start Investnebt Module
    Route::get('/Investment', [InvestmentController::class, 'index']);
    Route::get('/addInvestment', [InvestmentController::class, 'add']);
    Route::post('/addInvestment', [InvestmentController::class, 'add']);
    Route::post('/getInvestmentDataTable', [InvestmentController::class, 'getInvestmentDataTable']);
    Route::get('/InvestmentDelete/{id}', [InvestmentController::class, 'delete']);
    Route::get('/InvestmentEdit/{id}', [InvestmentController::class, 'edit']);
    Route::post('/InvestmentEdit', [InvestmentController::class, 'edit']);
    Route::get('/InvestmentDocument/{id}', [InvestmentController::class, 'investmentDocument']);
    Route::post('/contractCancel', [InvestmentController::class, 'contractCancel']);
    Route::post('/payment_reciept', [InvestmentController::class, 'payment_reciept']);
    Route::get('/cancelledInvestment', [InvestmentController::class, 'cancelledInvestment']);
    Route::get('/roi/{id}', [InvestmentController::class, 'getRoi']);
    Route::post('/roi/{id}', [InvestmentController::class, 'getRoi']);
    Route::get('/cancelledRoi/{id}', [InvestmentController::class, 'cancelledRoi']);
    Route::get('/contract/{id}', [InvestmentController::class, 'contract']);
    Route::post('/changeStatus', [InvestmentController::class, 'changeStatus']);

    Route::get('/payment-contract/{id}', [InvestmentController::class, 'payment_contract']);




    // Start Report Module
    Route::get('/Report', [ReportController::class, 'index']);
    Route::post('/getReportDataTable', [ReportController::class, 'getReportDataTable']);

    // End Investnebt Module
    Route::get('/profile', [ChangeController::class, 'index']);
    Route::post('/profile', [ChangeController::class, 'index']);


    Route::get('/changePassword', [ChangeController::class, 'changePassword'])->withoutMiddleware([AdminAuth::class])->middleware(['CheckUserRole']);
    Route::post('/changePassword', [ChangeController::class, 'changePassword'])->withoutMiddleware([AdminAuth::class])->middleware(['CheckUserRole']);

});

Route::get('/userDashboard', [Dashboard::class, 'user_dashboard'])->middleware(['CheckUserRole']);
Route::get('/userProfile', [ChangeController::class, 'userProfile'])->middleware(['CheckUserRole']);
Route::post('/userProfile', [ChangeController::class, 'userProfile'])->middleware(['CheckUserRole']);

// user 

Route::get('/myInvestment', [MyinvestmentController::class, 'index'])->middleware(['CheckUserRole']);
Route::post('/getUserInvestmentDataTable', [MyinvestmentController::class, 'getUserInvestmentDataTable'])->middleware(['CheckUserRole']);
Route::get('/investmentDetails/{id}', [MyinvestmentController::class, 'investmentDetails'])->middleware(['CheckUserRole']);
Route::get('/userRoi/{id}', [MyinvestmentController::class, 'userRoi'])->middleware(['CheckUserRole']);





Route::get('/', function () {
    if(Session::has('admin_login')){
        return Redirect::to(URL::to('/dashboard'));
    }
    
        $data['action'] = URL::to('login_check');
        $data['js'] = array('login');
        return view('admin.login',$data);
    });

    Route::get('/privacy-policy', function () {
            return view('privacy-policy');
        })->name('privacy-policy');
    Route::post('login_check', [Login::class, 'index']);
    Route::get('logout', [Login::class, 'logout']);
    
    Route::get('/forgetPassword'  , [Login::class, 'forgetPassword']);
    Route::post('/forgetPassword' , [Login::class, 'forgetPassword']);
    Route::get('reset-password/{token}', [Login::class, 'showResetPasswordForm'])->name('reset.password.get');
    Route::post('reset-password', [Login::class, 'submitResetPasswordForm'])->name('reset.password.post');
