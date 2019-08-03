<?php
use PHPUnit\Framework\TestCase;

function sum($a, $b)
{
    return $a + $b;
}

class Math
{
    public static function getObj()
    {
        return new self();
    }

    public function devision(int $subject, int $by)
    {
        if ($by == 0) {
            throw new Exception('devision by zero');
        }

        return $subject / $by;
    }
}

class MyTest extends TestCase
{
    private $_mathObj;

    public function setUp(): void
    {
        parent::setUp();
        $this->_mathObj = new Math();
    }

    public function testSum()
    {
        $a = 1;
        $b = 2;
        $expected = 3;
        $this->assertTrue(sum($a, $b) == $expected, "$a + $b != $expected");

        $a = 5;
        $b = 10;
        $expected = 22;
        $this->assertTrue(sum($a, $b) == $expected, "$a + $b != $expected");
    }

    public function testMath()
    {
        $this->assertNotEmpty(Math::getObj(), '_mathObj is empty');
    }
}