<?php

namespace App\Http\Controllers;

use App\Models\League;
use Illuminate\Http\Request;
use \Symfony\Component\HttpFoundation\StreamedResponse;

class LeagueController extends Controller
{
    public function list (Request $request) {
        $query = League::query();

        if ($timestamp = $request->get('start_timestamp')) {
            $query->where('start_timestamp', '>', $timestamp);
        }

        return response()->stream(function() use($query) {
            $resource = fopen('php://output', 'w');
            fputs($resource, '{[');

            $query->chunk(500, function($leagues) use($resource) {
                fputs($resource, $leagues->pluck('id')->implode(','));
            });

            fputs($resource, ']}');
            fclose($resource);
        }, 200, ['content-type' => 'application/json']);
    }

    public function view (Request $request, League $league) {
        return response()->json(['name' => $league->name]);
    }
}
