<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Destination;
use App\Models\Trip;
use App\Services\TravelPlannerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TripPlanningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic destinations for tests
        Destination::create([
            'name' => 'Hunza Valley',
            'description' => 'Beautiful Hunza mountains and lakes.',
            'best_season' => 'May to October',
            'estimated_cost' => 15000.00,
            'coordinates' => '36.3167,74.6500'
        ]);
    }

    /**
     * Test that the Planning Wizard renders successfully.
     */
    public function test_planning_wizard_form_renders(): void
    {
        $response = $this->get(route('planner.form'));

        $response->assertStatus(200);
        $response->assertSee('AI Trip Planner Wizard');
    }

    /**
     * Test that standard budget optimization logic successfully saves and generates itineraries.
     */
    public function test_trip_planning_success(): void
    {
        $destination = Destination::first();

        $response = $this->post(route('planner.plan'), [
            'destination_id' => $destination->id,
            'budget' => 60000,
            'days' => 3,
            'travel_type' => 'friends'
        ]);

        $trip = Trip::latest()->first();

        $this->assertNotNull($trip);
        $this->assertEquals(60000, $trip->budget);
        $this->assertEquals(3, $trip->days);
        $this->assertEquals($destination->id, $trip->destination_id);

        // Assert redirect to detail show page
        $response->assertRedirect(route('planner.show', $trip->id));
    }
}
