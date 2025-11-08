<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CustomizedEvent;
use App\Models\CustomizedEventCategory;
use App\Models\CustomizedEventService;
use App\Models\Service;

class CustomizedEventController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'event_type' => 'required|string',
            'date' => 'required|date',
            'person_count' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
            'categories' => 'required|array',
            'services' => 'required|array',
            'user_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        // Create the event
        $event = CustomizedEvent::create([
            'event_type' => $data['event_type'],
            'date' => $data['date'],
            'person_count' => $data['person_count'],
            'capacity' => $data['capacity'],
            'status' => 'pending',
            'user_name' => $data['user_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        ]);

        // Attach categories
        $event->categories()->attach($data['categories']);

        $totalPrice = 0;
        $servicesDetails = [];

        // Attach services with price calculation
        foreach ($data['services'] as $serviceId) {
            $service = Service::find($serviceId);
            if (!$service) continue;

            $price = 0;
            $note = '';

            if ($service->pricing_type === 'global') {
                $price = $service->price;
                $note = 'Prix global';
            } elseif ($service->pricing_type === 'per_person') {
                $price = $service->price_per_person * $data['person_count'];
                $note = 'Prix par personne';
            } elseif ($service->pricing_type === 'per_table') {
                $tables = ceil($data['person_count'] / 8);
                $price = $service->price_per_table * $tables;
                $note = "Prix par table (~$tables tables)";
            }

            CustomizedEventService::create([
                'customized_event_id' => $event->id,
                'service_id' => $serviceId,
                'calculated_price' => $price,
                'calculation_note' => $note,
            ]);

            $totalPrice += $price;

            $servicesDetails[] = [
                'title' => $service->title,
                'pricing_type' => $service->pricing_type,
                'note' => $note,
                'price' => $price,
            ];
        }

        // Update event price
        $event->update(['final_price' => $totalPrice]);

        // Generate PDF
        $pdf = Pdf::loadView('pdf.customized-event-receipt', [
            'event' => $event,
            'services' => $servicesDetails,
            'totalPrice' => $totalPrice,
        ]);

        $fileName = 'receipt_' . Str::random(10) . '.pdf';
        $pdf->save(storage_path("app/public/receipts/{$fileName}"));

        return response()->json([
            'success' => true,
            'event_id' => $event->id,
            'pdf_url' => asset("storage/receipts/{$fileName}"),
        ]);
    }
}
