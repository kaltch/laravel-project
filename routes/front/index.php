<?php
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Front\FrontMemberController;
use App\Http\Controllers\Front\FrontPropertyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'home']);
Route::get('/login', [FrontController::class, 'login']);
Route::post('/login', [FrontController::class, 'doLogin']);
Route::get('/logout', [FrontController::class, 'logout']);
Route::get('/signup', [FrontController::class, 'signup']);
Route::post('/signup', [FrontController::class, 'doSignup']);

// Route::get('/about', [FrontController::class, 'about']);
// Route::get('/host', [FrontController::class, 'host']);
// Route::get('/blog', [FrontController::class, 'blog']);
// Route::get('/advanced', [FrontController::class, 'advanced']);

Route::get('/about', function () {
    return response()->view('Front.error400', [], 404);
});
Route::get('/host', function () {
    return response()->view('Front.error400', [], 404);
});
Route::get('/blog', function () {
    return response()->view('Front.error400', [], 404);
});
Route::get('/advanced', function () {
    return response()->view('Front.error400', [], 404);
});

Route::get('/error/404', [FrontController::class, 'error404']);
Route::get('/error/400', [FrontController::class, 'error400']);

Route::group(['prefix' => '/properties'], function(){
    // /properties/get-data?location={city_name}
    Route::get('/get-data', [FrontPropertyController::class, 'listByCity']);
    // /properties/search?location={city_name}&checkin={10/1/2024}&checkout{10/1/2024}=&adults=2&children=0&pets=0
    Route::get('/search', [FrontPropertyController::class, 'search']);
    // /properties/search/url
    Route::get('/search/url', [FrontPropertyController::class, 'searchByUrl']);
    // /properties/detail/{{ $data->listing_id }}
    Route::get('/detail/{id}', [FrontPropertyController::class, 'getDetail']);
});

Route::group(['prefix' => '/member'], function(){
    // /member/wishlist
    Route::get('/wishlist', [FrontMemberController::class, 'getWishlist']);
    Route::post('/wishlist', [FrontMemberController::class, 'addToWishlist']);
    Route::post('/wishlist/delete', [FrontMemberController::class, 'deleteFromWishlist']);

    // /member/profile
    Route::get('/profile', [FrontMemberController::class, 'getProfile']);
    // /member/profile/account/update
    Route::post('/profile/account/update', [FrontMemberController::class, 'updateProfile']);
    // /member/profile/password/password
    Route::post('/profile/password/update', [FrontMemberController::class, 'updatePassword']);
});

Route::fallback(function () {
    return response()->view('Front.error404', [], 404);
});