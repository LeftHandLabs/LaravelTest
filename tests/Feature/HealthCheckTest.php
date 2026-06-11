<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    /**
     * Test the health check endpoint returns 200 with expected JSON structure.
     */
    public function test_health_check_returns_ok_status(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'timestamp',
            ])
            ->assertJsonFragment([
                'status' => 'ok',
            ]);
    }

    /**
     * Test the health check timestamp is a valid UTC ISO 8601 string.
     */
    public function test_health_check_timestamp_is_utc(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200);

        $timestamp = $response->json('timestamp');

        $this->assertNotNull($timestamp);
        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\.\d+)?Z$/',
            $timestamp,
            'Timestamp should be a UTC ISO 8601 string ending in Z'
        );
    }
}
