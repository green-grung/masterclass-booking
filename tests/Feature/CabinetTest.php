<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Craft;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CabinetTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_cabinet(): void
    {
        $response = $this->get(route('cabinet'));
        $response->assertRedirect(route('login'));
    }

    public function test_user_without_master_role_cannot_access_cabinet(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->assertInstanceOf(User::class, $user);
        $this->actingAs($user);
        $response = $this->get(route('cabinet'));
        $response->assertRedirect(route('home'));
    }

    public function test_master_can_access_cabinet(): void
    {
        $master = User::factory()->create(['role' => 'master']);
        $this->assertInstanceOf(User::class, $master);
        $this->actingAs($master);

        $response = $this->get(route('cabinet'));
        $response->assertStatus(200);
        $response->assertViewIs('cabinet');
        $response->assertViewHas('user', $master);
    }

    public function test_master_sees_only_his_master_classes(): void
    {
        $master1 = User::factory()->create(['role' => 'master']);
        $master2 = User::factory()->create(['role' => 'master']);
        $craft = Craft::factory()->create();

        $mc1 = MasterClass::factory()->create([
            'master_id' => $master1->id,
            'craft_id' => $craft->id,
        ]);
        $mc2 = MasterClass::factory()->create([
            'master_id' => $master2->id,
            'craft_id' => $craft->id,
        ]);

        $this->assertInstanceOf(User::class, $master1);
        $this->actingAs($master1);
        $response = $this->get(route('cabinet'));
        $response->assertSee($mc1->title);
        $response->assertDontSee($mc2->title);
    }
}
