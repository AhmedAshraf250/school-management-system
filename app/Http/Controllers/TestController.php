<?php

namespace App\Http\Controllers;

use App\Models\Section;

class TestController extends Controller
{
    public function test()
    {
        $sections = Section::query()
            ->with('grade:id,Name')
            ->select(['id', 'grade_id', 'classroom_id'])
            ->get();

        // $sectionsByGradeGroup = $sections
        //     ->groupBy(function (Section $section) {
        //         return $this->gradeGroupFromGradeName((string) optional($section->grade)->getTranslation('Name', 'en'));
        //     });

        $sectionsByGradeGroup = $sections
            ->groupBy(function (Section $section) {
                $gradeName = (string) optional($section->grade)->getTranslation('Name', 'en');

                return match ($gradeName) {
                    'Middle School' => 'middle',
                    'High School' => 'high',
                    default => 'primary',
                };
            }, true)
            ->map(fn ($gradeGroupSections) => $gradeGroupSections->values());
        dd($sectionsByGradeGroup, $sections);
    }
}
