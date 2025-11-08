<?php

namespace App\Http\Controllers;

use App\Models\ReadyOffer;
use App\Models\ReadyOfferService;
use App\Models\ReadyOfferBooking;
use App\Models\ReadyOfferReview;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReadyOfferController extends Controller
{
    // Create a new ready offer
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'original_price' => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0|lte:original_price',
            'services' => 'array',
            'categorie' => 'required|string|max:255',
        ]);

        $storedImagePath = $request->file('image')->store('offers', 'public');

        $offer = ReadyOffer::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $storedImagePath,
            'original_price' => $request->original_price ?? 0,
            'discounted_price' => $request->discounted_price ?? 0,
            'categorie' => $request->categorie,
            'admin_id' => Auth::id() ?? 1,
        ]);

        if ($request->has('services')) {
            $offer->services()->attach($request->services);
        }

        // 🔥 Ajout de l'URL publique
        $offer->image_url = asset('storage/' . $storedImagePath);

        return response()->json([
            'message' => 'Offre créée avec succès',
            'offer' => $offer,
        ]);
    }



    // Get all ready offers
    public function index()
    {
        $offers = ReadyOffer::with('services')->get();
        return response()->json($offers);
    }

    // Get details of a ready offer
    public function show($id)
    {
        $offer = ReadyOffer::with('services')->findOrFail($id);
        return response()->json($offer);
    }

    // Update a ready offer
    public function update(Request $request, $id)
    {
        $offer = ReadyOffer::findOrFail($id);

        // Validation des données
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categorie' => 'nullable|string|max:255',
        ]);

        // Mise à jour des données (sans toucher à l'image si elle n'est pas envoyée)
        $offer->title = $validatedData['title'];
        $offer->description = $validatedData['description'];

        // Mise à jour de l'image si une nouvelle est fournie
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ready_offers', 'public');
            $offer->image = $imagePath;
        }

        $offer->save(); // Sauvegarde des changements

        return response()->json($offer);
    }


    // Delete a ready offer
    public function destroy($id)
    {
        ReadyOffer::findOrFail($id)->delete();
        return response()->json(['message' => 'Offer deleted successfully']);
    }

    public function storeBooking(Request $request, $readyOfferId)
    {
        $request->validate([
            'reservation_date' => 'required|date', // Assurer que la date est fournie
            'status' => 'required|in:pending,confirmed,canceled',
        ]);

        // Vérifier si l'offre existe
        $readyOffer = ReadyOffer::findOrFail($readyOfferId);

        // Vérifier si une réservation existe déjà pour cette offre et cette date
        $existingBooking = ReadyOfferBooking::where('ready_offer_id', $readyOfferId)
            ->whereDate('reservation_date', $request->reservation_date)
            ->exists();

        if ($existingBooking) {
            return response()->json(['message' => 'Cette offre est déjà réservée pour cette date.'], 422);
        }

        // Créer la réservation
        $booking = ReadyOfferBooking::create([
            'user_id' => Auth::id(),
            'ready_offer_id' => $readyOfferId,
            'price' => $readyOffer->discounted_price, // ✅ Maintenant, la variable est définie
            'reservation_date' => $request->reservation_date,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Réservation créée avec succès', 'booking' => $booking]);
    }


    // Get bookings for a ready offer
    public function bookings($id)
    {
        $readyOffer = ReadyOffer::with('bookings.user')->findOrFail($id);
        return response()->json($readyOffer->bookings);
    }


    // Get all services
    public function getServices()
    {
        $services = Service::all();
        return response()->json($services);
    }

    public function totalRevenue()
{
    $total = ReadyOfferBooking::where('status', 'confirmed')->sum('price');
    return response()->json(['total_revenue' => $total]);
}
public function getReadyOfferReviews($offerId)
{
    $offer = ReadyOffer::with('reviews.user')->findOrFail($offerId);

    return response()->json($offer->reviews);
}

public function deleteReview($reviewId)
{
    $review = ReadyOfferReview::findOrFail($reviewId);
    $review->delete();

    return response()->json(['message' => 'Avis supprimé avec succès']);
}


}
