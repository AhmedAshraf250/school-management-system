<?php

namespace App\Repositories\Contracts;

interface FeeRepositoryInterface
{
    public function getAllFees();

    public function getFeeById($id);

    public function createFee($data);

    public function updateFee($id, $data);

    public function deleteFee($id);
}
