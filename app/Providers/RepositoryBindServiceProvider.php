<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryBindServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     */
    public function boot() {
        //
    }

    /**
     * Register any application services.
     */
    public function register() {
        $this->app->singleton(
            \App\Repositories\AdminUserRepositoryInterface::class,
            \App\Repositories\Eloquent\AdminUserRepository::class
        );
        $this->app->singleton(
            \App\Repositories\AdminUserRoleRepositoryInterface::class,
            \App\Repositories\Eloquent\AdminUserRoleRepository::class
        );
        $this->app->singleton(
            \App\Repositories\UserRepositoryInterface::class,
            \App\Repositories\Eloquent\UserRepository::class
        );
        $this->app->singleton(
            \App\Repositories\FileRepositoryInterface::class,
            \App\Repositories\Eloquent\FileRepository::class
        );
        $this->app->singleton(
            \App\Repositories\ImageRepositoryInterface::class,
            \App\Repositories\Eloquent\ImageRepository::class
        );
        $this->app->singleton(
            \App\Repositories\UserServiceAuthenticationRepositoryInterface::class,
            \App\Repositories\Eloquent\UserServiceAuthenticationRepository::class
        );
        $this->app->singleton(
            \App\Repositories\PasswordResettableRepositoryInterface::class,
            \App\Repositories\Eloquent\PasswordResettableRepository::class
        );
        $this->app->singleton(
            \App\Repositories\UserPasswordResetRepositoryInterface::class,
            \App\Repositories\Eloquent\UserPasswordResetRepository::class
        );
        $this->app->singleton(
            \App\Repositories\AdminPasswordResetRepositoryInterface::class,
            \App\Repositories\Eloquent\AdminPasswordResetRepository::class
        );
        $this->app->singleton(
            \App\Repositories\ArticleRepositoryInterface::class,
            \App\Repositories\Eloquent\ArticleRepository::class
        );
        $this->app->singleton(
            \App\Repositories\NotificationRepositoryInterface::class,
            \App\Repositories\Eloquent\NotificationRepository::class
        );
        $this->app->singleton(
            \App\Repositories\UserNotificationRepositoryInterface::class,
            \App\Repositories\Eloquent\UserNotificationRepository::class
        );
        $this->app->singleton(
            \App\Repositories\AdminUserNotificationRepositoryInterface::class,
            \App\Repositories\Eloquent\AdminUserNotificationRepository::class
        );

        $this->app->singleton(
            \App\Repositories\LogRepositoryInterface::class,
            \App\Repositories\Eloquent\LogRepository::class
        );

        $this->app->singleton(
            \App\Repositories\OauthClientRepositoryInterface::class,
            \App\Repositories\Eloquent\OauthClientRepository::class
        );

        $this->app->singleton(
            \App\Repositories\OauthAccessTokenRepositoryInterface::class,
            \App\Repositories\Eloquent\OauthAccessTokenRepository::class
        );

        $this->app->singleton(
            \App\Repositories\OauthRefreshTokenRepositoryInterface::class,
            \App\Repositories\Eloquent\OauthRefreshTokenRepository::class
        );
        $this->app->singleton(
            \App\Repositories\HospitalRepositoryInterface::class,
            \App\Repositories\Eloquent\HospitalRepository::class
        );

        $this->app->singleton(
            \App\Repositories\SpecialtyRepositoryInterface::class,
            \App\Repositories\Eloquent\SpecialtyRepository::class
        );

        $this->app->singleton(
            \App\Repositories\DoctorRepositoryInterface::class,
            \App\Repositories\Eloquent\DoctorRepository::class
        );

        $this->app->singleton(
            \App\Repositories\DoctorSpecialtyRepositoryInterface::class,
            \App\Repositories\Eloquent\DoctorSpecialtyRepository::class
        );

        $this->app->singleton(
            \App\Repositories\ClinicRepositoryInterface::class,
            \App\Repositories\Eloquent\ClinicRepository::class
        );

        $this->app->singleton(
            \App\Repositories\PlanRepositoryInterface::class,
            \App\Repositories\Eloquent\PlanRepository::class
        );

        $this->app->singleton(
            \App\Repositories\PointDoctorRepositoryInterface::class,
            \App\Repositories\Eloquent\PointDoctorRepository::class
        );

        $this->app->singleton(
            \App\Repositories\PointPatientRepositoryInterface::class,
            \App\Repositories\Eloquent\PointPatientRepository::class
        );

        $this->app->singleton(
            \App\Repositories\ChatHistoryRepositoryInterface::class,
            \App\Repositories\Eloquent\ChatHistoryRepository::class
        );

        $this->app->singleton(
            \App\Repositories\CallHistoryRepositoryInterface::class,
            \App\Repositories\Eloquent\CallHistoryRepository::class
        );

        $this->app->singleton(
            \App\Repositories\FilePatientRepositoryInterface::class,
            \App\Repositories\Eloquent\FilePatientRepository::class
        );

        $this->app->singleton(
            \App\Repositories\FilePatientImageRepositoryInterface::class,
            \App\Repositories\Eloquent\FilePatientImageRepository::class
        );

        $this->app->singleton(
            \App\Repositories\PatientRepositoryInterface::class,
            \App\Repositories\Eloquent\PatientRepository::class
        );

        /* NEW BINDING */
    }
}
