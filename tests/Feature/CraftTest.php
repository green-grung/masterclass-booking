<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Craft;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CraftTest extends TestCase
{
    use RefreshDatabase;

    public function test_craft_page_loads(): void
    {
        $craft = Craft::factory()->create();
        $response = $this->get(route('craft.show', $craft));
        $response->assertStatus(200);
        $response->assertViewIs('craft.show');
        $response->assertViewHas('craft', $craft);
    }

    public function test_craft_page_shows_master_classes(): void
    {
        $craft = Craft::factory()->create();
        $master = User::factory()->create(['role' => 'master']);
        $mc = MasterClass::factory()->create([
            'craft_id' => $craft->id,
            'master_id' => $master->id,
        ]);

        $response = $this->get(route('craft.show', $craft));
        $response->assertSee($master->name);
        $response->assertSee($master->description);
    }

    public function test_authenticated_user_sees_register_button(): void
    {
        $user = User::factory()->create();
        $this->assertInstanceOf(User::class, $user);
        $this->actingAs($user);

        $craft = Craft::factory()->create();
        $master = User::factory()->create(['role' => 'master']);
        $mc = MasterClass::factory()->create([
            'craft_id' => $craft->id,
            'master_id' => $master->id,
            'max_participants' => 5,
        ]);

        $response = $this->get(route('craft.show', $craft));
        $response->assertSee('записаться');
    }

    public function test_guest_does_not_see_register_button(): void
    {
        $craft = Craft::factory()->create();
        $master = User::factory()->create(['role' => 'master']);
        $mc = MasterClass::factory()->create([
            'craft_id' => $craft->id,
            'master_id' => $master->id,
        ]);

        $response = $this->get(route('craft.show', $craft));
        $response->assertSee('Войдите для записи');
        $response->assertDontSee('записаться');
    }
}
