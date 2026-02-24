<?php

use App\Models\User;

test('sidebar keeps sections menu active while browsing sections pages', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('sections.index'));

    $response->assertOk();
    $response->assertSee('id="sections-menu" class="collapse show"', false);

    $sectionsRoute = preg_quote(route('sections.index'), '/');
    $this->assertMatchesRegularExpression(
        '/<ul id="sections-menu" class="collapse show"[^>]*>[\s\S]*?<li class="active">\s*<a href="'.$sectionsRoute.'">/',
        $response->getContent(),
    );
});

test('sidebar keeps teachers menu active while browsing teachers pages', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('teachers.index'));

    $response->assertOk();
    $response->assertSee('id="Teachers-menu" class="collapse show"', false);

    $teachersRoute = preg_quote(route('teachers.index'), '/');
    $this->assertMatchesRegularExpression(
        '/<ul id="Teachers-menu" class="collapse show"[^>]*>[\s\S]*?<li class="active">\s*<a\s+href="'.$teachersRoute.'">/',
        $response->getContent(),
    );
});
