<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReadyOfferController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PrestataireController;
use App\Http\Controllers\EventCategoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\CustomizedEventController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route protégée : récupérer l'utilisateur connecté
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Déconnexion : protégé aussi par auth:sanctum
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return response()->json($request->user());
});

// Route pour la demande de réinitialisation
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
    ->middleware('guest')
    ->name('password.email');

// Route pour le traitement du formulaire de réinitialisation
Route::post('/reset-password', [PasswordResetController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');

// Route pour vérifier le token (optionnelle)
Route::get('/reset-password/{token}', [PasswordResetController::class, 'verifyToken'])
    ->middleware('guest')
    ->name('password.reset');

Route::get('/admin/dashboard', [AdminController::class, 'dashboardStats']);

Route::middleware(['auth:sanctum', 'role:client'])->group(function () {
    Route::get('/client/dashboard', function () {
        return response()->json(['message' => 'Bienvenue Client']);
    });
});

//Ajouter / Modifier / Supprimer un événement:

Route::post('/admin/events', [AdminController::class, 'addEvent']);
Route::put('/admin/events/{id}', [AdminController::class, 'editEvent']);
Route::delete('/admin/events/{id}', [AdminController::class, 'deleteEvent']);
Route::get('/admin/events', [AdminController::class, 'getEvents']);

//Les routes d "user"

Route::get('/users', [UserController::class, 'index']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);
Route::put('/users/{user}/suspend', [UserController::class, 'suspend']);
Route::put('/users/{user}/assign-prestataire', [UserController::class, 'assignPrestataire']);

Route::put('/admin/users/{id}/reactivate', [UserController::class, 'reactivateUser']);
Route::put('/admin/users/{id}/remove-provider-role', [UserController::class, 'removeProviderRole']);

// 📌 Récupérer toutes les réservations
Route::get('/admin/reservations', [ReservationController::class, 'index']);

// 📌 Modifier le statut d'une réservation (service ou événement)
Route::put('/admin/reservations/{id}/{type}', [ReservationController::class, 'updateStatus']);

// 📌 Voir les détails d'une réservation (service ou événement)
Route::get('/admin/reservations/{id}/{type}', [ReservationController::class, 'show']);

// ready offers :

// Récupérer toutes les offres prêtes
Route::get('/admin/ready-offers', [ReadyOfferController::class, 'index']);

// Créer une nouvelle offre prête
Route::post('/admin/ready-offers', [ReadyOfferController::class, 'store']);

// Récupérer les détails d'une offre prête
Route::get('ready-offers/{id}', [ReadyOfferController::class, 'show']);

// Modifier une offre prête
Route::put('admin/ready-offers/{id}', [ReadyOfferController::class, 'update']);

// Supprimer une offre prête
Route::delete('admin/ready-offers/{id}', [ReadyOfferController::class, 'destroy']);

// Suivi des réservations d'une offre prête
Route::post('/admin/ready-offers/{id}/bookings', [ReadyOfferController::class, 'storeBooking']);
Route::get('admin/ready-offers/{id}/bookings', [ReadyOfferController::class, 'bookings']);

Route::get('/admin/ready-offers/services', [ReadyOfferController::class, 'getServices']);
Route::get('/admin/ready-offers/services', [ServiceController::class, 'index']);
Route::get('admin/ready-offers-get/{id}', [ReadyOfferController::class, 'update']);

Route::get('/admin/total-revenue', [ReadyOfferController::class, 'totalRevenue']);
Route::get('/admin/ready-offers/{id}/reviews', [ReadyOfferController::class, 'getReadyOfferReviews']);
Route::delete('/admin/reviews/{id}', [ReadyOfferController::class, 'deleteReview']);
Route::get('/prestataires/{id}/historique', [PrestataireController::class, 'historique']);
Route::get('/admin/prestataires', [UserController::class, 'getPrestataires']);

Route::post('/admin/services', [ServiceController::class, 'store']);

Route::get('/admin/services', [ServiceController::class, 'index']);

Route::put('/admin/services/{id}/toggle-active', [ServiceController::class, 'toggleActive']);
// Prestataire :
Route::get('/prestataire/{id}/dashboard', [PrestataireController::class, 'dashboard']);

Route::get('/categories/create', [AdminController::class, 'createCategory']);
Route::post('/admin/categories', [AdminController::class, 'storeCategory']);

Route::get('/prestataires', [PrestataireController::class, 'index']);

// routes/api.php
Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/prestataire/{id}/services', [ServiceController::class, 'servicesByPrestataire']);

//res by services for pres :
Route::get('/prestataire/services/{id}/reservations', [ReservationController::class, 'getByService']);

// Route pour afficher le profil prestataire
Route::get('/prestataire/profile/{id}', [UserController::class, 'getProfile']);

// Route pour mettre à jour le profil prestataire
Route::put('/prestataire/profile/{id}', [UserController::class, 'updateProfile']);

Route::get('/categories/{id}/services', [ServiceController::class, 'getServicesByCategory']);

Route::post('/reservations', [ReservationController::class, 'store']);

Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::get('/events/category/{slug}', [EventCategoryController::class, 'show']);



Route::post('/customized-events', [CustomizedEventController::class, 'store']);
Route::post('/services/by-categories', [ServiceController::class, 'getServicesByCategories']);
Route::get('/categories', [CategoryController::class, 'index']);
