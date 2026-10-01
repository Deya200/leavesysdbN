<?php

namespace Tests\Feature;

use Tests\TestCase;

class AppUrlConfigTest extends TestCase
{
    public function test_app_url_points_to_the_live_site_for_invitation_links(): void
    {
        $env = file_get_contents(base_path('.env'));

        $this->assertStringContainsString('APP_URL=https://abc.cham.org.mw', $env);
    }
}
