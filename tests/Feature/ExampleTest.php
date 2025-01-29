<?php

test('example', function () {
    expect(true)->toBeTrue();
});

function sum(int $int, int $int1)
{
    return $int1 + $int;
}

test('sum', function () {
    $result = sum(1, 2);

    expect($result)->toBe(3);
});