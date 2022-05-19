<?php

use App\DTO\Input\UserInputDTO;
use App\Models\User;
use App\Repository\UserRepository;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function (){

});

it('shold create user with valid email', function () {
    $service = new UserService(new UserRepository());
    $userInputDTO = new UserInputDTO('test@ufms.br');
    $isCreated = $service->createUser($userInputDTO);

    expect($isCreated)->toBeTrue();
});

it('should delete a user', function () {
    $user = User::factory()->create();
    $service = new UserService(new UserRepository());
    $userInputDTO = new UserInputDTO($user->getAttribute('email'));
    $isDeleted = $service->deleteUser($userInputDTO);

    expect($isDeleted)->toBeTrue();
});

it('should throw exception when tries to create with invalid email', function (){
    $service = new UserService(new UserRepository());
    $userInputDTO = new UserInputDTO('test@test.com');
    $service->createUser($userInputDTO);

})->throws('Email informado não é do domínio ufms.');
