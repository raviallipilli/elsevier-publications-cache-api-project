<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use Illuminate\Support\Facades\Http;

class PublicationController extends Controller
{
    /**
     * Handle incoming request to fetch publication by DOI.
     */
    public function show(Request $request)
    {
        $doi = $request->query('doi');
        if (!$doi) {
            return response()->json(['error' => 'DOI is required'], 400);
        }

        // Check local cache
        $publication = Publication::where('doi', $doi)->first();

        if ($publication) {
            return response()->json(json_decode($publication->data, true));
        }

        // Fetch from CrossRef
        $data = $this->fetchFromCrossRef($doi);
        if ($data) {
            // Store in local cache
            Publication::create([
                'doi' => $doi,
                'data' => json_encode($data),
            ]);
            return response()->json($data);
        }

        return response()->json(['error' => 'Publication not found'], 404);
    }

    /**
     * Fetch publication data from CrossRef by DOI.
     */
    private function fetchFromCrossRef($doi)
    {
        $response = Http::withOptions(['verify' => false])->get("https://api.crossref.org/works/{$doi}");
        if ($response->successful()) {
            return $response->json();
        }
        return null;
    }
}
