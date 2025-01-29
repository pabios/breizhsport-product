<?php

namespace App\Domain\LearnRockTest;

class FizzBuzz
{
    public function getOutput(int $_leNombre): string
    {
        if ($_leNombre % 3 === 0) {
            return 'Fizz';
        }

        if ($_leNombre % 5 === 0) {
            return 'Buzz';
        }

        if ($_leNombre % 3 === 0 && $_leNombre % 5 === 0) {
            return 'FizzBuzz';
        }
        return (string) $_leNombre;
    }
}
