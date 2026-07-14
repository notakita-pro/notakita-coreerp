<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DashboardSecurityService
{
    /**
     * Panjang Access Token
     */
    const TOKEN_LENGTH = 16;

    /**
     * Maksimal salah PIN
     */
    const MAX_ATTEMPTS = 5;

    /**
     * Lama blokir (menit)
     */
    const LOCK_MINUTES = 15;

    /**
 * Membuat Access Token baru (Base62)
 *
 * Contoh:
 * aK8mP2xQ7RbN4zTy
 */
public static function generateToken(): string
{
    return Str::random(self::TOKEN_LENGTH);
}
    /**
     * Simpan Access Token baru
     */
    public static function resetToken(
        Company $company
    ): string {

        do {

            $token = self::generateToken();

        } while (

            Company::where(
                'access_token',
                $token
            )->exists()

        );

        $company->update([
            'access_token' => $token,
        ]);

        return $token;
    }

    /**
     * Cari Company berdasarkan token
     */
    public static function findByToken(
        string $token
    ): ?Company {

        return Company::where(
            'access_token',
            $token
        )->first();
    }

    /**
     * Apakah perusahaan sudah memiliki PIN
     */
    public static function hasPin(
        Company $company
    ): bool {

        return !empty(
            $company->dashboard_pin
        );
    }

    /**
     * Membuat PIN pertama
     */
    public static function createPin(
        Company $company,
        string $pin
    ): void {

        $company->update([

            'dashboard_pin' => Hash::make($pin),

            'pin_created_at' => now(),

            'failed_attempts' => 0,

            'locked_until' => null,

        ]);
    }

    /**
     * Ganti PIN
     */
    public static function changePin(
        Company $company,
        string $pin
    ): void {

        self::createPin(
            $company,
            $pin
        );
    }

    /**
     * Verifikasi PIN
     */
    public static function verifyPin(
        Company $company,
        string $pin
    ): bool {

        if (
            !Hash::check(
                $pin,
                $company->dashboard_pin
            )
        ) {

            self::increaseFailedAttempts(
                $company
            );

            return false;
        }

        self::resetFailedAttempts(
            $company
        );

        return true;
    }

    /**
     * Tambah percobaan gagal
     */
    public static function increaseFailedAttempts(
        Company $company
    ): void {

        $attempts =
            $company->failed_attempts + 1;

        $company->failed_attempts = $attempts;

        if (
            $attempts >= self::MAX_ATTEMPTS
        ) {

            $company->locked_until =
                now()->addMinutes(
                    self::LOCK_MINUTES
                );

            $company->failed_attempts = 0;
        }

        $company->save();
    }

    /**
     * Reset jumlah percobaan
     */
    public static function resetFailedAttempts(
        Company $company
    ): void {

        $company->update([

            'failed_attempts' => 0,

            'locked_until' => null,

        ]);
    }

    /**
     * Apakah dashboard sedang dikunci
     */
    public static function isLocked(
        Company $company
    ): bool {

        if (
            empty($company->locked_until)
        ) {

            return false;
        }

        return now()->lt(
            $company->locked_until
        );
    }

    /**
     * Sisa menit blokir
     */
    public static function remainingLockMinutes(
        Company $company
    ): int {

        if (
            !self::isLocked($company)
        ) {

            return 0;
        }

        return now()->diffInMinutes(
            $company->locked_until
        ) + 1;
    }
}