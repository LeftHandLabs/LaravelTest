<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    public function test_health_check_returns_ok_status(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'timestamp',
            ]);
    }

    public function test_health_check_timestamp_is_utc(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200);

        $timestamp = $response->json('timestamp');

        $this->assertNotNull($timestamp);
        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\.\d+)?Z$/',
            $timestamp
        );
    }
}
