<?php

namespace App\Tests\FizzBuzz;

use App\Domain\LearnRockTest\FizzBuzz;
use PHPUnit\Framework\TestCase;

/**
 * Une méthode GetOutput(int number) avec un paramètre d'entrée (number)
 * Cette méthode retourne un string
 * - Si c'est un multiple de 3, retourne "Fizz"
 * - Si c'est un multiple de 5, retourne "Buzz"
 * - Si c'est un multiple de 3 et 5, retourne "FizzBuzz"
 * - Si c'est multiple de ni l'un ni l'autre retourne le nombre en chaîne de caractère
 *  - Write test → Write code → Run test → Refactor :)
 * @Command php bin/phpunit
 */
class FizzBuzzTest extends TestCase
{
    private  FizzBuzz $fizzBuzz;

    protected function setUp(): void
    {
        $this->fizzBuzz = new FizzBuzz();
    }

    public function testLaClassExist():void
    {
        $this->assertTrue(class_exists(FizzBuzz::class), 'La classe FizzBuzz n\'existe pas.');
    }

    public function testRenvoisTruePourMultipleDe3():void
    {
        $this->assertEquals('Fizz', $this->fizzBuzz->getOutput(3));
    }

    public function testRenvoisTruePourMultipleDe5():void
    {
        $this->assertEquals('Buzz', $this->fizzBuzz->getOutput(5));
    }

    public function testRenvoisLeNombreEnChaine():void
    {
        $this->assertEquals('1', $this->fizzBuzz->getOutput(1));
        $this->assertEquals('2', $this->fizzBuzz->getOutput(2));
        $this->assertEquals('31', $this->fizzBuzz->getOutput(31));
    }
}



