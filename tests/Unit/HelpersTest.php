<?php

namespace Level7up\AIAssistant\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HelpersTest extends TestCase
{
    /** @test */
    public function it_returns_true_for_rtl_languages()
    {
        // Mock app()->getLocale() to return 'ar'
        app()->setLocale('ar');

        $this->assertTrue(is_rtl());
    }

    /** @test */
    public function it_returns_false_for_non_rtl_languages()
    {
        // Mock app()->getLocale() to return 'en'
        app()->setLocale('en');

        $this->assertFalse(is_rtl());
    }
}
