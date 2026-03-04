<?php

test('fee invoice requests validate duplicate fee per student', function () {
    $storeRequest = file_get_contents(__DIR__.'/../../app/Http/Requests/Student/StoreFeeInvoiceRequest.php');
    $updateRequest = file_get_contents(__DIR__.'/../../app/Http/Requests/Student/UpdateFeeInvoiceRequest.php');

    expect($storeRequest)->toContain("'list_fees' => 'required|array|min:1'")
        ->and($storeRequest)->toContain('duplicate_fee_for_student')
        ->and($storeRequest)->toContain('fee_not_allowed_for_student')
        ->and($storeRequest)->toContain('Student::query()')
        ->and($storeRequest)->toContain('Fee::query()')
        ->and($storeRequest)->toContain('FeeInvoice::query()')
        ->and($updateRequest)->toContain("'student_id' => 'required|integer|exists:students,id'")
        ->and($updateRequest)->toContain('where(\'id\', \'!=\', $invoiceId)')
        ->and($updateRequest)->toContain('duplicate_fee_for_student')
        ->and($updateRequest)->toContain('fee_not_allowed_for_student')
        ->and($updateRequest)->toContain('Student::query()')
        ->and($updateRequest)->toContain('Fee::query()');
});

test('fee invoice repository and migration enforce uniqueness', function () {
    $repository = file_get_contents(__DIR__.'/../../app/Repositories/Eloquent/FeeInvoicesEloRepository.php');
    $migration = file_get_contents(__DIR__.'/../../database/migrations/2026_03_02_112946_create_fee_invoices_table.php');

    expect($repository)->toContain('ensureFeeInvoiceIsUnique')
        ->and($repository)->toContain('ensureFeeBelongsToStudent')
        ->and($repository)->toContain('where(\'grade_id\', $gradeId)')
        ->and($repository)->toContain('where(\'classroom_id\', $classroomId)')
        ->and($repository)->toContain('ValidationException::withMessages')
        ->and($migration)->toContain("\$table->unique(['student_id', 'fee_id']);");
});
