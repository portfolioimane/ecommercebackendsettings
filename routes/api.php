<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\CouponController;
use App\Http\Controllers\Frontend\PromotionController;
use App\Http\Controllers\Frontend\MailchimpController;
use App\Http\Controllers\Frontend\SubscribersController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\ProfileController;




use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderControler;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController; 
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\ShippingAreaController as AdminShippingAreaController;
use App\Http\Controllers\Admin\HomePageHeaderController as AdminHomePageHeaderController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\LogoController as AdminLogoController;
use App\Http\Controllers\Admin\SettingsController;





// routes/api.php




use App\Http\Controllers\AuthController;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);


Route::middleware('auth:sanctum')->group(function () {
    // Dashboard route
    Route::get('admin/dashboard', [DashboardController::class, 'index']);



Route::prefix('admin/shipping-areas')->group(function () {
    Route::get('/', [AdminShippingAreaController::class, 'index']);       // List all shipping areas
    Route::post('/', [AdminShippingAreaController::class, 'store']);      // Create a new shipping area
    Route::get('/{id}', [AdminShippingAreaController::class, 'show']);    // Show a specific shipping area
    Route::put('/{id}', [AdminShippingAreaController::class, 'update']);  // Update a shipping area
    Route::put('/{id}/status', [AdminShippingAreaController::class, 'toggleStatus']);  // Update a shipping area

    Route::delete('/{id}', [AdminShippingAreaController::class, 'destroy']); // Delete a shipping area
});


    // Product routes
    Route::prefix('admin/products')->group(function () {
        Route::get('/', [AdminProductController::class, 'index']);
        Route::post('/', [AdminProductController::class, 'store']);
        Route::get('/{id}', [AdminProductController::class, 'show']);
        Route::put('/{id}', [AdminProductController::class, 'update']);
        Route::delete('/{id}', [AdminProductController::class, 'destroy']);
    });

    // Order routes
    Route::prefix('admin/orders')->group(function () {
        Route::get('/', [AdminOrderControler::class, 'index']);
        Route::get('/{id}', [AdminOrderControler::class, 'show']);
        Route::put('/{id}', [AdminOrderControler::class, 'update']);
        Route::delete('/{id}', [AdminOrderControler::class, 'destroy']);
    });

    Route::prefix('admin/coupons')->group(function () {
        Route::get('/', [AdminCouponController::class, 'index']);
        Route::post('/', [AdminCouponController::class, 'store']);
        Route::get('/{id}', [AdminCouponController::class, 'show']);
        Route::put('/{id}', [AdminCouponController::class, 'update']);
        Route::delete('/{id}', [AdminCouponController::class, 'destroy']);
    });

    // User routes
    Route::prefix('admin/users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });
    Route::prefix('admin/subscribers')->group(function () {
        Route::get('/', [SubscribersController::class, 'index']);
    });

    // Category routes
    Route::prefix('admin/categories')->group(function () {
        Route::get('/', [AdminCategoryController::class, 'index']);
        Route::post('/', [AdminCategoryController::class, 'store']);
        Route::get('/{id}', [AdminCategoryController::class, 'show']);
        Route::put('/{id}', [AdminCategoryController::class, 'update']);
        Route::delete('/{id}', [AdminCategoryController::class, 'destroy']);
    });


Route::prefix('admin/variants')->group(function () {
    Route::get('/', [ProductVariantController::class, 'index']);
    Route::post('/create', [ProductVariantController::class, 'create']);
    Route::get('/{id}', [ProductVariantController::class, 'show']);
    Route::delete('/{id}', [ProductVariantController::class, 'destroy']);
    Route::put('/edit/{id}', [ProductVariantController::class, 'update']); // Change this line
});


    

    // Review routes
    Route::prefix('admin/reviews')->group(function () {
        Route::get('/', [AdminReviewController::class, 'index']);
        Route::put('/{id}', [AdminReviewController::class, 'update']);
        Route::put('/{id}/feature', [AdminReviewController::class, 'feature']);
        Route::delete('/{id}', [AdminReviewController::class, 'destroy']);
        Route::post('/', [AdminReviewController::class, 'store']);

    });

    // Settings routes


    Route::prefix('admin/promotions')->group(function () {

       Route::post('/', [AdminPromotionController::class, 'store']);
    Route::get('/', [AdminPromotionController::class, 'index']);     
    Route::get('/{id}', [AdminPromotionController::class, 'show']);   
    Route::put('/{id}', [AdminPromotionController::class, 'update']);  
    Route::patch('/toggle-status/{id}', [AdminPromotionController::class, 'toggleStatus']);
    Route::delete('/{id}', [AdminPromotionController::class, 'destroy']); 
      });
 
    Route::prefix('admin/customize')->group(function () {
        Route::put('/homepage-header', [AdminHomePageHeaderController::class, 'update']);
        Route::get('/homepage-header', [AdminHomePageHeaderController::class, 'getHeader']);
        Route::get('/logos', [AdminLogoController::class, 'index']);
        Route::post('/logos', [AdminLogoController::class, 'store']);
       
    });

Route::group(['prefix' => 'admin/settings'], function () {
    Route::get('stripe', [SettingsController::class, 'getStripeSettings']);
    Route::post('stripe', [SettingsController::class, 'updateStripeSettings']);

    Route::get('paypal', [SettingsController::class, 'getPayPalSettings']);
    Route::post('paypal', [SettingsController::class, 'updatePayPalSettings']);

    Route::get('pusher', [SettingsController::class, 'getPusherSettings']);
    Route::post('pusher', [SettingsController::class, 'updatePusherSettings']);

    Route::get('mailchimp', [SettingsController::class, 'getMailchimpSettings']);
    Route::post('mailchimp', [SettingsController::class, 'updateMailchimpSettings']);
});

});

Route::get('categories', [CategoryController::class, 'index']); // View all products


Route::get('/', [HomeController::class, 'index']); // Home page
Route::get('products', [ProductController::class, 'index']); // View all products
Route::get('products/{id}', [ProductController::class, 'show']); // View a specific product

Route::get('/reviews/featured', [ReviewController::class, 'getFeaturedReviews']);
Route::get('/products/{productId}/reviews', [ReviewController::class, 'index']);

Route::get('/homepage-header', [HomeController::class, 'getHeader']);
Route::get('/promotions', [PromotionController::class, 'index']);
Route::post('/mailchimp/subscribe', [MailchimpController::class, 'subscribe']);
// routes/api.php

Route::post('/contact', [ContactController::class, 'store']);

Route::get('/contacts', [ContactController::class, 'index']);



// Cart routes
// Cart routes
Route::prefix('cart')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [CartController::class, 'index']); // Get cart items
    Route::post('/addtocart/{id}', [CartController::class, 'store']); // Add item to cart using product ID in the URL
    Route::delete('/{id}', [CartController::class, 'destroy']); 
});


// Order routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/orders', [OrderController::class, 'getUserOrders']);
    Route::get('/profile', [ProfileController::class, 'getProfile']);

    Route::put('/profile', [ProfileController::class, 'updateUser']);


    Route::post('/orders', [OrderController::class, 'store']);

   Route::get('/orders/{id}', [OrderController::class, 'show']);
   Route::post('/payment/create-intent', [OrderController::class, 'createPaymentIntent']);
    Route::post('/paypal/capture', [OrderController::class, 'capturePayPalPayment']);
   
    Route::post('/payment', [OrderController::class, 'payment']);

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/remove/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    

    Route::post('/reviews', [ReviewController::class, 'store']);

    Route::post('/coupons/apply', [CouponController::class, 'applyCoupon']);


});





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

