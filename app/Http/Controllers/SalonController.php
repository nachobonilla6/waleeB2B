<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalonController extends Controller
{
    public function index()
    {
        $treatments = [
            [
                'id' => 1,
                'name' => 'Corte de Cabello',
                'duration' => '45 min',
                'price' => 250,
                'image' => 'https://images.unsplash.com/photo-1605497788044-5a32c7078486?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'
            ],
            [
                'id' => 2,
                'name' => 'Tinte',
                'duration' => '2 horas',
                'price' => 500,
                'image' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'
            ],
            [
                'id' => 3,
                'name' => 'Manicure',
                'duration' => '30 min',
                'price' => 150,
                'image' => 'https://images.unsplash.com/photo-1604654894610-d4ff3c124daf?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'
            ],
            [
                'id' => 4,
                'name' => 'Pedicure',
                'duration' => '45 min',
                'price' => 200,
                'image' => 'https://images.unsplash.com/photo-1604656854863-cc9f3ed0d017?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60'
            ]
        ];

        $timeSlots = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
            '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'
        ];

        return view('salon.index', compact('treatments', 'timeSlots'));
    }
}
