<?php

it('can access privacy policy page', function () {
    expect($this->get(route('privacy-policy'))->status())->toBe(200);
});

it('can access terms and conditions page', function () {
    expect($this->get(route('terms-and-conditions'))->status())->toBe(200);
});
