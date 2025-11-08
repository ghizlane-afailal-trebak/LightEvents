<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\BookingEvent;
use App\Models\Review;
use App\Models\Service;
use App\Models\BookingService;
use App\Models\Category;


class AdminController extends Controller
{
    public function dashboardStats()
    {
        return response()->json([
            'total_events' => Event::count(),
            'total_bookings' => BookingEvent::count() + BookingService::count(),
            'pending_bookings' => BookingService::where('status', 'pending')->count(),
            'confirmed_bookings' => BookingService::where('status', 'confirmed')->count(),
            'cancelled_bookings' => BookingService::where('status', 'canceled')->count(),
            'total_users' => User::count(),
            'active_providers' => User::where('role', 'prestataire')->count(),
            'total_revenue' => BookingService::where('status', 'confirmed')->sum('price'),
            'recent_bookings' => BookingService::with('user', 'service')->latest()->limit(5)->get(),
            'alerts' => [
                'new_providers' => User::where('role', 'prestataire')->where('created_at', '>=', now()->subDays(7))->count(),
                'negative_reviews' => Review::where('rating', '<=', 2)->count(),
                'late_providers' => Service::where('updated_at', '<', now()->subDays(30))->count(),
            ]
        ]);
    }

    public function addEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'category' => 'required|string',
            'services' => 'array', // Array of service IDs
        ]);

        // Create the event
        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'category' => $request->category,
        ]);

        // Associate services (if any)
        if ($request->has('services')) {
            $event->services()->sync($request->services);
        }

        return response()->json(['message' => 'Événement ajouté avec succès', 'event' => $event]);
    }


    public function editEvent(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'category' => 'required|string',
            'services' => 'array', // Array of service IDs
        ]);

        // Update event details
        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'category' => $request->category,
        ]);

        // Sync services
        if ($request->has('services')) {
            $event->services()->sync($request->services);
        }

        return response()->json(['message' => 'Événement modifié avec succès', 'event' => $event]);
    }


    public function deleteEvent($id)
    {
        $event = Event::findOrFail($id);

        // Delete associated services first
        $event->services()->detach();

        // Delete the event
        $event->delete();

        return response()->json(['message' => 'Événement supprimé avec succès']);
    }

    public function getEvents()
{
    return response()->json(Event::all());
}

public function createCategory()
{
    // Get all prestataires (users with the 'prestataire' role)
    $prestataires = User::where('role', 'prestataire')->get();

    return view('admin.create-category', compact('prestataires'));
}

public function storeCategory(Request $request)
{
    // Validate the input
    $request->validate([
        'name' => 'required|string|max:255',
        'prestataire_id' => 'required|exists:users,id'
    ]);

    // Create the new category
    $category = Category::create([
        'name' => $request->name,
        'prestataire_id' => $request->prestataire_id
    ]);

    return response()->json([
        'message' => 'Category created successfully!',
        'category' => $category
    ]);
}


}
