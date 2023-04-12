<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\post;

it('should be login user', function () {
    $user = User::factory()->create();

    post(uri: '/api/login', data: [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertStatus(200);
});

it('should be return an error if email is empty', function () {
    post(uri: '/api/login', data: [
        'email' => '',
        'password' => 'password',
    ])
        ->assertSessionHasErrors(keys: ['email']);
});

it('should be return an error if email is not an email', function () {
    post(uri: '/api/login', data: [
        'email' => 'user.com',
        'password' => 'password',
    ])
        ->assertSessionHasErrors(keys: ['email']);
});

it('should be return an error if password is empty', function () {
    $user = User::factory()->create();

    post(uri: '/api/login', data: [
        'email' => $user->email,
        'password' => '',
    ])
        ->assertSessionHasErrors(keys: ['password']);
});

it('should be return an error if email is not equal to user email', function () {
    User::factory()->create();

    post(uri: '/api/login', data: [
        'email' => 'user@user.com',
        'password' => 'password',
    ])
        ->assertSessionHasErrors(keys: ['email']);
});

it('should be return an error if password is not equal to user password', function () {
    $user = User::factory()->create();

    post(uri: '/api/login', data: [
        'email' => $user->email,
        'password' => 'pass',
    ])
        ->assertSessionHasErrors(keys: ['email']);
});
