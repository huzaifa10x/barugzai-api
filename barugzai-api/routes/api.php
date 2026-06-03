<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ManufacturerController;
use App\Http\Controllers\API\CarModelController;
use App\Http\Controllers\API\SellYourCarController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\CarController;
use App\Http\Controllers\API\ImageSliderController;
use App\Http\Controllers\API\SectionController;
use App\Http\Controllers\API\BlogCategoryController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\AboutOurCompanyController;
use App\Http\Controllers\API\WhatWeOfferController;
use App\Http\Controllers\API\SellerController;
use App\Http\Controllers\API\SocialMediaLinkController;
use App\Http\Controllers\API\AboutUsController;
use App\Http\Controllers\API\MetaDataApiController;
use App\Http\Controllers\API\ContactApiController;


Route::get('/meta-data/{slug}', [MetaDataApiController::class, 'getBySlug']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('manufacturers')->group(function () {
    Route::get('/', [ManufacturerController::class, 'index']); // List all manufacturers
    Route::post('/', [ManufacturerController::class, 'store']); // Create a manufacturer
    Route::get('/{id}', [ManufacturerController::class, 'show']); // Show a specific manufacturer
    Route::put('/{id}', [ManufacturerController::class, 'update']); // Update a manufacturer
    Route::delete('/{id}', [ManufacturerController::class, 'destroy']); // Delete a manufacturer
    Route::post('/by-manufacturer', [ManufacturerController::class, 'getModelsByManufacturer']);
});

Route::prefix('car-models')->group(function () {
    Route::get('/', [CarModelController::class, 'index']); // List all car models
    Route::post('/', [CarModelController::class, 'store']); // Create a car model
    Route::get('/{id}', [CarModelController::class, 'show']); // Show a specific car model
    Route::put('/{id}', [CarModelController::class, 'update']); // Update a car model
    Route::delete('/{id}', [CarModelController::class, 'destroy']); // Delete a car model

});

Route::prefix('sell-your-car')->group(function () {
    Route::get('/', [SellYourCarController::class, 'index']); // List all records
    Route::post('/', [SellYourCarController::class, 'store']); // Create a record
    Route::get('/{id}', [SellYourCarController::class, 'show']); // View a record
    Route::put('/{id}', [SellYourCarController::class, 'update']); // Update a record
    Route::delete('/{id}', [SellYourCarController::class, 'destroy']); // Delete a record
});

Route::prefix('services')->group(function () {
    Route::get('/', [ServiceController::class, 'index']);
    Route::post('/', [ServiceController::class, 'store']);
    Route::get('/{id}', [ServiceController::class, 'show']);
    Route::put('/{id}', [ServiceController::class, 'update']);
    Route::delete('/{id}', [ServiceController::class, 'destroy']);
    Route::post('/header', [ServiceController::class, 'updateHeader']); // Update service header text

});

Route::prefix('cars')->group(function () {
    Route::get('/', [CarController::class, 'getAllCars']);
    Route::post('/', [CarController::class, 'store']);
    Route::get('/{id}', [CarController::class, 'show']);
    Route::put('/{id}', [CarController::class, 'update']);
    Route::delete('/{id}', [CarController::class, 'destroy']);
    Route::post('/{id}/images', [CarController::class, 'updateImages']);
    Route::get('/latest/cars', [CarController::class, 'getLatestCars']);
    Route::get('/latest/slider', [CarController::class, 'latestCars']);
    Route::get('/similar/{listing_id}', [CarController::class, 'getRelatedListings']);
});

Route::prefix('images')->group(function () {
    Route::post('/image-sliders', [ImageSliderController::class, 'store']); // Create a new image slider
    Route::get('/image-sliders', [ImageSliderController::class, 'index']);  // Retrieve all image sliders
    Route::delete('/image-sliders/{id}', [ImageSliderController::class, 'destroy']);
});

Route::prefix('section')->group(function () {
    Route::get('/section', [SectionController::class, 'show']);
    Route::put('/section', [SectionController::class, 'update']);
});
Route::prefix('blogs')->group(function () {
    Route::get('/', [BlogController::class, 'index']); // Get all blogs
    Route::get('/{id}', [BlogController::class, 'show']); // Get a single blog and increment views
    Route::post('/', [BlogController::class, 'store']);
    Route::delete('/{id}', [BlogController::class, 'destroy']); // Delete a category
});

Route::prefix('blog-categories')->group(function () {
    Route::get('/', [BlogCategoryController::class, 'index']); // Get all categories
    Route::get('/{id}', [BlogCategoryController::class, 'show']); // Get a single category with its blogs
    Route::post('/', [BlogCategoryController::class, 'store']); // Create a new category
    Route::put('/{id}', [BlogCategoryController::class, 'update']); // Update an existing category
    Route::delete('/{id}', [BlogCategoryController::class, 'destroy']); // Delete a category
});

Route::prefix('about')->group(function () {
    Route::get('/get', [AboutOurCompanyController::class, 'get']); // Fetch About Our Company
    Route::post('/update', [AboutOurCompanyController::class, 'update']); // Update About Our Company
});

Route::prefix('what-we-offer')->group(function () {
    Route::get('/', [WhatWeOfferController::class, 'get']); // Fetch What We Offer
    Route::post('/update', [WhatWeOfferController::class, 'update']);
});

Route::prefix('sellers')->group(function () {
    Route::get('/', [SellerController::class, 'index']); // Get all sellers
    Route::get('/{id}', [SellerController::class, 'show']); // Get a single seller
    Route::post('/', [SellerController::class, 'store']); // Create a new seller
    Route::put('/{id}', [SellerController::class, 'update']); // Update a seller
    Route::delete('/{id}', [SellerController::class, 'destroy']); // Delete a seller
});

Route::prefix('socialmedialinks')->group(function () {
    Route::get('/', [SocialMediaLinkController::class, 'get']); // Get social media links
    Route::post('/update', [SocialMediaLinkController::class, 'update']); // Update social media links
});

Route::prefix('about-us')->group(function () {
    Route::get('/', [AboutUsController::class, 'get']); // Get About Us data
    Route::post('/update', [AboutUsController::class, 'update']); // Update About Us
});

Route::get('/contact-person', [ContactApiController::class, 'index']); 

Route::get('/optimized-image/{path}', [CarController::class, 'optimizedImage'])
    ->where('path', '.*');
    
    