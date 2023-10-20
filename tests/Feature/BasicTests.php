<?php

it('cannot access .env files by users', function () {
    expect($this->get('/.env')->status())->toBe(200);
    expect($this->get('/.env.example')->status())->toBe(404);
    expect($this->get('/.env.production')->status())->toBe(404);
});

it('can access robots.txt file', function () {
    expect($this->get('/robots.txt')->status())->toBe(200);
});
