<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\LedgerAsset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'firstname',
        'lastname',
        'affiliate_code',
        'referrer_id',
        'main_balance',
        'deposit_balance',
        'referral_balance',
        'profit_balance',
        'locked_balance',
        'suspended_at',
        'main_balance',
        'deposit_balance',
        'locked_balance',
        'referral_balance',
        'profit_balance',
        'deposit_bonus_balance',
        'country',
        'phone_number',
        'dob',
        'kyc_status',
        'kyc_submitted_at',
        'two_factor_enable',
        'notify_login_attempts',
        'notify_email_notifications',
        'notify_deposit_alerts',
        'notify_withdrawal_alerts',
        'notify_security_alerts',
        'last_verification_sent_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'suspended_at' => 'datetime',
            'dob' => 'date',
            'two_factor_enable' => 'boolean',
            'notify_login_attempts' => 'boolean',
            'notify_email_notifications' => 'boolean',
            'notify_deposit_alerts' => 'boolean',
            'notify_withdrawal_alerts' => 'boolean',
            'notify_security_alerts' => 'boolean'

        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    // public function syncBalances() {
    //     $this->main_balance = 
    //         $this->deposit_balance +
    //         $this->referral_balance +
    //         $this->profit_balance;

    //     $this->save();
    // }

    public function getTotalBalanceAttribute(): string {

        $sum = bcadd((string) $this->main_balance, (string) $this->profit_balance, 8);

        $sum = bcadd($sum, (string) $this->referral_balance, 8);
        $sum = bcadd($sum, (string) $this->deposit_balance, 8);
        $sum = bcadd($sum, (string) $this->deposit_bonus_balance, 8);

        return $sum;
    }

    public function botLicenses() {
        return $this->hasMany(BotLicense::class);
    }

    public function hasActiveLicense() {
        return $this->botLicenses()->whereFuture('expires_at')->exists();
    }

    public function ledgers() {
        return $this->hasMany(WalletLedger::class);
    }

    public function getBalance(LedgerAsset $asset): float {
        return $this->ledgers()
            ->where('asset', $asset)
            ->latest('id')
            ->value('balance_after') ?? 0;
    }

    public function deposits() {
        return $this->hasMany(Deposit::class);
    }

    public function isAdmin() {
        return $this->hasRole(['admin']) || $this->email === 'fritzdultimate7@gmail.com';
    }

    public function kyc() {
        return $this->hasOne(KycVerification::class);
    }

    public function notifications() {
        return $this->belongsToMany(Notification::class)
            ->withPivot('read_at', 'dismissed_at')
            ->withTimestamps();
    }

    public function rank() {
        return $this->hasOne(UserRank::class);
    }

    public function referredBy() {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referrals() {
        return $this->hasMany(User::class, 'referrer_id');
    }

    public function botInvestments() {
        return $this->hasMany(BotInvestment::class);
    }

    public function isActive(): bool {
        $monthlyInvestment = BotInvestment::where('user_id', $this->id)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $hasActiveReferral = $this->referrals()
            ->whereHas('botInvestments')
            ->exists();

        return $monthlyInvestment >= 500 && $hasActiveReferral;
    }
}
