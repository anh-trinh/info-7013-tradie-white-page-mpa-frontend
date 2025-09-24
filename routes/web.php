
<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// === MPA PUBLIC ROUTES ===

// Page 1: Homepage
$router->get('/', function () {
    return view('pages.public.homepage');
});

// Page 4: Search Results Page
$router->get('/search', function () {
    // Search logic will be added later
    return view('pages.public.search-results');
});

// Page 5: Tradie Details Page
$router->get('/tradie/{id}', function ($id) {
    return view('pages.public.tradie-details', ['id' => $id]);
});

// Admin Login Page (MPA)
$router->get('/admin/login', function () {
    return view('pages.admin.admin-login');
});

$router->get('/register', function () {
    return view('pages.public.register');
});

$router->get('/login', function () {
    return view('pages.public.login');
});

// === SPA ENTRY ROUTE ===
// This route will be the entry for your Vue.js SPA later.
// Any unmatched URL will be redirected here.
$router->get('/{any:.*}', function () {
    return view('spa-entry');
});
