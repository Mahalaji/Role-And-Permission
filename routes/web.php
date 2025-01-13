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
use App\Http\Controllers\Module;
use App\Http\Controllers\menu;
use App\Http\Controllers\domain;
use App\Http\Controllers\language;
use App\Http\Controllers\department;
use App\Http\Controllers\designation;
use App\Http\Controllers\frontend\dashboard;
use App\Http\Controllers\frontend\blogfront;
use App\Http\Controllers\frontend\newsfront;





   
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
Route::get('/blog',[blogs::class, 'blogshow'])->name('blog')->middleware('role:Admin|Blog_Team');
Route::post('/getBlogsAjax', [blogs::class, 'getBlogsAjax']);
Route::post('/updateBlogStatus',[blogs::class,'updateBlogStatus']);
Route::get('/blog/add', [blogs::class, 'title']);
Route::post('/addblog',[blogs::class,'addblog']);
Route::get('/blog/edit/{id}', [blogs::class, 'edit']);
Route::post('/update',[blogs::class,'update']);
Route::post('/destory/{id}', [blogs::class, 'destory']);
Route::view('/blogcategory','blog.category_index')->name('blogcategory')->middleware('role:Admin|Blog_Team');
Route::post('/getBlogsCategoryAjax', [blogs::class, 'getBlogsCategoryAjax']);
Route::view('/blogcategory/add','blog.category_create');
Route::post('/addcategery',[blogs::class,'addcategery']);
Route::post('/destorycategory/{id}', [blogs::class, 'destorycategory']);
Route::get('/blogcategory/edit/{id}', [blogs::class, 'editcategory']);
Route::post('/updatecategery',[blogs::class,'updateCategory']);

//news
Route::get('/news/add', [newss::class, 'title']);
Route::post('/createnews',[newss::class,'createnews']);
Route::view('/newss','news.index')->name('newss')->middleware('role:Admin|News_Team');
Route::post('/getNewsAjax', [newss::class, 'getNewsAjax']);
Route::post('/updateNewsStatus',[newss::class,'updateNewsStatus']);
Route::get('/news/edit/{id}', [newss::class, 'editnews']);
Route::post('/updatenews',[newss::class,'updatenews']);
Route::post('/destorynews/{id}', [newss::class, 'destorynews']);
Route::view('/newscategory','news.category_index')->name('newscategory')->middleware('role:Admin|News_Team');
Route::post('/getNewsCategoryAjax', [newss::class, 'getNewsCategoryAjax']);
Route::view('/newscategory/add','news.category_create');
Route::post('/createnewscategory',[newss::class,'createnewscategory']);
Route::get('/newscategory/edit/{id}', [newss::class, 'editnewscategory']);
Route::post('/updatenewscategery',[newss::class,'updatenewscategery']);
Route::post('/destorynewscategory/{id}', [newss::class, 'destorynewscategory']);

//pages
Route::view('/pages','pages.index')->name('pages')->middleware('role:Admin|Page_Team');
Route::post('/getPagesAjax', [pagess::class, 'getPagesAjax']);
Route::get('/editpages/{id}', [pagess::class, 'editpages']);
Route::post('/updatepages',[pagess::class,'updatepages']);
Route::view('/page/add','pages.create');
Route::post('/createpages',[pagess::class,'createpages']);
Route::post('/destorypages/{id}', [pagess::class, 'destorypages']);

//company
Route::view('/company','company.index')->name('company')->middleware('role:Admin');
Route::post('/getCompanyAjax', [companies::class, 'getCompanyAjax']);
Route::view('/company/add','company.create');
Route::post('/createcompany',[companies::class,'createcompany']);
Route::get('/company/edit/{id}', [companies::class, 'editcompany']);
Route::post('/updatecompany',[companies::class,'updatecompany']);
Route::post('/destorycompany/{id}', [companies::class, 'destorycompany']);
Route::post('/getCompanyaddress', [companies::class, 'getCompanyaddress']);
Route::post('/deleteCompanyAddress', [companies::class, 'deleteaddress']);
Route::post('/saveCompanyAddress', [companies::class, 'saveCompanyAddress']);

//module
Route::view('/module','module.index')->name('module')->middleware('role:Admin');
Route::post('/getModuleAjax', [Module::class, 'getModuleAjax']);
Route::get('/submodule/add/{id}', [Module::class, 'add_submodule']);
Route::post('/addsubmodule',[Module::class,'addsubmodule']);
Route::get('/module/add',[Module::class,'moduleadd']);
Route::post('/addmodule',[Module::class,'addmodule']);
// Route::get('/module/permission/add/{id}', [Module::class, 'add_permission']);
// Route::post('/addpermission',[Module::class,'addpermission']);
Route::get('/module/edit/{id}', [Module::class, 'editmodule']);
Route::post('/editmodule',[Module::class,'updatemodule']);
Route::post('/destorymodule/{id}', [Module::class, 'destorymodule']);
Route::post('/ShowPermissions',[Module::class,'ShowPermissions'])->name('ShowPermissions')->middleware('role:Admin');
Route::post('/storepermission',[Module::class,'savePermissions']);
Route::post('/deletePermission',[Module::class,'deletePermission'])->name('deletePermission')->middleware('role:Admin');

//menu
Route::view('/menu','menu.index')->name('menu')->middleware('role:Admin');
Route::post('/getMenuAjax', [menu::class, 'getMenuAjax']);
Route::get('/menu/add/{id}', [menu::class, 'Addmenubar']);
Route::post('/updatejsondata',[menu::class,'updatejsondata']);
Route::view('/menu/add','menu.create1')->name('create1')->middleware('role:Admin');
Route::post('/addmenu',[menu::class,'addmenu']);
Route::get('/menu/edit/{id}', [menu::class, 'editmenu']);
Route::post('/editmenu',[menu::class,'updatemenu']);
Route::post('/destorymenu/{id}', [menu::class, 'destorymenu']);

//domain
Route::view('/domain','domain.index')->name('domain')->middleware('role:Admin');
Route::post('/getDomainAjax', [domain::class, 'getDomainAjax']);
Route::view('/domain/add','domain.create');
Route::post('/adddomain',[domain::class,'adddomain']);
Route::get('/domain/edit/{id}', [domain::class, 'editdomain']);
Route::post('/updatedomain',[domain::class,'updatedomain']);
Route::post('/destorydomain/{id}', [domain::class, 'destorydomain']);

//language
Route::view('/language','language.index')->name('language')->middleware('role:Admin');
Route::post('/getLanguageAjax', [language::class, 'getLanguageAjax']);
Route::view('/language/add','language.create');
Route::post('/addlanguage',[language::class,'addlanguage']);
Route::get('/language/edit/{id}', [language::class, 'editlanguage']);
Route::post('/updatelanguage',[language::class,'updatelanguage']);
Route::post('/destorylanguage/{id}', [language::class, 'destorylanguage']);

//department
Route::view('/department','department.index')->name('department')->middleware('role:Admin');
Route::post('/getDepartmentAjax', [department::class, 'getDepartmentAjax']);
Route::view('/department/add','department.create');
Route::post('/adddepartment',[department::class,'adddepartment']);
Route::get('/department/edit/{id}', [department::class, 'editdepartment']);
Route::post('/updateDepartment',[department::class,'updateDepartment']);
Route::post('/destorydepartment/{id}', [department::class, 'destorydepartment']);

//designation
Route::view('/designation','designation.index')->name('designation')->middleware('role:Admin');
Route::post('/getDesignationAjax', [designation::class, 'getDesignationAjax']);
Route::get('/designation/add', [designation::class, 'designationadd']);
Route::post('/adddesignation',[designation::class,'adddesignation']);
Route::get('/designation/edit/{id}', [designation::class, 'editdesignation']);
Route::post('/updateDesignation',[designation::class,'updateDesignation']);
Route::post('/destorydesignation/{id}', [designation::class, 'destorydesignation']);

// frontend

Route::get('/dashboard', [dashboard::class, 'dashboard']);
Route::get('/blogs', [blogfront::class, 'showblog']);
Route::get('/news', [newsfront::class, 'shownews']);
Route::get('/Blogs/{article}', [blogfront::class, 'blogsbyslug']);
Route::get('/News/{article}', [newsfront::class, 'newsbyslug']);
Route::get('/Blogtitle/{article}', [blogfront::class, 'blogsbytitle']);
Route::get('/newstitle/{article}', [newsfront::class, 'newsbytitle']);
Route::get('/ajaxblogs', [blogfront::class, 'loadMoreBlogs'])->name('ajaxblogs');
Route::get('/ajaxnews', [newsfront::class, 'loadMoreNews'])->name('ajaxnews');
});
