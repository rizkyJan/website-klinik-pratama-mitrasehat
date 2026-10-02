<?php

namespace Tests\Unit;

use App\Services\AI\ScopeClassifier;
use PHPUnit\Framework\TestCase;

class ScopeClassifierTest extends TestCase
{
    public function test_it_classifies_common_examples(): void
    {
        $classifier = new ScopeClassifier();

        $this->assertSame('greeting', $classifier->classify('Halo'));
        $this->assertSame('health', $classifier->classify('Perut saya sakit di bagian atas'));
        $this->assertSame('health', $classifier->classify('Gigi saya sakit sejak tadi malam'));
        $this->assertSame('clinic', $classifier->classify('Jadwal dokter gigi besok bagaimana?'));
        $this->assertSame('clinic', $classifier->classify('Ada nebulizer?'));
        $this->assertSame('out_of_scope', $classifier->classify('Buatkan saya kode Laravel untuk kasir'));
    }
}
