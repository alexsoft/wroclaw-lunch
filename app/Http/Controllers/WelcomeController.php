<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class WelcomeController extends Controller
{
    public function welcome()
    {
        if (!File::exists(storage_path('app/places.json'))) {
            File::put(
                storage_path('app/places.json'),
                json_encode(['Zupa', 'Kres', 'Kurna Chata', 'Express Oriental @ Dominikana'])
            );
        };

        $places = \Cache::remember('places', 60, function() {

            $json = File::get(storage_path('app/places.json'));

            $places = json_decode($json, true);

            return $places;

        });

//        $place = \Cache::remember('place', 60, function() use ($places) {

            $date = (new \Carbon\Carbon)->format('Y-m-d');

            $md5 = md5($date);

            $sub = substr($md5, -2);


//            return $places[$sub % count($places)];
            $place = $places[hexdec($sub) % count($places)];

//        });

        return view('welcome', compact('places', 'place'));
    }

    public function addNewPlace(Request $request)
    {
        $this->validate($request, [
            'place' => 'required'
        ]);

        $existingPlaces = json_decode(File::get(storage_path('app/places.json')), true);

        $existingPlaces[] = $request->get('place');

        File::put(storage_path('app/places.json'), json_encode($existingPlaces));

        \Cache::flush('places');
    }
}