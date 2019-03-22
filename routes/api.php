<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router){
    Route::post('login', 'API\AuthController@login');
    Route::post('logout', 'API\AuthController@logout');
    Route::post('refresh', 'API\AuthController@refresh');
    Route::post('me', 'API\AuthController@me');
});*/

/*Route::group(['middleware' => ['jwt.auth']], function() {
    Route::get('logout', 'AuthController@logout');
    Route::get('test', function(){
        return response()->json(['foo'=>'bar']);
    });
});*/
Route::any('security-login', 'Api\ApiController@securitylogin');
Route::group(['namespace' => 'Api','middleware' => 'jwt-auth'], function (){
	Route::any('update-password','UserController@updateuserpassword');
	Route::get('get-user','ApiController@getAuthUser');
	Route::post('change-password','UserController@changePassword');
	Route::post('update-user-profile','UserController@updateUserProfile');
	Route::post('visitor-incident','AidRequestController@reportIncident');
	Route::get('get-incident','AidRequestController@getIncidents');
	Route::get('user-logout','UserController@userLogout');
	Route::post('notification_token_registration','MiscellaneousController@notificationTokenRegisteration');
	Route::get('get-contacts','UserController@getcontacts');
	Route::any('records-response','AidRequestController@reportsresponse');
	Route::post('get-location','BeaconController@getorgpreLocInfo');
	Route::get('firebaseNotification','ApiController@firebaseNotification');
	Route::get('getLastMessage','UserController@getcontactsLastChat');
	Route::post('updateLastMessage','ChatRecordController@updateLastMessage');
	Route::post('chatNotification','ChatRecordController@chatNotification');
	Route::post('resetUnread','ChatRecordController@resetUnreadMessages');
    Route::any('total_badge','ChatRecordController@total_badge');
});

Route::post('get-opl-info', 'Api\BeaconController@getOrgPreLocInfo');
Route::get('get-beacon-uuid', 'Api\BeaconController@getBeaconUUID');
Route::post('request-otp', 'Api\MiscellaneousController@requestOTP');
Route::post('verify-otp', 'Api\MiscellaneousController@verifyOTP');
Route::get('page-info', 'Api\StaticPageController@pageInfo');
Route::post('user_registeration','Api\UserController@userRegisteration');
Route::get('crone-unresponses','Api\ChatRecordController@croneUnresponses');
Route::get('get-uuid','Api\MiscellaneousController@getUuid');
//~ Route::get('jwt','Api\ApiController@jwt');
