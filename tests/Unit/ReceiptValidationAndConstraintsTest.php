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

test('financial operations use soft deletes to preserve accounting links', function () {
    $softDeletesMigration = file_get_contents(__DIR__.'/../../database/migrations/2026_03_31_151102_add_soft_deletes_to_financial_operation_tables.php');
    $receiptModel = file_get_contents(__DIR__.'/../../app/Models/Receipt.php');
    $paymentModel = file_get_contents(__DIR__.'/../../app/Models/Payment.php');
    $processingFeeModel = file_get_contents(__DIR__.'/../../app/Models/ProcessingFee.php');

    expect($softDeletesMigration)->toContain("Schema::table('receipts'")
        ->and($softDeletesMigration)->toContain("Schema::table('payments'")
        ->and($softDeletesMigration)->toContain("Schema::table('processing_fees'")
        ->and($softDeletesMigration)->toContain('->softDeletes();')
        ->and($receiptModel)->toContain('use SoftDeletes;')
        ->and($paymentModel)->toContain('use SoftDeletes;')
        ->and($processingFeeModel)->toContain('use SoftDeletes;');
});

test('financial repositories expose trash restore workflow and hard delete guard', function () {
    $receiptsContract = file_get_contents(__DIR__.'/../../app/Repositories/Contracts/ReceiptsRepositoryInterface.php');
    $paymentsContract = file_get_contents(__DIR__.'/../../app/Repositories/Contracts/PaymentRepositoryInterface.php');
    $processingContract = file_get_contents(__DIR__.'/../../app/Repositories/Contracts/ProcessingFeeRepositoryInterface.php');
    $forceDeleteGuardTrait = file_get_contents(__DIR__.'/../../app/Models/Concerns/PreventsForceDelete.php');
    $feesTranslations = file_get_contents(__DIR__.'/../../lang/en/fees_trans.php');

    expect($receiptsContract)->toContain('restoreReceipt')
        ->and($paymentsContract)->toContain('restorePayment')
        ->and($processingContract)->toContain('restoreProcessingFee')
        ->and($forceDeleteGuardTrait)->toContain('force_delete_not_allowed')
        ->and($feesTranslations)->toContain('force_delete_not_allowed');
});
