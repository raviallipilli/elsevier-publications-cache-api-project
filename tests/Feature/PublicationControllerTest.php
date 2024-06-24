<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Publication;
use Illuminate\Support\Facades\Http;

class PublicationControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that a publication is returned from the cache if it exists.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_publication_from_cache()
    {
        $doi = '10.2000/xyz';
        $data = [
            'status' => 'ok', 
            'message-type' => 'work-agency', 
            'message-version' => '1.0.0', 
            'message' => [
                'DOI' => $doi, 
                'agency' => [
                    'id' => 'crossref', 
                    'label' => 'Crossref'
                    ]
                ]
            ];
        
        // Seed the database
        Publication::create([
            'doi' => $doi,
            'data' => json_encode($data),
        ]);

        // Make a GET request to the API with the DOI
        $response = $this->get('/publication/works/?doi=' . $doi);
        
        // Assert that the response status is 200 OK
        $response->assertStatus(200);

        // Assert that the response JSON matches the data
        $response->assertJson($data);
    }

    /**
     * Test that a publication is fetched from CrossRef and stored in the cache if it does not exist in the local cache.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_fetches_publication_from_crossref_and_stores_in_cache()
    {
        $doi = '10.1038/nature12373';

        // Mock the HTTP request to CrossRef
        Http::fake([
            "https://api.crossref.org/works/{$doi}" => Http::response([
                'status' => 'ok',
                'message-type' => 'work',
                'message-version' => '1.0.0',
                'message' => [
                    'DOI' => $doi,
                    'agency' => [
                        'id' => ['crossref'],
                        'label' => ['Crossref'],
                    ],
                ],
            ], 200),
        ]);

        // Make a GET request to the API with the DOI
        $response = $this->get('/publication/works/?doi=' . $doi);

        // Assert that the response status is 200 OK
        $response->assertStatus(200);

        // Assert that the response JSON structure matches the expected format
        $response->assertJsonStructure([
            'status',
            'message-type',
            'message-version',
            'message' => [
                'DOI',
                'agency' => [
                    'id',
                    'label',
                ],
            ],
        ]);

        // Check if the publication is stored in the database
        $this->assertDatabaseHas('publications', ['doi' => $doi]);
    }
}
