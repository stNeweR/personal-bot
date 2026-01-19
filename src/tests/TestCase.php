<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected string $telegramWebhookUrl;

    protected function setUp(): void
    {
        parent::setUp();

        $this->telegramWebhookUrl = config('telegram.application_webhook_endpoint');
    }
}
