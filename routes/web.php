<?php
  
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\Backend\HomeController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\ProductController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Backend\Newss;
use App\Http\Controllers\Backend\Pagess;
use App\Http\Controllers\Backend\Companies;
use App\Http\Controllers\Backend\Module;
use App\Http\Controllers\Backend\Menu;
use App\Http\Controllers\Backend\Domain;
use App\Http\Controllers\Backend\Language;
use App\Http\Controllers\Backend\Department;
use App\Http\Controllers\Backend\Designation;
use App\Http\Controllers\frontend\Dashboard;
use App\Http\Controllers\frontend\Blogfront;
use App\Http\Controllers\frontend\Newsfront;
use App\Http\Controllers\Backend\Blogs;
use App\Http\Controllers\Backend\FileManagerController;









   
Route::get('/', function () {
    return view('welcome');
});
  
Auth::routes();
  
Route::get('/home', [HomeController::class, 'index'])->name('home');
  
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class)->middleware('role:Admin');
    Route::resource('products', ProductController::class);

    Route::get('/access/{article}',[RoleController::class,'access'])->name('access');
    Route::post('/roles/{roleId}/update-access', [RoleController::class, 'updateAccess'])->name('roles.updateAccess');



//blog
Route::get('/blog', [Blogs::class, 'blogshow'])->name('blog')->middleware(['auth', 'role:Admin|Blog_Team', 'permission:blog-index']);
Route::post('/getBlogsAjax', [Blogs::class, 'getBlogsAjax']);
Route::post('/updateBlogStatus',[Blogs::class,'updateBlogStatus']);
Route::get('/blog/add', [Blogs::class, 'title'])->middleware(['auth', 'role:Admin|Blog_Team', 'permission:blog-create']);
Route::post('/addblog',[Blogs::class,'addblog']);
Route::get('/blog/edit/{id}', [Blogs::class, 'edit'])->middleware(['auth', 'role:Admin|Blog_Team', 'permission:blog-edit']);
Route::post('/update',[Blogs::class,'update']);
Route::post('/destory/{id}', [Blogs::class, 'destory'])->middleware(['auth', 'role:Admin|Blog_Team', 'permission:blog-delete']);
Route::view('/blogcategory','Backend.blog.category_index')->name('blogcategory')->middleware(['auth', 'role:Admin|Blog_Team', 'permission:blogcategory-index']);
Route::post('/getBlogsCategoryAjax', [Blogs::class, 'getBlogsCategoryAjax']);
Route::view('/blogcategory/add','Backend.blog.category_create')->middleware(['auth', 'role:Admin|Blog_Team', 'permission:blogcategory-create']);
Route::post('/addcategery',[Blogs::class,'addcategery']);
Route::post('/destorycategory/{id}', [Blogs::class, 'destorycategory'])->middleware(['auth', 'role:Admin|Blog_Team', 'permission:blogcategory-delete']);
Route::get('/blogcategory/edit/{id}', [Blogs::class, 'editcategory'])->middleware(['auth', 'role:Admin|Blog_Team', 'permission:blogcategory-edit']);
Route::post('/updatecategery',[Blogs::class,'updateCategory']);

//news
Route::get('/news/add', [Newss::class, 'title'])->middleware('role:Admin|News_Team', 'permission:news-create');
Route::post('/createnews',[Newss::class,'createnews']);
Route::view('/newss','Backend.news.index')->name('newss')->middleware('role:Admin|News_Team', 'permission:news-index');
Route::post('/getNewsAjax', [Newss::class, 'getNewsAjax']);
Route::post('/updateNewsStatus',[Newss::class,'updateNewsStatus']);
Route::get('/news/edit/{id}', [Newss::class, 'editnews'])->middleware('role:Admin|News_Team', 'permission:news-edit');
Route::post('/updatenews',[Newss::class,'updatenews']);
Route::post('/destorynews/{id}', [Newss::class, 'destorynews'])->middleware('role:Admin|News_Team', 'permission:news-delete');
Route::view('/newscategory','Backend.news.category_index')->name('newscategory')->middleware('permission:newscategory-index');
Route::post('/getNewsCategoryAjax', [Newss::class, 'getNewsCategoryAjax']);
Route::view('/newscategory/add','Backend.news.category_create')->middleware('permission:newscategory-create');
Route::post('/createnewscategory',[Newss::class,'createnewscategory']);
Route::get('/newscategory/edit/{id}', [Newss::class, 'editnewscategory'])->middleware('permission:newscategory-edit');
Route::post('/updatenewscategery',[Newss::class,'updatenewscategery']);
Route::post('/destorynewscategory/{id}', [Newss::class, 'destorynewscategory'])->middleware('permission:newscategory-delete');

//pages
Route::view('/pages','Backend.pages.index')->name('pages')->middleware('role:Admin|Page_Team','permission:page-index');
Route::post('/getPagesAjax', [Pagess::class, 'getPagesAjax']);
Route::get('/editpages/{id}', [Pagess::class, 'editpages'])->middleware('role:Admin|Page_Team','permission:page-edit');
Route::post('/updatepages',[Pagess::class,'updatepages']);
Route::view('/page/add','Backend.pages.create')->middleware('role:Admin|Page_Team','permission:page-create');
Route::post('/createpages',[Pagess::class,'createpages']);
Route::post('/destorypages/{id}', [Pagess::class, 'destorypages'])->middleware('role:Admin|Page_Team','permission:page-delete');

//company
Route::view('/company','Backend.company.index')->name('company')->middleware('role:Admin','permission:company-index');
Route::post('/getCompanyAjax', [Companies::class, 'getCompanyAjax']);
Route::view('/company/add','Backend.company.create')->middleware('role:Admin','permission:company-create');
Route::post('/createcompany',[Companies::class,'createcompany']);
Route::get('/company/edit/{id}', [Companies::class, 'editcompany'])->middleware('role:Admin','permission:company-edit');
Route::post('/updatecompany',[Companies::class,'updatecompany']);
Route::post('/destorycompany/{id}', [Companies::class, 'destorycompany'])->middleware('role:Admin','permission:company-delete');
Route::post('/getCompanyaddress', [Companies::class, 'getCompanyaddress']);
Route::post('/deleteCompanyAddress', [Companies::class, 'deleteaddress']);
Route::post('/saveCompanyAddress', [Companies::class, 'saveCompanyAddress']);

//module
Route::get('/module',[Module::class, 'index'])->name('module')->middleware('role:Admin');
Route::post('/getModuleAjax', [Module::class, 'getModuleAjax']);
Route::get('/submodule/add/{id}', [Module::class, 'add_submodule']);
Route::post('/addsubmodule',[Module::class,'addsubmodule']);
Route::get('/module/add',[Module::class,'moduleadd']);
Route::post('/addmodule',[Module::class,'addmodule']);
// Route::get('/module/permission/add/{id}', [Module::class, 'add_permission']);
// Route::post('/addpermission',[Module::class,'addpermission']);
// Route::get('/mvc/create/{id}', [Module::class, 'editmodule']);
Route::post('/createmvc', [Module::class, 'mvc']);
Route::post('/mvctable', [Module::class, 'mvctable']);
Route::get('/getColumns/{table}', [Module::class, 'getColumns']);

//recycle
Route::view('/module/recycle', 'Backend.module.recycleindex');
Route::post('/getModuleRecycleAjax', [Module::class, 'getModuleRecycleAjax']);
Route::post('/restoremodule/{id}', [Module::class, 'restoremodule']);


Route::post('/editmodule',[Module::class,'updatemodule']);
Route::post('/destorymodule/{id}', [Module::class, 'destorymodule']);
Route::post('/ShowPermissions',[Module::class,'ShowPermissions'])->name('ShowPermissions')->middleware('role:Admin');
Route::post('/storepermission',[Module::class,'savePermissions']);
Route::post('/deletePermission',[Module::class,'deletePermission'])->name('deletePermission')->middleware('role:Admin');

//menu
Route::view('/menu','Backend.menu.index')->name('menu')->middleware('role:Admin');
Route::post('/getMenuAjax', [Menu::class, 'getMenuAjax']);
Route::get('/menu/add/{id}', [Menu::class, 'Addmenubar']);
Route::post('/updatejsondata',[Menu::class,'updatejsondata']);
Route::view('/menu/add','Backend.menu.create1')->name('create1')->middleware('role:Admin');
Route::post('/addmenu',[Menu::class,'addmenu']);
Route::get('/menu/edit/{id}', [Menu::class, 'editmenu']);
Route::post('/editmenu',[Menu::class,'updatemenu']);
Route::post('/destorymenu/{id}', [Menu::class, 'destorymenu']);

//domain
Route::view('/domain','Backend.domain.index')->name('domain')->middleware('role:Admin');
Route::post('/getDomainAjax', [Domain::class, 'getDomainAjax']);
Route::view('/domain/add','Backend.domain.create');
Route::post('/adddomain',[Domain::class,'adddomain']);
Route::get('/domain/edit/{id}', [Domain::class, 'editdomain']);
Route::post('/updatedomain',[Domain::class,'updatedomain']);
Route::post('/destorydomain/{id}', [Domain::class, 'destorydomain']);

//language
Route::view('/language','Backend.language.index')->name('language')->middleware('role:Admin');
Route::post('/getLanguageAjax', [Language::class, 'getLanguageAjax']);
Route::view('/language/add','Backend.language.create');
Route::post('/addlanguage',[Language::class,'addlanguage']);
Route::get('/language/edit/{id}', [Language::class, 'editlanguage']);
Route::post('/updatelanguage',[Language::class,'updatelanguage']);
Route::post('/destorylanguage/{id}', [Language::class, 'destorylanguage']);

//department
Route::view('/department','Backend.department.index')->name('department')->middleware('role:Admin');
Route::post('/getDepartmentAjax', [Department::class, 'getDepartmentAjax']);
Route::view('/department/add','Backend.department.create');
Route::post('/adddepartment',[Department::class,'adddepartment']);
Route::get('/department/edit/{id}', [Department::class, 'editdepartment']);
Route::post('/updateDepartment',[Department::class,'updateDepartment']);
Route::post('/destorydepartment/{id}', [Department::class, 'destorydepartment']);

//designation
Route::view('/designation','Backend.designation.index')->name('designation')->middleware('role:Admin');
Route::post('/getDesignationAjax', [Designation::class, 'getDesignationAjax']);
Route::get('/designation/add', [Designation::class, 'designationadd']);
Route::post('/adddesignation',[Designation::class,'adddesignation']);
Route::get('/designation/edit/{id}', [Designation::class, 'editdesignation']);
Route::post('/updateDesignation',[Designation::class,'updateDesignation']);
Route::post('/destorydesignation/{id}', [Designation::class, 'destorydesignation']);



// Routes for CountrylistController
Route::get('/Countrylist', [\App\Http\Controllers\Backend\Countrylist::class, 'index'])->name('Countrylist');
Route::post('/getCountryAjax', [\App\Http\Controllers\Backend\Countrylist::class, 'getCountryAjax']);
Route::get('/Countrylist/edit/{id}', [\App\Http\Controllers\Backend\Countrylist::class, 'edit']);
Route::post('/updateCountry', [\App\Http\Controllers\Backend\Countrylist::class, 'updateCountry']);
Route::post('/destoryCountrylist/{id}', [\App\Http\Controllers\Backend\Countrylist::class, 'destoryCountrylist']);

// Routes for CitylistController
Route::get('/Citylist', [\App\Http\Controllers\Backend\Citylist::class, 'index'])->name('Citylist');
Route::post('/getCityAjax', [\App\Http\Controllers\Backend\Citylist::class, 'getCityAjax']);
Route::get('/City/edit/{id}', [\App\Http\Controllers\Backend\Citylist::class, 'edit']);
Route::post('/updateCity', [\App\Http\Controllers\Backend\Citylist::class, 'updateCity']);
Route::post('/destoryCity/{id}', [\App\Http\Controllers\Backend\Citylist::class, 'destoryCity']);



// Routes for StatelistController
Route::get('/Statelist', [\App\Http\Controllers\Backend\Statelist::class, 'index'])->name('Statelist');
Route::post('/getStateAjax', [\App\Http\Controllers\Backend\Statelist::class, 'getStateAjax']);

// Route::get('/Statelist/create', [\App\Http\Controllers\Backend\Statelist::class, 'create']);
Route::get('/State/edit/{id}', [\App\Http\Controllers\Backend\Statelist::class, 'edit']);
Route::post('/updatestate', [\App\Http\Controllers\Backend\Statelist::class, 'updatestate']);
Route::post('/destoryState/{id}', [\App\Http\Controllers\Backend\Statelist::class, 'destoryState']);


Route::get('/filemanager', [FileManagerController::class, 'index'])->name('filemanager');

// frontend

Route::get('/dashboard', [Dashboard::class, 'dashboard'])->name('dashboard');
Route::get('/blogs', [Blogfront::class, 'showblog']);
Route::get('/news', [Newsfront::class, 'shownews']);
Route::get('/Blogs/{article}', [Blogfront::class, 'blogsbyslug']);
Route::get('/News/{article}', [Newsfront::class, 'newsbyslug']);
Route::get('/ajaxblogs/category', [Blogfront::class, 'getBlogsByCategory']);
Route::get('/ajaxnews/category', [Newsfront::class, 'fetchByCategory']);
Route::get('/ajaxblogs', [Blogfront::class, 'loadMoreBlogs'])->name('ajaxblogs');
Route::get('/ajaxnews', [Newsfront::class, 'loadMoreNews'])->name('ajaxnews');
Route::get('/contact', [Dashboard::class, 'contact'])->name('contact');


});
// Test Routes
Route::get('/Test', [\App\Http\Controllers\Backend\TestController::class, 'index'])->name('Test');
Route::get('/Test/create', [\App\Http\Controllers\Backend\TestController::class, 'create']);
Route::post('/Test/store', [\App\Http\Controllers\Backend\TestController::class, 'store']);
Route::get('/Test/edit/{id}', [\App\Http\Controllers\Backend\TestController::class, 'edit']);
Route::post('/Test/update', [\App\Http\Controllers\Backend\TestController::class, 'update']);
Route::post('/Test/delete/{id}', [\App\Http\Controllers\Backend\TestController::class, 'delete']);