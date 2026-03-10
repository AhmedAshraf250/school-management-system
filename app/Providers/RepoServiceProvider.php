<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\TeacherRepositoryInterface::class,
            \App\Repositories\Eloquent\TeacherEloRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\StudentRepositoryInterface::class,
            \App\Repositories\Eloquent\StudentEloRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\StaticDataRepositoryInterface::class,
            \App\Repositories\Eloquent\StaticDataEloRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\PromotionRepositoryInterface::class,
            \App\Repositories\Eloquent\PromotionEloRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\GraduationRepositoryInterface::class,
            \App\Repositories\Eloquent\GraduationEloRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\FeeRepositoryInterface::class,
            \App\Repositories\Eloquent\FeeEloRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\FeeInvoicesRepositoryInterface::class,
            \App\Repositories\Eloquent\FeeInvoicesEloRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\ReceiptsRepositoryInterface::class,
            \App\Repositories\Eloquent\ReceiptsEloRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\ProcessingFeeRepositoryInterface::class,
            \App\Repositories\Eloquent\ProcessingFeeEloRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\PaymentRepositoryInterface::class,
            \App\Repositories\Eloquent\PaymentEloRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\AttendanceRepoistoryInterface::class,
            \App\Repositories\Eloquent\AttendanceEloRepoistory::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\LibraryRepositoryInterface::class,
            \App\Repositories\Eloquent\LibraryEloRepository::class
        );
        // $this->app->bind(
        //     \App\Repositories\Contracts\SubjectRepoistoryInterface::class,
        //     \App\Repositories\Eloquent\SubjectEloRepoistory::class
        // );
        // $this->app->bind(
        //     \App\Repositories\Contracts\QuizRepoistoryInterface::class,
        //     \App\Repositories\Eloquent\QuizEloRepoistory::class
        // );
        // $this->app->bind(
        //     \App\Repositories\Contracts\QuestionRepoistoryInterface::class,
        //     \App\Repositories\Eloquent\QuestionEloRepoistory::class
        // );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
