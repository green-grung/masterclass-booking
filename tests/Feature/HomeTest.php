<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Craft;
use App\Models\MasterClass;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    public function test_home_page_shows_crafts_menu(): void
    {
        $crafts = Craft::factory()->count(3)->create();
        $response = $this->get('/');
        foreach ($crafts as $craft) {
            $response->assertSee($craft->name);
        }
    }

    public function test_authenticated_user_sees_their_registrations(): void
    {
        $user = User::factory()->create();
        $master = User::factory()->create(['role' => 'master']);
        $craft = Craft::factory()->create();
        $mc = MasterClass::factory()->create([
            'master_id' => $master->id,
            'craft_id' => $craft->id,
        ]);
        Registration::create([
            'user_id' => $user->id,
            'master_class_id' => $mc->id,
            'status' => 'confirmed',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->actingAs($user);
        $response = $this->get('/');
        $response->assertSee($mc->title);
    }
}
