<?php
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
  Route::get('site-offline', 'HomeController@SiteOffline');

///////////////Admin Routes////////////////
  Route::group(['prefix' => 'admin'], function () {
    Route::get('dashboard', 'AdminController@index')->name('dashboard');
  });

///////////////user password change by super admin////////////////
  Route::get('user-list', 'AdminController@getUser')->name('user.list');
  Route::get('user-password', 'AdminController@userPasswordChange')->name('user.password');
  Route::post('change-password', 'AdminController@passwordChange')->name('update.pass');
  Route::get('admin', 'Admin\LoginController@showLoginForm')->name('admin.login');
  Route::post('admin', 'Admin\LoginController@login');
  Route::get('admin-password/request', 'Admin\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
  Route::post('admin-password/email', 'Admin\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
  Route::get('admin-password/reset/{token}', 'Admin\ResetPasswordController@showResetForm')->name('admin.password.reset');
  Route::post('admin-password/reset', 'Admin\ResetPasswordController@reset');
  Route::post('logouts', 'Admin\LoginController@logout')->name('admin.logout');

  /* Resend Activation mail */
  Route::get('activate-user', 'Admin\Organization\OrganizationController@activateUser')->name('activate_user');

  Route::group(['prefix' => 'admin', 'middleware' => ['web', 'auth:admin'], 'namespace' => 'Admin'], function () {

	  Route::get('view-profile', 'Profile\ProfileController@index')->name('admin.profile');
    Route::get('edit-profile', 'Profile\ProfileController@updateProfile')->name('admin.edit.profile');
    Route::post('update-profile', 'Profile\ProfileController@updatePersonalInformation')->name('admin.update.profile');
    Route::get('change-password', 'Profile\ProfileController@userPassword')->name('admin.change.password');
    Route::post('password-update', 'Profile\ProfileController@changePassword')->name('admin.update.password');
    Route::get('site-setting', 'SiteSetting\SiteSettingController@index')->name('site.setting');
    Route::post('save-site-setting', 'SiteSetting\SiteSettingController@saveSettings')->name('save.site.setting');
    Route::get('dynamic-content', 'DynamicContent\DynamicContentController@index')->name('dynamic.content');
    Route::get('admin-users', 'Admins\AdminsController@index')->name('admins.index');
    Route::post('save-admin-user', 'Admins\AdminsController@store')->name('save.admin.user');
    Route::resource('admin-user', 'Admins\AdminsController');
    Route::post('admin-user/{id}', 'Admins\AdminsController@update');

    Route::match(array('GET', 'POST', 'PUT', 'PATCH'), '/create-admin-user', array(
      'uses' => 'Admins\AdminsController@create',
      'as' => 'admins.create'
    ));

    Route::get('edit-content/{slug}', 'DynamicContent\DynamicContentController@editContent')->name('edit.content');
    Route::post('update-content', 'DynamicContent\DynamicContentController@updateDynamicContent')->name('update.content');


    Route::resource('banner', 'Banner\BannerController');
    Route::resource('testimonial', 'Testimonial\TestimonialController');

    /* Route start for Premises */
    Route::resource('premise', 'Premise\PremiseController');
    Route::post('premise/create', 'Premise\PremiseController@create');

    /* Route start for Premises */
    Route::resource('location', 'Location\LocationController');
    Route::post('location/create', 'Location\LocationController@create');

    /* Route start for Premises */
    Route::resource('beacon', 'Beacon\BeconController');
    Route::post('beacon/create', 'Beacon\BeconController@create');

    Route::resource('visitorlogs', 'Visitorlog\VisitorlogController');

    /* Route start for Manage Subscription */
    Route::resource('subscription', 'Subscription\SubscriptionController');
    Route::get('subscriptions/plans', 'Subscription\SubscriptionController@subscriptionPlan');
    Route::get('subscriptions/change/', 'Subscription\SubscriptionController@changeSubscription')->name('subscription-change');
    Route::get('subscriptions/payment', 'Subscription\SubscriptionController@payment');
    Route::get('subscriptions/card/{id}', 'Subscription\SubscriptionController@createCardPayment');
    Route::post('subscriptions/payment/process', 'Subscription\SubscriptionController@paymentProcess');
    Route::post('subscriptions/card/process', 'Subscription\SubscriptionController@changeCardPayment');
    Route::get('subscriptions/card-detail/{id}', 'Subscription\SubscriptionController@viewCardPayment');
    Route::get('payment/history', 'Subscription\SubscriptionController@paymentHistory');
    Route::get('invoice/detail/{subscription_id}', 'Subscription\SubscriptionController@subscriptionDetail');


    /* Route start for Manage Security */
    Route::resource('security', 'ManageSecurity\ManageSecurityController');

    /* Route start for Manage Security */
    Route::resource('organization', 'Organization\OrganizationController');

    Route::get('aid-request', 'AidRequest\AidRequestController@index')->name('aid_request');
    
    Route::get('/aid-request-responded/{id}/{orgId}', 'AidRequest\AidRequestController@respondedList');
    /* get Aid Request */
    Route::get('getAidRequest', [
      'as' => 'get_aid_request',
      'uses' => 'Ajax\AjaxAidRequestController@getAidRequestList'
    ]);



    Route::get('newsletters', 'NewsLetter\NewsLetterController@index')->name('newsletters');
    Route::get('static-page', [
      'as' => 'admin_static_page',
      'uses' => 'StaticPages\StaticPageController@index'
    ]
  );

    Route::get('edit-page/{slug}', [
      'as' => 'admin_edit_about_us',
      'uses' => 'StaticPages\StaticPageController@editStaticPage'
    ]
  );

    Route::post('save-static-page', [
      'as' => 'save_static_page',
      'uses' => 'StaticPages\StaticPageController@saveStaticPage'
    ]
  );


    /* change subscription status */
    Route::post('subscriptionStatus', [
      'as' => 'subscription_status',
      'uses' => 'Subscription\SubscriptionController@changeStatus'
    ]);

    /* change premises status */
    Route::post('premiseStatus', [
      'as' => 'premise_status',
      'uses' => 'Premise\PremiseController@changeStatus'
    ]);

    /* change location status */
    Route::post('locationStatus', [
      'as' => 'location_status',
      'uses' => 'Location\LocationController@changeStatus'
    ]);

    /* change becon status */
    Route::post('beconStatus', [
      'as' => 'becon_status',
      'uses' => 'Beacon\BeconController@changeStatus'
    ]);

    /* change testimonial status */
    Route::post('testimonialStatus', [
      'as' => 'testimonial_status',
      'uses' => 'Testimonial\TestimonialController@changeStatus'
    ]);

    /* change security status */
    Route::post('securityStatus', [
      'as' => 'security_status',
      'uses' => 'ManageSecurity\ManageSecurityController@changeStatus'
    ]);

    /* change organization status */
    Route::post('reInvite', [
      'as' => 're_invite',
      'uses' => 'ManageSecurity\ManageSecurityController@reInvitation'
    ]);

    /* cancel subscription */
    Route::post('cancelSubscription', [
      'as' => 'cancel_subscription',
      'uses' => 'Subscription\SubscriptionController@subscriptionCancel'
    ]);

    /* cancel subscription */
    Route::post('defaultCardPayment', [
      'as' => 'default_card_payment',
      'uses' => 'Subscription\SubscriptionController@defaultCardPayment'
    ]);

    /* cancel subscription */
    Route::post('deleteCard', [
      'as' => 'delete_card',
      'uses' => 'Subscription\SubscriptionController@deleteCard'
    ]);

    /* change organization status */
    Route::post('resendMail', [
      'as' => 'resend_mail',
      'uses' => 'Organization\OrganizationController@resendActivationMail'
    ]);

    /* change security status */
    Route::post('securityStore', [
      'as' => 'security_store',
      'uses' => 'ManageSecurity\ManageSecurityController@store'
    ]);

    /* change organization status */
    Route::post('organizationStatus', [
      'as' => 'organization_status',
      'uses' => 'Organization\OrganizationController@changeStatus'
    ]);

    /* change organization status */
    Route::get('getPremise', [
      'as' => 'get_premise',
      'uses' => 'Ajax\AjaxLocationController@getPremise'
    ]);

    /* change organization status */
    Route::get('getLocation', [
      'as' => 'get_ocation',
      'uses' => 'Ajax\AjaxLocationController@getLocation'
    ]);

    /* change organization status */
    Route::post('premiseAdd', [
      'as' => 'premise_add',
      'uses' => 'Premise\PremiseController@store'
    ]);

    /* change organization status */
    Route::post('locationAdd', [
      'as' => 'location_add',
      'uses' => 'Location\LocationController@store'
    ]);

    /* change adminuser status */
    Route::post('adminUserStatus', [
      'as' => 'admin_user_status',
      'uses' => 'Admins\AdminsController@changeStatus'
    ]);

    Route::get('/transaction-details', [
      'uses' => 'Transaction\TransactionController@index'
    ]);

  });


Route::group(['prefix' => 'ajax', 'middleware' => 'web'], function() {

  Route::get('getState', [
    'as' => 'get_state',
    'uses' => 'AjaxController@getState'
  ]
);

  Route::get('getCity', [
    'as' => 'get_city',
    'uses' => 'AjaxController@getCity'
  ]
);

    // Save Email Subscription
  Route::post('save-email-subscription', [
    'as' => 'save_email_subscription',
    'uses' => 'AjaxController@saveEmailSubscription'
  ]);

    // Save Email Subscription
  Route::post('otp-generate', [
    'as' => 'otp_generate',
    'uses' => 'AjaxController@otpGenerate'
  ]);

    // Save Email Subscription
  Route::post('otp-confirm', [
    'as' => 'otp_confirm',
    'uses' => 'AjaxController@otpConfirm'
  ]);

    // Check Email Subscription Exist or Not
  Route::post('check-email-subscription', [
    'as' => 'check_email_subscription',
    'uses' => 'AjaxController@checkEmailSubscription'
  ]);

  /* Newsletter */
  Route::post('get-newsletters', [
    'as' => 'get_newsletters',
    'uses' => 'Admin\Ajax\AjaxNewsletterController@getNewsletterList'
  ]);

  /* Newsletter */
  Route::post('delete-newsletters', [
    'as' => 'delete_newsletters',
    'uses' => 'Admin\Ajax\AjaxNewsletterController@deleteNewsletter'
  ]);

  /* delete poc details */
  Route::post('delete-poc', [
    'as' => 'delete_poc',
    'uses' => 'AjaxController@removePocDetails'
  ]);

    // Send Bulk Newsletter
  Route::post('send-newsletter', [
    'as' => 'send_newsletter',
    'uses' => 'Admin\Ajax\AjaxNewsletterController@sendNewsletter'
  ]);
});


Auth::routes();

Route::group(['middleware' => ['web', 'site-setting']], function () {

 Route::any('/home','HomeController@index')->name('home');

 Route::get('/','HomeController@index')->name('welcome');

 Route::get('/about-us', 'CmsController@aboutUs')->name('about.us');
 Route::get('/faq', 'CmsController@faq')->name('faq');

 //Route::get('faq', 'HomeController@faq')->name('faq');

 Route::get('/privacy-policy', 'CmsController@privacyPolicy')->name('privacy.policy');

 Route::get('/terms-conditions', 'CmsController@termCondition')->name('terms.conditions');

 

 Route::post('contactus', 'HomeController@contactus')->name('contactus');

 Route::post('/contact/us', 'CmsController@saveContactUs')->name('contact.us');

 Route::get('join-us/{organization_id?}', 'JoinUsController@index')->name('joinus');
 Route::post('join-us', 'JoinUsController@saveInfo')->name('saveinfo');
 Route::get('plan-detail/{organization_id}', 'JoinUsController@selectPlan')->name('plan');
 Route::get('payment', 'JoinUsController@payment')->name('payment');
 Route::post('payment/process', 'JoinUsController@paymentProcess');
 Route::get('thankyou/{plan_id}', 'JoinUsController@thankYou');

});

//Auth::routes();

//Route::get('/', 'HomeController@index')->name('home');
