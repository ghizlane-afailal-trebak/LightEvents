<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Service;
use App\Models\ReadyOffer;
use App\Models\Category;

class EventCategoryController extends Controller
{

// use App\Models\Event;

public function show($categoryName)
{
    // Fetch ready offers based on the 'categorie' field
    $readyOffers = ReadyOffer::where('categorie', $categoryName)->get();

    // Example media for category (could be dynamic later if needed)
    $media = [
        'type' => 'video', // or 'image'
        'url' => asset("media/{$categoryName}.mp4") // Or .jpg for images
    ];

    return response()->json([
        'ready_offers' => $readyOffers,
        'media' => $media,
    ]);
}

}
