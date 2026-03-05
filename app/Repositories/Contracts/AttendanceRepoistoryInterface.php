<?php

namespace App\Repositories\Contracts;

use App\Models\Section;
use Illuminate\Database\Eloquent\Collection;

interface AttendanceRepoistoryInterface
{
    public function allSections(): Collection;

    public function sectionWithStudents(int $sectionId): Section;

    public function storeAttendance(array $data): void;
}
