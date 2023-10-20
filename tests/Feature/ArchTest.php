<?php

// https://pestphp.com/docs/arch-testing

test('globals')
    ->expect(['dd', 'dump'])
    ->not->toBeUsed()
    ->ignoring('App\Http\Controller\TestController');

test('Enums')
    ->expect('App\Enums')
    ->toBeEnums();

test('Interfaces')
    ->expect('App\Interfaces')
    ->toBeInterfaces();

test('Actions')
    ->expect('App\Actions')
    ->toBeInvokable();

test('Concerns')
    ->expect('App\Concerns')
    ->toBeTraits();

test('Controllers has suffix of Controller')
    ->expect('App\Http\Controllers')
    ->toHaveSuffix('Controller');

test('Listeners has suffix of Listener')
    ->expect('App\Http\Listeners')
    ->toHaveSuffix('Listener');

test('Events has suffix of Event')
    ->expect('App\Http\Events')
    ->toHaveSuffix('Event');

test('DTOs has suffix of DTO')
    ->expect('App\Http\DTOs')
    ->toHaveSuffix('DTO');
