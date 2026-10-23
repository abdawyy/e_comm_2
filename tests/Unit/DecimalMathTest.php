<?php

namespace Tests\Unit;

use App\Services\DecimalMath;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DecimalMathTest extends TestCase
{
    #[Test]
    public function it_converts_exact_tenths_of_a_pound_to_cents(): void
    {
        $math = new DecimalMath;

        $this->assertSame(1050, $math->toCents('10.5000'));
    }

    #[Test]
    public function it_rounds_half_away_from_zero_on_the_cents_place(): void
    {
        $math = new DecimalMath;

        $this->assertSame(1051, $math->toCents('10.5050'));
        $this->assertSame(1050, $math->toCents('10.5049'));
        $this->assertSame(-1051, $math->toCents('-10.5050'));
    }
}
