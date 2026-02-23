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
