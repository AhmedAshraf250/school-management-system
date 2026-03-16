<?php

test('main sidebar partial dispatches to guard-specific sidebars', function () {
    $mainSidebar = file_get_contents(__DIR__.'/../../resources/views/layouts/partials/main-sidebar.blade.php');

    expect($mainSidebar)->toContain('GuardResolver::currentGuard()');
    expect($mainSidebar)->toContain('layouts.main-sidebar.student-main-sidebar');
    expect($mainSidebar)->toContain('layouts.main-sidebar.teacher-main-sidebar');
    expect($mainSidebar)->toContain('layouts.main-sidebar.guardian-main-sidebar');
    expect($mainSidebar)->toContain('layouts.main-sidebar.admin-main-sidebar');
});

test('guard specific sidebar files exist', function () {
    expect(file_exists(__DIR__.'/../../resources/views/layouts/main-sidebar/admin-main-sidebar.blade.php'))->toBeTrue();
    expect(file_exists(__DIR__.'/../../resources/views/layouts/main-sidebar/student-main-sidebar.blade.php'))->toBeTrue();
    expect(file_exists(__DIR__.'/../../resources/views/layouts/main-sidebar/teacher-main-sidebar.blade.php'))->toBeTrue();
    expect(file_exists(__DIR__.'/../../resources/views/layouts/main-sidebar/guardian-main-sidebar.blade.php'))->toBeTrue();
});
