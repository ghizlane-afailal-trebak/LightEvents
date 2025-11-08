<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Service;
use App\Models\BookingService;
use App\Models\Review;
use Illuminate\Support\Facades\Log;

class PrestataireController extends Controller
{

    public function index()
    {
        // Récupérer tous les utilisateurs avec rôle 'prestataire'
        $prestataires = User::where('role', 'prestataire')->get();
        return response()->json($prestataires);
    }
    public function historique($id)
    {
        $prestataire = User::where('id', $id)->where('role', 'prestataire')->first();

        if (!$prestataire) {
            return response()->json(['message' => 'Prestataire non trouvé'], 404);
        }

        $services = $prestataire->services()->with(['reservations', 'reviews'])->get();

        return response()->json($services);
    }

        public function dashboard($id)
        {
            // Vérifier si l'utilisateur est un prestataire
            $user = User::find($id);

            if (!$user || $user->role !== 'prestataire') {
                return response()->json(['error' => 'Utilisateur non valide ou non autorisé.'], 403);
            }

            // Récupérer les services associés à ce prestataire
            $serviceIds = Service::where('prestataire_id', $id)->pluck('id');

            // Récupérer les réservations de ces services
            $bookings = BookingService::whereIn('service_id', $serviceIds)->get();


            // Récupérer les avis liés à ces services
            $reviews = Review::whereIn('service_id', $serviceIds)->get();

            return response()->json([
                'total_bookings' => $bookings->count(),
                'confirmed_bookings' => $bookings->where('status', 'confirmed')->count(),
                'pending_bookings' => $bookings->where('status', 'pending')->count(),
                'average_rating' => $reviews->count() ? round($reviews->avg('rating'), 2) : null,
                'upcoming_reservations' => $bookings->where('date', '>=', now())->take(2)->values(),
                'recent_reviews' => $reviews->sortByDesc('created_at')->take(5)->values()
            ]);
        }
    }
