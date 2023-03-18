<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\CalculadoraController;

class calculadoraTest extends TestCase
{
    /** @test */
    public function sum(){
        $sum = new CalculadoraController();

        $response = $sum->sum(1, 3);

        $this->assertEquals(4, $response);
    }
}
