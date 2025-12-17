<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Employee\OrderController;
use App\Http\Controllers\Employee\OrderStatusController;
use App\Http\Controllers\Employee\PaymentHistoryController;
use App\Http\Controllers\FoodCategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Vendor\VendorFoodController;
use App\Http\Controllers\Vendor\VendorMealController;
use App\Http\Controllers\Vendor\VendorOrderController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localize', 'localizationRedirect', 'localeSessionRedirect', 'localeViewPath']
    ],
    function () {

        Route::middleware(['auth'])->group(function () {

            Route::get('dashboard', [DashboardController::class, 'index'])
                ->name('dashboard')->middleware('permission:dashboard');

            // Admin Settings
            Route::middleware(['permission:settings.view'])->group(function () {

                Route::get('settings', [SettingsController::class, 'general'])
                    ->name('settings');

                Route::get('settings/logo', [SettingsController::class, 'logo'])
                    ->name('settings.logo');

                Route::get('settings/contact', [SettingsController::class, 'contact'])
                    ->name('settings.contact');
            });


            // Profile
            Route::get('profile', [ProfileController::class, 'index'])
                ->middleware('permission:profile.update')
                ->name('profile');

            // Roles
            Route::get('roles', [RoleController::class, 'index'])
                ->middleware('permission:role.view')
                ->name('roles');

            Route::get('roles/create', [RoleController::class, 'create'])
                ->middleware('permission:role.create')
                ->name('roles.create');

            Route::get('roles/edit', [RoleController::class, 'edit'])
                ->middleware('permission:role.edit')
                ->name('roles.edit');

            // Create User
            Route::get('users', [UserController::class, 'index'])
                ->middleware('permission:user.view')
                ->name('users');

            Route::get('users/create', [UserController::class, 'create'])
                ->middleware('permission:user.create')
                ->name('users.create');

            Route::post('users/store', [UserController::class, 'store'])
                ->middleware('permission:user.create')
                ->name('users.store');

            Route::get('users/edit/{id}', [UserController::class, 'edit'])
                ->middleware('permission:user.edit')
                ->name('users.edit');

            Route::put('users/update/{id}', [UserController::class, 'update'])
                ->middleware('permission:user.edit')
                ->name('users.update');

            Route::post('users/update/status/{id}', [UserController::class, 'changeStatus'])
                ->name('users.status');

            Route::get('users/show/{id}', [UserController::class, 'show'])->name('users.show');



            // Food Categories
            Route::get('food_category', [FoodCategoryController::class, 'index'])
                ->middleware('permission:food_category.view')
                ->name('food_category');

            Route::get('food_category/create', [FoodCategoryController::class, 'create'])
                ->middleware('permission:food_category.create')
                ->name('food_category.create');

            Route::post('food_category/store', [FoodCategoryController::class, 'store'])
                ->middleware('permission:food_category.create')
                ->name('food_category.store');

            Route::get('food_category/edit/{id}', [FoodCategoryController::class, 'edit'])
                ->middleware('permission:food_category.edit')
                ->name('food_category.edit');

            Route::put('food_category/update/{id}', [FoodCategoryController::class, 'update'])
                ->middleware('permission:food_category.edit')
                ->name('food_category.update');

            Route::delete('food_category/delete/{id}', [FoodCategoryController::class, 'destroy'])
                ->middleware('permission:food_category.delete')
                ->name('food_category.delete');


            // Create Team
            Route::get('teams', [TeamController::class, 'index'])
                ->middleware('permission:team.view')
                ->name('teams');

            Route::get('teams/create', [TeamController::class, 'create'])
                ->middleware('permission:team.create')
                ->name('teams.create');

            Route::post('teams/store', [TeamController::class, 'store'])
                ->middleware('permission:team.create')
                ->name('teams.store');

            Route::get('teams/edit/{id}', [TeamController::class, 'edit'])
                ->middleware('permission:team.edit')
                ->name('teams.edit');

            Route::put('teams/update/{id}', [TeamController::class, 'update'])
                ->middleware('permission:team.edit')
                ->name('teams.update');

            Route::delete('teams/delete/{id}', [TeamController::class, 'destroy'])
                ->middleware('permission:team.delete')
                ->name('teams.delete');



            // Vendor Pages
            Route::prefix('vendor')->group(function () {

                // Vendor Order Status
                Route::get('orders/{status}', [VendorOrderController::class, 'index'])
                    ->middleware('permission:vendor.order.status')
                    ->name('vendor.orders.status');

                Route::post('vendor/order/status/{id}', [VendorOrderController::class, 'updateStatus'])
                    ->name('vendor.order.status.update');

                Route::post('order/mark_paid/{id}', [VendorOrderController::class, 'markPaid'])
                    ->middleware('permission:vendor.order.status.paid')
                    ->name('vendor.order.markPaid');

                Route::get('order/details', [VendorOrderController::class, 'orderDetails'])
                    ->middleware('permission:vendor.order.status.details')
                    ->name('vendor.order.details');

                Route::get('orders/export/{order_status}', [VendorOrderController::class, 'exportMealPdf'])
                    ->name('vendor.orders.export.pdf');

                // Vendor Food Management
                Route::get('food', [VendorFoodController::class, 'index'])
                    ->middleware('permission:vendor.food.view')
                    ->name('vendor.food');

                Route::get('food/create', [VendorFoodController::class, 'create'])
                    ->middleware('permission:vendor.food.create')
                    ->name('vendor.food.create');

                Route::post('food/store', [VendorFoodController::class, 'store'])
                    ->middleware('permission:vendor.food.create')
                    ->name('vendor.food.store');

                Route::get('food/edit/{id}', [VendorFoodController::class, 'edit'])
                    ->middleware('permission:vendor.food.update')
                    ->name('vendor.food.edit');

                Route::put('food/update/{id}', [VendorFoodController::class, 'update'])
                    ->middleware('permission:vendor.food.edit')
                    ->name('vendor.food.update');

                Route::delete('food/delete/{id}', [VendorFoodController::class, 'destroy'])
                    ->middleware('permission:vendor.food.delete')
                    ->name('vendor.food.delete');

                Route::post('food/set_today_meal', [VendorFoodController::class, 'set_today_meal'])
                    ->middleware('permission:vendor.food.set_meal')
                    ->name('vendor.food.set_today_meal');

                Route::post('vendor/today-meal/delete', [VendorFoodController::class, 'delete'])
                    ->name('vendor.food.delete_today_meal');

                Route::post('food/stock_update', [VendorFoodController::class, 'restock_today_meal'])
                    ->middleware('permission:vendor.food.stock_update')
                    ->name('vendor.food.stock.update');



                // Vendor Meal History
                Route::get('meal/history', [VendorMealController::class, 'history'])
                    ->middleware('permission:vendor.meal_history')
                    ->name('vendor.meal.history');


                Route::get('meal/payment_history', [VendorMealController::class, 'payment_history'])
                    ->middleware('permission:vendor.payment_history')
                    ->name('vendor.meal.payment_history');
            });





            // Employee Pages
            Route::prefix('employee')->group(function () {

                // Employee Order Food
                Route::get('make_order', [OrderController::class, 'make_order'])
                    ->middleware('permission:employee.make_order')
                    ->name('order.make_order');

                Route::get('make_order/vendor_list/{id}', [OrderController::class, 'vendor_list'])
                    ->middleware('permission:employee.vendor_list')
                    ->name('order.make_order.vendor_list');

                Route::post('order', [OrderController::class, 'order'])
                    ->middleware('permission:employee.place_order')
                    ->name('employee.order');

                Route::get('orders/{status}', [OrderStatusController::class, 'index'])
                    ->middleware('permission:employee.order.status')
                    ->name('employee.orders.status');

                // Employee Order Cancel
                Route::post('order/cancel/{id}', [OrderStatusController::class, 'cancel'])
                    ->middleware('permission:employee.order.cancel')
                    ->name('order.cancel');

                // Employee Payment
                Route::get('payment_history', [PaymentHistoryController::class, 'payment_history'])
                    ->middleware('permission:employee.payment_history')
                    ->name('employee.payment_history');
            });
        });
    }
);
