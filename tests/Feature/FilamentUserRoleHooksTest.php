<?php

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'doctor']);
    Role::create(['name' => 'asistente']);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    test()->actingAs($admin);
});

// Test 1: Verifica sincronizacion de rol al crear usuario.
it('sincroniza roles al crear usuario desde el hook afterCreate', function (): void {
    Livewire::test(CreateUser::class)
        ->set('data.name', 'Usuario Hook Create')
        ->set('data.email', 'hook-create@example.com')
        ->set('data.password', 'password123')
        ->set('data.roles', ['doctor'])
        ->set('data.is_active', true)
        ->call('create')
        ->assertHasNoErrors();

    $createdUser = User::where('email', 'hook-create@example.com')->first();

    expect($createdUser)->not->toBeNull();
    expect($createdUser->hasRole('doctor'))->toBeTrue();
});

// Test 2: Verifica reemplazo de rol al editar usuario.
it('sincroniza roles al editar usuario desde el hook afterSave', function (): void {
    $user = User::factory()->create([
        'email' => 'hook-edit@example.com',
    ]);
    $user->assignRole('doctor');

    Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
        ->set('data.roles', ['asistente'])
        ->call('save')
        ->assertHasNoErrors();

    $user->refresh();

    expect($user->hasRole('asistente'))->toBeTrue();
    expect($user->hasRole('doctor'))->toBeFalse();
});
