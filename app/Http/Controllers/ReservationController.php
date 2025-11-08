<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BookingService;
use App\Models\BookingEvent;
use App\Models\user;
use Illuminate\Http\Request;

class ReservationController extends Controller {
    // 📌 Récupérer toutes les réservations (avec filtrage)
    public function index(Request $request) {
        $date = $request->query('date');
        $eventType = $request->query('eventType');

        $bookingServices = BookingService::with('user', 'service')
            ->when($date, fn($query) => $query->where('date', $date))
            ->get();

        $bookingEvents = BookingEvent::with('user', 'event')
            ->when($date, fn($query) => $query->where('date', $date))
            ->when($eventType, fn($query) => $query->whereHas('event', fn($q) => $q->where('category', $eventType)))
            ->get();

        return response()->json([
            'booking_services' => $bookingServices,
            'booking_events' => $bookingEvents,
        ]);
    }

    // 📌 Mettre à jour le statut d'une réservation
    public function updateStatus(Request $request, $id, $type) {
        $request->validate(['status' => 'required|in:confirmed,canceled,pending']);

        if ($type === 'service') {
            $booking = BookingService::findOrFail($id);
        } elseif ($type === 'event') {
            $booking = BookingEvent::findOrFail($id);
        } else {
            return response()->json(['message' => 'Type de réservation invalide'], 400);
        }

        $booking->update(['status' => $request->status]);

        return response()->json(['message' => 'Statut mis à jour !', 'reservation' => $booking]);
    }

    // 📌 Voir les détails d’une réservation
    public function show($id, $type) {
        if ($type === 'service') {
            $booking = BookingService::with('user', 'service')->findOrFail($id);
        } elseif ($type === 'event') {
            $booking = BookingEvent::with('user', 'event')->findOrFail($id);
        } else {
            return response()->json(['message' => 'Type de réservation invalide'], 400);
        }

        return response()->json($booking);
    }

    public function getByService($id)
    {
        $bookings = BookingService::where('service_id', $id)
        ->with(['user:id,name,email,phone_number']) // On ne prend que les champs nécessaires
        ->get();

        return response()->json($bookings);
    }

    public function store(Request $request)
    {
        // Validation des champs
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'reservation_date' => 'required|date|after_or_equal:today',
            'service_id' => 'required|exists:services,id',
            'price' => 'required|numeric|min:0',
            'capacity' => 'nullable|integer|min:1',
        ]);
        $user = User::where('email', $validated['email'])->first();

        // Création de la réservation
        $reservation = BookingService::create([
            'user_id' => $user ? $user->id : null, // <== tu dois OBLIGATOIREMENT inclure ça
            'user_name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'reservation_date' => $validated['reservation_date'],
            'service_id' => $validated['service_id'],
            'price' => $validated['price'],
            'status' => 'en attente', // ou 'pending' selon ta logique
        ]);

        return response()->json([
            'message' => 'Réservation enregistrée avec succès !',
            'reservation' => $reservation
        ], 201);
    }
}
