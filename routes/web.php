<?php
Route::get('/', function () { return redirect('/home'); });

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('auth.login');
$this->post('login', 'Auth\LoginController@login')->name('auth.login');
$this->post('logout', 'Auth\LoginController@logout')->name('auth.logout');

// Change Password Routes...
$this->get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
$this->patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.password.reset');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('auth.password.reset');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset')->name('auth.password.reset');
Route::post('/upload', 'DocViewerAPI@up')->name('up');
Route::get('/DOCSAPI/down', 'DocViewerAPI@down')->name('down');
Route::get('/DOCSAPI/details', 'DocViewerAPI@detail')->name('detail');
Route::get('/DOCS', 'DocViewer@index')->name('doc');
Route::get('/DOCSAPI', 'DocViewerAPI@search')->name('docAPI');
Route::get('/upload', 'DocViewer@upload')->name('load');
Route::get('/images/{imageName?}', 'DocViewerAPI@getImage')->name('load');
Route::get('/testInComingQuery', 'DocViewerAPI@queryTest')->name('load');
