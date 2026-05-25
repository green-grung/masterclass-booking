<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\MasterClass;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterClassTest extends TestCase
{
    use RefreshDatabase;

    public function test_available_places_calculates_correctly(): void
    {
        $masterClass = MasterClass::factory()->create(['max_participants' => 5]);
        $users = User::factory()->count(3)->create();

        foreach ($users as $user) {
            Registration::create([
                'user_id' => $user->id,
                'master_class_id' => $masterClass->id,
                'status' => 'confirmed',
            ]);
        }

        $this->assertEquals(2, $masterClass->availablePlaces());
    }

    public function test_is_user_registered_returns_true(): void
    {
        $user = User::factory()->create();
        $masterClass = MasterClass::factory()->create();

        Registration::create([
            'user_id' => $user->id,
            'master_class_id' => $masterClass->id,
            'status' => 'confirmed',
        ]);

        $this->assertTrue($masterClass->isUserRegistered($user->id));
    }
}