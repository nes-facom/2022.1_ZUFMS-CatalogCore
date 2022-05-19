<?php

use App\Services\AccessCodeService;

uses(Tests\TestCase::class);

beforeEach(function (){

});

it('should create alphanumeric code with length 5', function(){
    $service = new AccessCodeService();
    $accessCodeValue = $service->getPassword();

    expect(preg_match('/^([A-Za-z0-9._%+-]){5}$/', $accessCodeValue))->toBe(1);
});
