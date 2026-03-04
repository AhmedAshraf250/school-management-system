<?php

test('fee invoice add view locks amount input and binds amount to selected fee', function () {
    $viewContent = file_get_contents(__DIR__.'/../../resources/views/pages/fee-invoices/add.blade.php');

    expect($viewContent)->toContain('name="amount" step="0.01"')
        ->and($viewContent)->toContain('readonly required')
        ->and($viewContent)->toContain('data-amount="{{ $fee->amount }}"')
        ->and($viewContent)->toContain('select[name="fee_id"], select[name$="[fee_id]"]');
});

test('fee invoice repository always uses fee amount from database', function () {
    $repositoryContent = file_get_contents(__DIR__.'/../../app/Repositories/Eloquent/FeeInvoicesEloRepository.php');

    expect($repositoryContent)->toContain('$fee = $this->loadFee((int) $row[\'fee_id\']);')
        ->and($repositoryContent)->toContain('$amount = (float) $fee->amount;')
        ->and($repositoryContent)->toContain('$fee = $this->loadFee((int) $data[\'fee_id\']);')
        ->and($repositoryContent)->not->toContain('$amount = (float) $row[\'amount\'];')
        ->and($repositoryContent)->not->toContain('$amount = (float) $data[\'amount\'];');
});
