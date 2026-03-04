<?php

test('fee invoice controller requests fees filtered by student grade and classroom', function () {
    $controller = file_get_contents(__DIR__.'/../../app/Http/Controllers/Students/FeeInvoiceController.php');
    $contract = file_get_contents(__DIR__.'/../../app/Repositories/Contracts/FeeInvoicesRepositoryInterface.php');

    expect($controller)->toContain('availableFees((int) $student->grade_id, (int) $student->classroom_id)')
        ->and($controller)->toContain('availableFees((int) $feeInvoice->grade_id, (int) $feeInvoice->classroom_id)')
        ->and($contract)->toContain('public function availableFees(int $gradeId, int $classroomId): Collection;');
});
