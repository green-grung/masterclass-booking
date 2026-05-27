<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Craft;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private MasterClass $masterClass;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'user']);
        $master = User::factory()->create(['role' => 'master']);
        $craft = Craft::factory()->create();
        $this->masterClass = MasterClass::factory()->create([
            'master_id' => $master->id,
            'craft_id' => $craft->id,
            'max_participants' => 2,
        ]);
    }

    public function test_user_can_register_for_master_class(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('registration.confirm', $this->masterClass));
        $response->assertStatus(200);
        $response->assertViewIs('registration.confirm');
        $response->assertViewHas('masterClass', $this->masterClass);
        $response->assertViewHas('user', $this->user);

        $postResponse = $this->post(route('registration.store', $this->masterClass), [
            'action' => 'confirm',
        ]);
        $postResponse->assertRedirect(route('craft.show', $this->masterClass->craft_id));
        $this->assertDatabaseHas('registrations', [
            'user_id' => $this->user->id,
            'master_class_id' => $this->masterClass->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_user_cannot_register_twice(): void
    {
        $this->actingAs($this->user);
        // Первая запись
        $this->post(route('registration.store', $this->masterClass), ['action' => 'confirm']);

        // Попытка второй записи
        $response = $this->get(route('registration.confirm', $this->masterClass));
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Вы уже записаны на этот мастер-класс.');
    }

    public function test_cannot_register_when_no_places(): void
    {
        // Заполняем места
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $this->masterClass->update(['max_participants' => 2]);

        $this->assertInstanceOf(User::class, $user2);
        $this->actingAs($user2);
        $this->post(route('registration.store', $this->masterClass), ['action' => 'confirm']);
        $this->assertInstanceOf(User::class, $user3);
        $this->actingAs($user3);
        $this->post(route('registration.store', $this->masterClass), ['action' => 'confirm']);

        // Теперь мест нет
        $this->actingAs($this->user);
        $response = $this->get(route('registration.confirm', $this->masterClass));
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Нет свободных мест.');
    }

    public function test_user_cancel_registration(): void
    {
        $this->actingAs($this->user);
        $response = $this->post(route('registration.store', $this->masterClass), [
            'action' => 'cancel',
        ]);
        $response->assertRedirect(route('craft.show', $this->masterClass->craft_id));
        $response->assertSessionHas('info', 'Запись отменена.');
        $this->assertDatabaseMissing('registrations', [
            'user_id' => $this->user->id,
            'master_class_id' => $this->masterClass->id,
        ]);
    }
}
