<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Craft;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterClassControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $master;

    private Craft $craft;

    protected function setUp(): void
    {
        parent::setUp();
        $this->master = User::factory()->create(['role' => 'master']);
        $this->craft = Craft::factory()->create();
    }

    public function test_master_can_edit_his_master_class(): void
    {
        $this->actingAs($this->master);
        $mc = MasterClass::factory()->create([
            'master_id' => $this->master->id,
            'craft_id' => $this->craft->id,
        ]);

        $response = $this->get(route('master-class.edit', $mc));
        $response->assertStatus(200);
        $response->assertViewIs('master-class.form');
    }

    public function test_master_cannot_edit_others_master_class(): void
    {
        $otherMaster = User::factory()->create(['role' => 'master']);
        $this->actingAs($this->master);

        $mc = MasterClass::factory()->create([
            'master_id' => $otherMaster->id,
            'craft_id' => $this->craft->id,
        ]);

        $response = $this->get(route('master-class.edit', $mc));
        $response->assertForbidden(); // 403
    }

    public function test_master_can_update_description_and_price(): void
    {
        $this->actingAs($this->master);
        $mc = MasterClass::factory()->create([
            'master_id' => $this->master->id,
            'craft_id' => $this->craft->id,
            'description' => 'Old description',
            'price' => 100,
        ]);

        $response = $this->put(route('master-class.update', $mc), [
            'description' => 'New description',
            'price' => 200,
        ]);

        $response->assertRedirect(route('cabinet'));
        $this->assertDatabaseHas('master_classes', [
            'id' => $mc->id,
            'description' => 'New description',
            'price' => 200,
        ]);
    }

    public function test_master_can_view_participants(): void
    {
        $this->actingAs($this->master);
        $mc = MasterClass::factory()->create([
            'master_id' => $this->master->id,
            'craft_id' => $this->craft->id,
        ]);
        $user = User::factory()->create();
        $mc->registrations()->create([
            'user_id' => $user->id,
            'status' => 'confirmed',
        ]);

        $response = $this->get(route('master-class.participants', $mc));
        $response->assertStatus(200);
        $response->assertViewIs('master-class.participants');
        $response->assertSee($user->name);
    }
}
