<?php

test('guest is redirected when opening student calendar page', function () {
    $this->get(route('student.calendar'))
        ->assertRedirect(route('auth.selection'));
});

test('guest is redirected when opening guardian calendar page', function () {
    $this->get(route('guardian.calendar'))
        ->assertRedirect(route('auth.selection'));
});
