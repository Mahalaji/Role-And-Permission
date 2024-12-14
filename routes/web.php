<?php
  
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\blogs;
use App\Http\Controllers\newss;
use App\Http\Controllers\pagess;
use App\Http\Controllers\companies;



   
Route::get('/', function () {
    return view('welcome');
});
  
Auth::routes();
  
Route::get('/home', [HomeController::class, 'index'])->name('home');
  
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);



Route::view('/blog','blog.index');
Route::post('/getBlogsAjax', [blogs::class, 'getBlogsAjax']);
Route::get('/blog/add', [blogs::class, 'title']);
Route::post('/addblog',[blogs::class,'addblog']);
Route::get('/blog/edit/{id}', [blogs::class, 'edit']);
Route::post('/update',[blogs::class,'update']);
Route::post('/destory/{id}', [blogs::class, 'destory']);
Route::view('/blogcategory','blog.category_index');
Route::post('/getBlogsCategoryAjax', [blogs::class, 'getBlogsCategoryAjax']);
Route::view('/blogcategory/add','blog.category_create');
Route::post('/addcategery',[blogs::class,'addcategery']);
Route::post('/destorycategory/{id}', [blogs::class, 'destorycategory']);
Route::get('/blogcategory/edit/{id}', [blogs::class, 'editcategory']);
Route::post('/updatecategery',[blogs::class,'updateCategory']);

Route::get('/news/add', [newss::class, 'title']);
Route::post('/createnews',[newss::class,'createnews']);
Route::view('/news','news.index');
Route::post('/getNewsAjax', [newss::class, 'getNewsAjax']);
Route::get('/news/edit/{id}', [newss::class, 'editnews']);
Route::post('/updatenews',[newss::class,'updatenews']);
Route::post('/destorynews/{id}', [newss::class, 'destorynews']);
Route::view('/newscategory','news.category_index');
Route::post('/getNewsCategoryAjax', [newss::class, 'getNewsCategoryAjax']);
Route::view('/newscategory/add','news.category_create');
Route::post('/createnewscategory',[newss::class,'createnewscategory']);
Route::get('/newscategory/edit/{id}', [newss::class, 'editnewscategory']);
Route::post('/updatenewscategery',[newss::class,'updatenewscategery']);
Route::post('/destorynewscategory/{id}', [newss::class, 'destorynewscategory']);


Route::view('/pages','pages.index');
Route::post('/getPagesAjax', [pagess::class, 'getPagesAjax']);
Route::get('/editpages/{id}', [pagess::class, 'editpages']);
Route::post('/updatepages',[pagess::class,'updatepages']);
Route::view('/page/add','pages.create');
Route::post('/createpages',[pagess::class,'createpages']);
Route::post('/destorypages/{id}', [pagess::class, 'destorypages']);

Route::view('/company','company.index');
Route::post('/getCompanyAjax', [companies::class, 'getCompanyAjax']);
Route::view('/company/add','company.create');
Route::post('/createcompany',[companies::class,'createcompany']);
Route::get('/company/edit/{id}', [companies::class, 'editcompany']);
Route::post('/updatecompany',[companies::class,'updatecompany']);
Route::post('/destorycompany/{id}', [companies::class, 'destorycompany']);
Route::post('/getCompanyaddress', [companies::class, 'getCompanyaddress']);
Route::post('/deleteCompanyAddress', [companies::class, 'deleteaddress']);
Route::post('/saveCompanyAddress', [companies::class, 'saveCompanyAddress']);
});
