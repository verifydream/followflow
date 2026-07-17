<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LeadControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_leads(): void
    {
        $user = User::factory()->create();
        Lead::factory()->count(3)->create(['user_id' => $user->id]);

        // Leads for other user
        Lead::factory()->count(2)->create();

        $response = $this->actingAs($user)->getJson('/api/leads');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_user_can_create_lead(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/leads', [
            'name' => 'John Doe',
            'phone' => '1234567890',
            'email' => 'john@example.com',
            'company' => 'Acme Corp',
            'value' => 5000,
            'notes' => 'Test notes',
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('name', 'John Doe');

        $this->assertDatabaseHas('leads', [
            'user_id' => $user->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    public function test_user_can_show_lead(): void
    {
        $user = User::factory()->create();
        $lead = Lead::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson("/api/leads/{$lead->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('id', $lead->id)
                 ->assertJsonPath('name', $lead->name);
    }

    public function test_user_can_delete_lead(): void
    {
        $user = User::factory()->create();
        $lead = Lead::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson("/api/leads/{$lead->id}");

        $response->assertStatus(200)
                 ->assertJson(['ok' => true]);

        $this->assertDatabaseMissing('leads', [
            'id' => $lead->id,
        ]);
    }

    public function test_user_can_mark_lead_contacted(): void
    {
        $user = User::factory()->create();
        $lead = Lead::factory()->create([
            'user_id' => $user->id,
            'last_contact' => now()->subDays(10),
        ]);

        $response = $this->actingAs($user)->postJson("/api/leads/{$lead->id}/contacted");

        $response->assertStatus(200);

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
        ]);

        $updatedLead = Lead::find($lead->id);
        $this->assertTrue($updatedLead->last_contact->gt(now()->subMinutes(1)));
    }

    public function test_user_can_view_dashboard(): void
    {
        $user = User::factory()->create();

        // New lead
        Lead::factory()->create([
            'user_id' => $user->id,
            'status' => 'new',
            'value' => 1000,
            'last_contact' => now(),
        ]);

        // Stale lead
        Lead::factory()->create([
            'user_id' => $user->id,
            'status' => 'new',
            'value' => 2000,
            'last_contact' => now()->subDays(6),
        ]);

        // Other user lead
        Lead::factory()->create([
            'status' => 'new',
            'value' => 5000,
            'last_contact' => now()->subDays(6),
        ]);

        $response = $this->actingAs($user)->getJson('/api/dashboard');

        $response->assertStatus(200)
                 ->assertJson([
                     'total' => 2,
                     'new' => 2,
                     'stale' => 1,
                     'total_value' => 3000,
                 ]);
    }
}
