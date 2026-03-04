<?php

test('receipt requests enforce positive amount and debt checks', function () {
    $storeRequest = file_get_contents(__DIR__.'/../../app/Http/Requests/Student/StoreReceiptRequest.php');
    $updateRequest = file_get_contents(__DIR__.'/../../app/Http/Requests/Student/UpdateReceiptRequest.php');

    expect($storeRequest)->toContain("'debit' => 'required|numeric|gt:0'")
        ->and($storeRequest)->toContain('no_outstanding_balance')
        ->and($storeRequest)->toContain('amount_exceeds_balance')
        ->and($updateRequest)->toContain("'debit' => 'required|numeric|gt:0'")
        ->and($updateRequest)->toContain('availableBalanceForUpdate')
        ->and($updateRequest)->toContain('no_outstanding_balance');
});

test('financial migration relations keep accounting history for reporting', function () {
    $studentAccountsMigration = file_get_contents(__DIR__.'/../../database/migrations/2026_03_02_203190_create_student_accounts_table.php');
    $fundAccountsMigration = file_get_contents(__DIR__.'/../../database/migrations/2026_03_02_203200_create_fund_accounts_table.php');
    $feeInvoicesMigration = file_get_contents(__DIR__.'/../../database/migrations/2026_03_02_112946_create_fee_invoices_table.php');

    expect($studentAccountsMigration)->toContain("->constrained('students')->restrictOnDelete()")
        ->and($studentAccountsMigration)->toContain("->constrained('receipts')->nullOnDelete()")
        ->and($studentAccountsMigration)->toContain("->constrained('processing_fees')")
        ->and($studentAccountsMigration)->toContain('->nullOnDelete();')
        ->and($fundAccountsMigration)->toContain("->constrained('receipts')->nullOnDelete()")
        ->and($fundAccountsMigration)->toContain("->constrained('payments')->nullOnDelete()")
        ->and($feeInvoicesMigration)->toContain("->constrained('students')->restrictOnDelete()");
});
