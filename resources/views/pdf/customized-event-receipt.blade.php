<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de réservation</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; line-height: 1.5; }
        h2 { text-align: center; }
        ul { list-style-type: none; padding: 0; }
    </style>
</head>
<body>
    <h2>🎉 Reçu de réservation personnalisée</h2>

    <p><strong>Nom :</strong> {{ $event->user_name }}</p>
    <p><strong>Email :</strong> {{ $event->email }}</p>
    <p><strong>Téléphone :</strong> {{ $event->phone }}</p>
    <p><strong>Type d'événement :</strong> {{ $event->event_type }}</p>
    <p><strong>Date :</strong> {{ $event->date }}</p>
    <p><strong>Nombre de personnes :</strong> {{ $event->person_count }}</p>
    <p><strong>Capacité :</strong> {{ $event->capacity }}</p>

    <h4>🛎 Services sélectionnés :</h4>
    <ul>
        @foreach ($services as $service)
            <li>
                {{ $service['title'] }} ({{ $service['note'] }}) — {{ number_format($service['price'], 2) }} DA
            </li>
        @endforeach
    </ul>

    <p><strong>Total :</strong> {{ number_format($totalPrice, 2) }} DA</p>

    <p style="margin-top: 20px;">Merci pour votre confiance ✨</p>
</body>
</html>
