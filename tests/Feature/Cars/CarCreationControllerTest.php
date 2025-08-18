<?php

namespace Tests\Feature\Cars;

use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CarCreationControllerTest extends TestCase
{
    public function test_it_renders_the_create_release_page()
    {
        $this->signIn();

        $response = $this->get('/cars/create');

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) =>
            $page->component('Cars/Create/Index')
        );
    }
}
