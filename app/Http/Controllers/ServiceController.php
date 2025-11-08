<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller

{
    public function index()
    {
        $services = Service::with('category')->get(); // ⬅ on charge la relation 'category'
        return response()->json($services);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'pricing_type' => 'required|string',
            'price' => 'nullable|numeric',
            'price_per_person' => 'nullable|numeric',
            'price_per_table' => 'nullable|numeric',
            'image' => 'nullable|string',
            'is_active' => 'boolean',
            'prestataire_id' => 'required|exists:users,id', // Vérification que le prestataire existe
            'category_id' => 'required|exists:categories,id'

        ]);

        $service = Service::create($request->all());

        return response()->json($service, 201);
    }

    public function show($id)
{
    $service = Service::with('reviews')->findOrFail($id);

    return response()->json([
        'id' => $service->id,
        'title' => $service->title,
        'description' => $service->description,
        'image' => $service->image,
        'pricing_type' => $service->pricing_type,
        'price' => $service->price,
        'price_per_person' => $service->price_per_person,
        'price_per_table' => $service->price_per_table,
        'rating' => round($service->reviews->avg('rating'), 1),
        'reviews' => $service->reviews->take(3)->map(function ($review) {
            return [
                'author' => $review->author_name ?? 'Utilisateur',
                'comment' => $review->comment,
                'rating' => $review->rating,
            ];
        })
    ]);
}


    public function toggleActive($id, Request $request)
{
    $service = Service::findOrFail($id);
    $service->is_active = $request->input('is_active');
    $service->save();

    return response()->json(['message' => 'Statut mis à jour', 'service' => $service]);
}

//
public function servicesByPrestataire($prestataireId)
{
    $services = Service::where('prestataire_id', $prestataireId)
        ->with('category')
        ->get();

    return response()->json($services);
}

public function getServicesByCategories(Request $request)
{
    $categoryIds = $request->input('categories', []);

    if (empty($categoryIds)) {
        return response()->json([], 200); // Retourne vide si aucune catégorie sélectionnée
    }

    $services = Service::whereIn('category_id', $categoryIds)
        ->where('is_active', true)
        ->with(['reviews', 'user']) // optionnel : si tu veux le prestataire
        ->get()
        ->map(function ($service) {
            return [
                'id' => $service->id,
                'title' => $service->title,
                'description' => $service->description,
                'image' => $service->image,
                'pricing_type' => $service->pricing_type,
                'price' => $service->price,
                'price_per_person' => $service->price_per_person,
                'price_per_table' => $service->price_per_table,
                'average_rating' => round($service->reviews->avg('rating'), 1),
                'reviews' => $service->reviews->take(3)->map(function ($review) {
                    return [
                        'author' => $review->author_name ?? 'Utilisateur',
                        'comment' => $review->comment,
                        'rating' => $review->rating,
                    ];
                })
            ];
        });

    return response()->json($services);
}

public function getServicesByCategory($id)
{
    $services = Service::where('category_id', $id)
        ->where('is_active', true)
        ->with(['reviews', 'user']) // si tu veux aussi le prestataire
        ->get()
        ->map(function ($service) {
            return [
                'id' => $service->id,
                'title' => $service->title,
                'description' => $service->description,
                'image' => $service->image,
                'pricing_type' => $service->pricing_type,
                'price' => $service->price,
                'price_per_person' => $service->price_per_person,
                'price_per_table' => $service->price_per_table,
                'average_rating' => round($service->reviews->avg('rating'), 1),
                'reviews' => $service->reviews->take(3)->map(function ($review) {
                    return [
                        'author' => $review->author_name ?? 'Utilisateur',
                        'comment' => $review->comment,
                        'rating' => $review->rating,
                    ];
                })
            ];
        });


    return response()->json($services);
}


}
