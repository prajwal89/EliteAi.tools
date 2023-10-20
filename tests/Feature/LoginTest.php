<?php

it('can see login page', function () {
    expect($this->get(route('auth.login'))->status())->toBe(200);
});
