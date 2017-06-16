<?php
Route::get('/', function () {
    return view('welcome');
});


 Route::group(['namespace' => 'Cms'], function () {
	     
		/****************** login **************************/ 
		Route::get('/cms', 				'LoginController@index');
		Route::post('/cms/login', 		'LoginController@store');
		Route::get('/cms/logout', 		'LoginController@logout');
		
		Route::post('/cms/forgetPassword', 	'LoginController@forgetPassword');
		
		Route::get('/cms/emailverify', 		'LoginController@emailverify');
		Route::post('/cms/emailverify', 		'LoginController@emailverify');
		/****************** login end **************************/ 
		
		/****************** dashboard **************************/ 
		Route::get('/cms/dashboard', 	'DashboardController@index');
		Route::get('/cms/getyear/{id?}', 	'DashboardController@graph');
		
		/****************** dashboard end **************************/ 
		
		/****************** common **************************/ 
		Route::get('/cms/common/userStatus', 	'CommonController@userStatus');
		/****************** common end **************************/ 
		
		/****************** user **************************/ 
		Route::get('/cms/user', 				 'UserController@index');
		Route::get('/cms/user/add', 			 'UserController@create');
		Route::get('/cms/user/edit/{id?}', 		 'UserController@edit');
		Route::get('/cms/user/view/{id?}', 		 'UserController@view');
		Route::get('/cms/user/show/{id?}', 		 'UserController@show');
		Route::get('/cms/user/delete/{id?}',     'UserController@destroy');
		Route::get('/cms/user/deleteM',         'UserController@destroyMultiple');
		Route::post('/cms/user/store',           'UserController@store');
		Route::post('/cms/user/update', 		 'UserController@update');
        Route::get('/cms/user/change/{id?}',     'UserController@change');
        Route::post('/cms/user/updatePassword', 'UserController@updatePassword');
        Route::get('/cms/user/export',         'UserController@exportCsv');
        Route::get('/cms/user/import',     'UserController@import');
        Route::get('/cms/user/sample',     'UserController@forceDownload');
        Route::post('/cms/user/importCSV', 'UserController@import_csv');
		/****************** user end **************************/ 
		
		
		/****************** profile **************************/ 
		Route::get('/cms/profile', 				 'ProfileController@index');
		Route::get('/cms/profile/add', 			 'ProfileController@create');
		Route::get('/cms/editProfile/{id?}', 		 'ProfileController@edit');
		Route::get('/cms/profile/show/{id?}', 		 'ProfileController@show');
		Route::get('/cms/profile/delete/{id?}',     'ProfileController@destroy');
		Route::post('/cms/profile/update', 		 'ProfileController@update');
        
		Route::post('/cms/profile/store',           'ProfileController@store');
		Route::get('/cms/changePassword/{id?}', 	 'ProfileController@changePassword');
     	/****************** profile end **************************/ 
		
		
		/****************** role **************************/ 
		Route::get('/cms/role',                 'RoleController@index');
		Route::get('/cms/role/add',             'RoleController@create');
		Route::get('/cms/role/edit/{id?}',      'RoleController@edit');
		Route::get('/cms/role/delete/{id?}',    'RoleController@destroy');
		Route::post('/cms/role/store',          'RoleController@store');
		Route::post('/cms/role/update',         'RoleController@update');
		/****************** role end **************************/ 
		 
		  
		/****************** client **************************/ 
		Route::get('/cms/client',               'ClientController@index');
		Route::get('/cms/client/add',           'ClientController@create');
		Route::get('/cms/client/show/{id?}',    'ClientController@show');
		Route::get('/cms/client/edit/{id?}',    'ClientController@edit');
		Route::get('/cms/client/view/{id?}',    'ClientController@view');
		Route::get('/cms/client/delete/{id?}',  'ClientController@destroy');
		Route::get('/cms/client/deleteM',       'ClientController@destroyMultiple');
		Route::post('/cms/client/store',        'ClientController@store');
		Route::post('/cms/client/update',       'ClientController@update');
		Route::get('/cms/client/export',        'ClientController@exportCsv');
        Route::get('/cms/client/import',        'ClientController@import');
        Route::get('/cms/client/sample',        'ClientController@forceDownload');
        Route::post('/cms/client/importCSV',    'ClientController@import_csv');
		/****************** client end **************************/ 
		  
		 /****************** client_fm **************************/ 
		Route::get('/cms/client_fm',               'ClientController_fm@index');
		Route::get('/cms/client_fm/add',           'ClientController_fm@create');
		Route::get('/cms/client_fm/show/{id?}',    'ClientController_fm@show');
		Route::get('/cms/client_fm/edit/{id?}',    'ClientController_fm@edit');
		Route::get('/cms/client_fm/view/{id?}',    'ClientController_fm@view');
		Route::get('/cms/client_fm/delete/{id?}',  'ClientController_fm@destroy');
		Route::get('/cms/client_fm/deleteM',       'ClientController_fm@destroyMultiple');
		Route::post('/cms/client_fm/store',        'ClientController_fm@store');
		Route::post('/cms/client_fm/update',       'ClientController_fm@update');
		Route::get('/cms/client_fm/export',        'ClientController_fm@exportCsv');
        Route::get('/cms/client_fm/import',        'ClientController_fm@import');
        Route::get('/cms/client_fm/sample',        'ClientController_fm@forceDownload');
        Route::post('/cms/client_fm/importCSV',    'ClientController_fm@import_csv');
		/****************** client_fm end **************************/ 

 

		/****************** company **************************/ 
		Route::get('/cms/company',               'CompanyController@index');
		Route::get('/cms/company/add',           'CompanyController@create');
		Route::get('/cms/company/show/{id?}',    'CompanyController@show');
		Route::get('/cms/company/edit/{id?}',    'CompanyController@edit');
		Route::get('/cms/company/view/{id?}',    'CompanyController@view');
		Route::get('/cms/company/delete/{id?}',  'CompanyController@destroy');
		Route::post('/cms/company/store',        'CompanyController@store');
		Route::post('/cms/company/update',       'CompanyController@update');
		Route::get('/cms/company/export',        'CompanyController@exportCsv');
        Route::get('/cms/company/import',        'CompanyController@import');
        Route::get('/cms/company/sample',        'CompanyController@forceDownload');
        Route::post('/cms/company/importCSV',    'CompanyController@import_csv');
		/****************** company end **************************/ 
 

		/****************** company_fm **************************/ 
		Route::get('/cms/company_fm',               'CompanyController_fm@index');
		Route::get('/cms/company_fm/add',           'CompanyController_fm@create');
		Route::get('/cms/company_fm/show/{id?}',    'CompanyController_fm@show');
		Route::get('/cms/company_fm/edit/{id?}',    'CompanyController_fm@edit');
		Route::get('/cms/company_fm/view/{id?}',    'CompanyController_fm@view');
		Route::get('/cms/company_fm/delete/{id?}',  'CompanyController_fm@destroy');
		Route::post('/cms/company_fm/store',        'CompanyController_fm@store');
		Route::post('/cms/company_fm/update',       'CompanyController_fm@update');
		Route::get('/cms/company_fm/export',        'CompanyController_fm@exportCsv');
        Route::get('/cms/company_fm/import',        'CompanyController_fm@import');
        Route::get('/cms/company_fm/sample',        'CompanyController_fm@forceDownload');
        Route::post('/cms/company_fm/importCSV',    'CompanyController_fm@import_csv');
		/****************** company_fm end **************************/ 
 
		/****************** Relationship Manager **************************/ 
		Route::get('/cms/manager',               'RelationshipmanagerController@index');
		Route::get('/cms/manager/add',           'RelationshipmanagerController@create');
		Route::get('/cms/manager/show/{id?}',    'RelationshipmanagerController@show');
		Route::get('/cms/manager/edit/{id?}',    'RelationshipmanagerController@edit');
		Route::get('/cms/manager/view/{id?}',    'RelationshipmanagerController@view');
		Route::get('/cms/manager/delete/{id?}',  'RelationshipmanagerController@destroy');
		Route::post('/cms/manager/store',        'RelationshipmanagerController@store');
		Route::post('/cms/manager/update',       'RelationshipmanagerController@update');
		Route::get('/cms/manager/export',        'RelationshipmanagerController@exportCsv');
        Route::get('/cms/manager/import',        'RelationshipmanagerController@import');
        Route::get('/cms/manager/sample',        'RelationshipmanagerController@forceDownload');
        Route::post('/cms/manager/importCSV',    'RelationshipmanagerController@import_csv');
		/****************** Relationshipmanager end **************************/ 
		
 
			/****************** Relationship_fm Manager **************************/ 
		Route::get('/cms/manager_fm',               'RelationshipmanagerController_fm@index');
		Route::get('/cms/manager_fm/add',           'RelationshipmanagerController_fm@create');
		Route::get('/cms/manager_fm/show/{id?}',    'RelationshipmanagerController_fm@show');
		Route::get('/cms/manager_fm/edit/{id?}',    'RelationshipmanagerController_fm@edit');
		Route::get('/cms/manager_fm/view/{id?}',    'RelationshipmanagerController_fm@view');
		Route::get('/cms/manager_fm/delete/{id?}',  'RelationshipmanagerController_fm@destroy');
		Route::post('/cms/manager_fm/store',        'RelationshipmanagerController_fm@store');
		Route::post('/cms/manager_fm/update',       'RelationshipmanagerController_fm@update');
		Route::get('/cms/manager_fm/export',        'RelationshipmanagerController_fm@exportCsv');
        Route::get('/cms/manager_fm/import',        'RelationshipmanagerController_fm@import');
        Route::get('/cms/manager_fm/sample',        'RelationshipmanagerController_fm@forceDownload');
        Route::post('/cms/manager_fm/importCSV',    'RelationshipmanagerController_fm@import_csv');
		/****************** Relationshipmanager_fm end **************************/ 
		  
		/****************** People Manager **************************/ 
		Route::get('/cms/people',               'PeopleController@index');
		Route::get('/cms/people/add',           'PeopleController@create');
		Route::get('/cms/people/show/{id?}',    'PeopleController@show');
		Route::get('/cms/people/edit/{id?}',    'PeopleController@edit');
		Route::get('/cms/people/view/{id?}',    'PeopleController@view');
		Route::get('/cms/people/delete/{id?}',  'PeopleController@destroy');
		Route::get('/cms/people/deleteM',       'PeopleController@destroyMultiple');
		Route::post('/cms/people/store',        'PeopleController@store');
		Route::post('/cms/people/update',       'PeopleController@update');
		Route::get('/cms/people/export',        'PeopleController@exportCsv');
        Route::get('/cms/people/import',        'PeopleController@import');
        Route::get('/cms/people/sample',        'PeopleController@forceDownload');
        Route::post('/cms/people/importCSV',    'PeopleController@import_csv');
        Route::post('/cms/people/upload',         'PeopleController@crop_image');
        Route::post('/cms/people/save_thumbnail',  'PeopleController@save_thumbnail');
        
		/****************** People end **************************/ 
		
		
		/****************** Permission Manage **************************/ 
		Route::get('/cms/permission',               'PermissionController@index');
		Route::get('/cms/permission/add',           'PermissionController@create');
		Route::get('/cms/permission/show/{id?}',    'PermissionController@show');
		Route::get('/cms/permission/edit/{id?}',    'PermissionController@edit');
		Route::get('/cms/permission/delete/{id?}',  'PermissionController@destroy');
		Route::post('/cms/permission/store',        'PermissionController@store');
		Route::post('/cms/permission/update',       'PermissionController@update');
		/****************** Permission end **************************/ 
		
		/****************** News Manage **************************/
 		Route::get('/cms/news',                'NewsController@index');
 		Route::get('/cms/news/add',            'NewsController@create');
 		Route::get('/cms/news/edit/{id?}', 	   'NewsController@edit');
 		Route::get('/cms/news/view/{id?}',    'NewsController@view');
		Route::post('/cms/news/store',         'NewsController@store');
		Route::post('/cms/news/update', 	   'NewsController@update');
		Route::get('/cms/news/delete/{id?}',   'NewsController@destroy');
		Route::get('/cms/news/deleteM',         'NewsController@destroyMultiple');
		Route::get('/cms/news/export',         'NewsController@exportCsv');
        Route::get('/cms/news/import',         'NewsController@import');
        Route::get('/cms/news/sample',         'NewsController@forceDownload');
        Route::post('/cms/news/importCSV',     'NewsController@import_csv');
        Route::post('/cms/news/upload',         'NewsController@crop_image');
        Route::post('/cms/news/upload2',         'NewsController@crop_image2');
        Route::post('/cms/news/save_thumbnail',  'NewsController@save_thumbnail');
        Route::post('/cms/news/save_thumbnail2',  'NewsController@save_thumbnail2');
        
        	 
		/****************** News end **************************/ 
  
		/****************** Event Manage **************************/
 		Route::get('/cms/event',            'EventController@index');
 		Route::get('/cms/event/add',        'EventController@create');
 		Route::post('/cms/event/store',     'EventController@store');
 		Route::get('/cms/event/edit/{id?}', 'EventController@edit');
 		Route::get('/cms/event/view/{id?}',    'EventController@view');
 		Route::post('/cms/event/update', 	'EventController@update');
 		Route::get('/cms/event/export',         'EventController@exportCsv');
        Route::get('/cms/event/import',         'EventController@import');	
        Route::get('/cms/event/sample',    'EventController@forceDownload');
        Route::post('/cms/event/importCSV','EventController@import_csv'); 
		Route::get('/cms/event/delete/{id?}',   'EventController@destroy');
		Route::get('/cms/event/deleteM',         'EventController@destroyMultiple');
		Route::post('/cms/event/upload',         'EventController@crop_image');
        Route::post('/cms/event/upload2',         'EventController@crop_image2');
        Route::post('/cms/event/save_thumbnail',  'EventController@save_thumbnail');
        Route::post('/cms/event/save_thumbnail2',  'EventController@save_thumbnail2');
		/****************** Event end **************************/ 

		/****************** Family Manage **************************/ 
 		Route::get('/cms/family',           'FamilyController@index');
 		Route::get('/cms/family/add',       'FamilyController@create');
 		Route::post('/cms/family/store',    'FamilyController@store');
 		Route::get('/cms/family/edit/{id?}','FamilyController@edit');
 		Route::get('/cms/family/view/{id?}','FamilyController@view');
 		Route::post('/cms/family/update', 	'FamilyController@update');	
 		Route::get('/cms/family/export',    'FamilyController@exportCsv');
        Route::get('/cms/family/import',    'FamilyController@import');	
        Route::get('/cms/family/sample',    'FamilyController@forceDownload');
        Route::post('/cms/family/importCSV','FamilyController@import_csv');
		Route::get('/cms/family/delete/{id?}',   'FamilyController@destroy');
		Route::get('/cms/family/deleteM',         'FamilyController@destroyMultiple');
		/****************** Family end **************************/

		/****************** Message Manage **************************/
		Route::get('/cms/message',           'MessageController@index');
		Route::get('/cms/message/detail/{id?}','MessageController@detail');
		Route::get('/cms/message/getById/{id?}','MessageController@getDataById');
		Route::get('/cms/message/deleteM',         'MessageController@destroyMultiple');
		
		/****************** Message end **************************/
		
		
		/****************** Push Notification Manage **************************/
		Route::get('/cms/pushnotification',           'PushNotificationController@index');
		Route::post('/cms/pushnotification/store',    'PushNotificationController@store');
		
		/****************** Push Notification end **************************/
		
});

 


Route::group(['prefix' => 'api'], function()
{

    /*
   * import excel csv Route
   */
    Route::post('user/import_excel_csv','ImportController@importCSVEXCEl');
    Route::post('user/import_excel_csv_database','ImportController@importCSVEXCElDatabase');
    Route::post('user/{id}/delete_excel_csv','ImportController@deleteCSVEXCEl');



    // Password reset link request routes...
    Route::post('password/email', 'Auth\PasswordController@postEmail');

    // Password reset routes...
    Route::post('password/reset', 'Auth\PasswordController@postReset');

    // authentication
    Route::post('register', 'RegisterController@register');
    Route::get('register/verify/{confirmationCode}', 'RegisterController@confirm');
    Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
    Route::post('authenticate', 'AuthenticateController@authenticate');


    /*
     * User Route
     */
    Route::put('user/{id}/profile','UserController@editProfile');  
    Route::get('user/search','UserController@search');
    Route::resource('user', 'UserController@index');
    Route::get('user/export/file','UserController@exportFile');


    /*
     * Permission Route
     */
    Route::get('permission/search','PermissionController@search');
    Route::resource('permission', 'PermissionController@index');

    /*
     * Role Route
     */
    Route::get('role/search','RoleController@search');
    Route::resource('role', 'RoleController@index');

    /*
     * Task Route
     */
    Route::get('task/search','TaskController@search');
    Route::resource('task', 'TaskController@index');
    Route::get('task/export/file','TaskController@exportFile');
    /*
     * Comment Route
     */
    Route::get('comment/search','CommentController@search');
    Route::resource('comment', 'CommentController@index');

    /*
     * tag Route
     */
    Route::get('tag/search','TagController@search');
    Route::resource('tag', 'TagController@index');

    /*
     * Gallery Route
     */
    Route::get('gallery/search','GalleryController@search');
    Route::resource('gallery', 'GalleryController@index');


    /*
     * Category Route
     */
    Route::resource('category', 'CategoryController@index');

    /*
     * Upload image Controller
     */
    Route::post('/uploadimage','UploadController@uploadimage');
    Route::post('/deleteimage/{id}','UploadController@deleteUpload');
});


