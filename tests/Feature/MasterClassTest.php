<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Craft;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterClassTest extends TestCase
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

    public function test_master_can_create_master_class(): void
    {
        $this->actingAs($this->master);

        $response = $this->post('/master-class', [
            'craft_id' => $this->craft->id,
            'title' => 'Test Class',
            'description' => 'Description',
            'date' => now()->addDay()->toDateString(),
            'time_slot' => '9-11',
            'max_participants' => 10,
            'price' => 500,
        ]);

        $response->assertRedirect('/cabinet');
        $this->assertDatabaseHas('master_classes', ['title' => 'Test Class']);
    }


   public function test_master_cannot_create_conflicting_slot(): void
{
    $this->actingAs($this->master);
    $date = now()->addDay()->toDateString();

    $existing = MasterClass::create([
        'craft_id' => $this->craft->id,
        'master_id' => $this->master->id,
        'title' => 'Existing',
        'description' => 'Desc',
        'date' => $date,
        'time_slot' => '9-11',
        'max_participants' => 5,
        'price' => 100,
    ]);

    $this->assertDatabaseHas('master_classes', ['id' => $existing->id]);

    $response = $this->from(route('master-class.create'))
        ->post('/master-class', [
            'craft_id' => $this->craft->id,
            'title' => 'Conflict',
            'description' => 'Desc',
            'date' => $date,
            'time_slot' => '9-11',
            'max_participants' => 10,
            'price' => 500,
        ]);

    $response->dumpSession(); // посмотреть, есть ли ошибки
    $response->assertSessionHasErrors('time_slot');
}
}