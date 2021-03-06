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


//for admin panel home page + employee login
Route::get('/admin','homeController@adminHomepage');


Route::get('/', 'homeController@homepage');
Route::get('/home', 'homeController@homepage');
Route::get('/getDepartureTime/{date}', 'homeController@getDepartureTime');

//customer login, logout
Route::get('logout', 'MyController@logout'); // logout
Route::get('sign-in', 'MyController@showLogin'); // login
Route::post('sign-in', 'MyController@doLogin'); // login

//agents login, logout
Route::get('agent-logout', 'MyController@agentLogout'); // logout
Route::get('agent-sign-in', 'MyController@agentShowLogin'); // login
Route::post('agent-sign-in', 'MyController@agentDoLogin'); // login
Route::get('after-agent-register', 'MyController@agentRegister'); // login

//agents login, logout
Route::get('employee-logout', 'MyController@employeeLogout'); // logout
Route::get('employee-sign-in', 'MyController@employeeShowLogin'); // login
Route::post('employee-sign-in', 'MyController@employeeDoLogin'); // login
Route::get('employee-home', 'MyController@employeeHome'); // employee-home

//representatives login, logout
Route::get('representative-logout', 'MyController@reptLogout'); // logout
Route::get('representative-sign-in', 'MyController@reptShowLogin'); // login
Route::post('representative-sign-in', 'MyController@reptDoLogin'); // login
Route::get('representative-home', 'MyController@reptHome'); // representative-home

//admins login, logout
Route::get('admin-logout', 'MyController@adminLogout'); // logout
Route::get('admin-sign-in', 'MyController@adminShowLogin'); // login
Route::post('admin-sign-in', 'MyController@adminDoLogin'); // login
Route::get('admin-home', 'MyController@adminHome'); // admin-home
Route::get('admin/admin-create', 'MyController@adminCreate'); // admin-create / register
Route::post('admin/admin-store', 'MyController@adminStore'); // admin store

Route::get('admin-representative-list','AdminActivityController@adminGetReptList');
Route::get('admin-agent-list','AdminActivityController@adminGetAgentList');
Route::get('admin-profile/{id}','AdminActivityController@adminProfile');


//super admins login, logout
Route::get('super-admin-logout', 'MyController@superAdminLogout'); // logout
Route::get('super-admin-sign-in', 'MyController@superAdminShowLogin'); // login
Route::post('super-admin-sign-in', 'MyController@superAdminDoLogin'); // login
Route::get('super-admin-home', 'MyController@superAdminHome'); // super-admin-home

Route::get('confirm-ticket/{id}', 'MyController@adminConfirmTicket'); // login

// customer login from seat list
Route::get('login-from-seatlist/{id}', 'MyController@loginFrom'); // login from buslist / seatlist
Route::post('login-from-seatlist/{id}', 'MyController@loginFromSeat'); // login from buslist / seatlist

Route::get('places','MyController@places' ); // places in controller


Route::resource('user','UserController'); // sign up, show profile, edit profile by users

Route::resource('agent','AgentController'); // sign up, show profile, edit profile by agents

Route::resource('representative','RepresentativeController'); // sign up, show profile, edit profile by representatives

Route::resource('bus','BusController');

Route::resource('employee','EmployeeController'); // sign up, show profile, edit profile by employees

// for user/customer
Route::post('search-buses','BusSearchController@search_bus'); // bus list without filter
Route::post('search-buses-with-filter','BusSearchController@search_bus_filter'); // bus list with filter
Route::get('/seat-list-details/{id}', 'BusSearchController@seat_list'); //seat list details
Route::post('booking-details/{id}/{tripID}', 'BusSearchController@booking'); //booking details
Route::post('payment-details/{id}/{tripID}', 'BusSearchController@payment'); //payment details

Route::get('/payment-info/{id}', 'BusSearchController@showPayment'); //seat list details

Route::post('confirm-ticket/{id}/{tripID}','BusSearchController@confirmTicket'); // confirm ticket

// for agent
Route::post('agent-search-buses-with-filter','BusSearchController@agent_search_bus_filter'); // bus list with filter
Route::get('agent-search-buses','BusSearchController@agent_search_bus'); // bus list with filter
Route::get('agent-seat-list-details/{id}', 'BusSearchController@agent_seat_list'); //seat list details
Route::post('agent-booking-details/{id}/{tripID}', 'BusSearchController@agent_booking'); //booking details
Route::post('agent-confirm-ticket/{id}/{tripID}/{userID}','BusSearchController@agent_confirmTicket'); // confirm ticket
Route::get('agent-confirm-ticket/{id}/{tripID}/{userID}','BusSearchController@send_from'); // confirm ticket

// may be search ticket by admin
Route::get('search-ticket','BusSearchController@search_ticket'); // bus ticket with filter
Route::post('search-ticket-with-filter','BusSearchController@search_ticket_filter'); // bus ticket with filter

// for printing tickets, cancellation
Route::get('show-ticket/{id}/{ticketID}','TicketPrintController@showTicket'); // show ticket
Route::get('download-ticket/{id}/{ticketID}','TicketPrintController@downloadTicket'); // download ticket
Route::get('cancel-ticket/{ticketID}','TicketPrintController@cancelTicket'); // cancel ticket
Route::post('cancel-ticket/{ticketID}','TicketPrintController@cancelTicketConfirm'); // cancel ticket
Route::get('cancel-refund-policy','TicketPrintController@cancelRefundPolicy'); // cancel-refund-policy ticket


// for ajax call
Route::get('/get-status/{id}','AjaxlController@getSeatStatus'); // get status of seat
Route::get('/update-status/{id}/{status}/{userID}','AjaxlController@updateStatus'); // update status of seat
Route::get('/get-userID/{id}','AjaxlController@getUserID'); // get userID
Route::get('/get-gender/{id}','AjaxlController@getUserGender'); // get user gender to show in seat list
Route::get('/get-seat-list/{id}','AjaxlController@getSeatList'); // get seat list to show in profile

Route::get('/get-bus-layout/{id}','AjaxlController@getBusLayout'); // get bus layout to show in rep. bus list
Route::get('/get-username','AjaxlController@getUsername'); // get session value

Route::get('/get-name-gender/{id}','AjaxlController@getNameGender'); // get name gender of user
//update-ticket-status/active/'+id+"/"+empID
Route::get('/update-ticket-status/{active}/{id}/{empID}','AjaxlController@updateTicketStatus'); // update ticket status - active

// for representative activities
Route::get('/representative-buses/{id}','RepActivityController@getBusList');// get buses of respective operator
Route::post('/representative-buses-with-filter/{id}','RepActivityController@getFilteredBusList');// get buses of respective operator
Route::get('/representative-add-buses/{id}','RepActivityController@addNewBus');// add new buses of respective operator
Route::post('/representative-add-buses-preview/{id}','RepActivityController@addNewBusPreview');// preview add new buses of respective operator
Route::post('/representative-edit-buses/{id}/{busID}','RepActivityController@editBus');// edit buses of respective operator
Route::get('/representative-trips/{id}','RepActivityController@search_trips');// get trips of respective operator
Route::get('/representative-add-trips-form/{id}','RepActivityController@addNewTripForm');// add new trips of respective operator
Route::post('/representative-edit-trips/{id}/{tripID}','RepActivityController@editTrip');// edit buses of respective operator
Route::post('/representative-add-trips/{id}','RepActivityController@addNewTrip');// edit buses of respective operator
Route::get('/representative-availability/{id}','RepActivityController@availability');// show available bus, trip,
Route::get('/representative-seats/{id}','RepActivityController@showSeat');// show seats
Route::post('/representative-seats/{id}','RepActivityController@showBusSeat');// show seats
Route::post('/representative-seats-edit/{id}','RepActivityController@editBusSeat');// edit seats

// --------------------------- mosaddek ---------------------------//

Route::get('representative-places', 'RepActivityController@reptPlaces');
Route::get('representative-place-details/{p_id}', 'RepActivityController@reptPlaceDetails');
Route::get('representative-place-edit/{p_id}', 'RepActivityController@reptPlaceEdit'); // representative-home
Route::post('representative-place-update/{p_id}','RepActivityController@reptUpdatePlace');
Route::get('representative-add-place','RepActivityController@reptAddPlace');
Route::post('representative-add-place','RepActivityController@reptStorePlace');

Route::get('routes_view', 'RepActivityController@reptRoute');
Route::get('representative-routes', 'RepActivityController@reptRoute');
Route::get('representative-route-details/{route_id}', 'RepActivityController@reptRouteDetails');
Route::get('representative-route-edit/{route_id}', 'RepActivityController@reptRouteEdit');
Route::post('representative-route-update/{route_id}','RepActivityController@reptUpdateRoute');
Route::get('rept-search-route','RepActivityController@reptSearchRoute');
Route::get('/live_search/routes', 'RepActivityController@reptLiveSearchRoute')->name('live_search.routes');
Route::get('rept-add-route','RepActivityController@reptAddRoute');
Route::post('rept-add-route','RepActivityController@reptStoreRoute');


//ajax for admin
Route::get('admin-confirm-Rept/{id}','AdminActivityController@adminReptConfirm');
Route::get('admin-cancel-Rept/{id}','AdminActivityController@adminReptCancel');
Route::get('admin-confirm-Agent/{id}','AdminActivityController@adminAgentConfirm');
Route::get('admin-cancel-Agent/{id}','AdminActivityController@adminAgentCancel');


// employee activities employee-search-ticket-with-filter
Route::get('/employee-ticket-list','EmployeeActivityController@getTicketList');// get tickets
Route::post('/employee-search-ticket-with-filter','EmployeeActivityController@getFilteredTicketList');// get tickets


Route::get('/profile', function () {
    return view('user.profile');
});

Route::get('/admin-home-nnnnnnn', function () {
    return view('admin.New folder.index');
});

Route::get('/customers_view-mm', function () {
    return view('admin.customers_view');
});

Route::get('/bookings_view-mm', function (Request $request,$id,$tripID) {
    return view('admin.bookings_view');
});