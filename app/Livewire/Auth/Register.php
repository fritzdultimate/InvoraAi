<?php

namespace App\Livewire\Auth;

use App\Mail\VerifyEmailMail;
use App\Models\User;
use App\Services\ReferralCreationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

#[Layout('components.layouts.auth')]
class  Register extends Component {
    public $email = '';
    public $password = '';
    public $remember = false;
    public $fullname = '';
    public $password_confirmation = '';
    public bool $accept_terms = false;

    public ?string $ref = null;

    protected function rules() {
        return [
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'accept_terms' => 'accepted',
        ];
    }

    protected $messages = [
        'fullname.required' => 'Please enter your full name.',
        'email.required' => 'Email is required.',
        'email.email' => 'Enter a valid email address.',
        'email.unique' => 'This email is already registered.',
        'password.required' => 'Password is required.',
        'password.confirmed' => 'Passwords do not match.',
        'password.min' => 'Password must be at least 8 characters.',
    ];

    public function mount() {
        $this->ref = request()->query('ref');
    }

    public function register() {
        // Validate input
        $credentials = $this->validate();

        $nameParts = explode(' ', $this->fullname, 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        DB::beginTransaction();

        try{
            $username = Str::slug($firstName . '-' . $lastName) . rand(100, 999);
            $referrer = User::where('affiliate_code', $this->ref)->first();
            $user = User::create([
                'firstname' => $firstName,
                'lastname' => $lastName,
                'name' => $username,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'affiliate_code' => $this->generateUniqueReferralCode(),
                'referrer_id' => $referrer?->id
            ]);

            if($referrer) {
                ReferralCreationService::createFor($user, $referrer);

                // Mail::to($referrer->email)->queue(
                //     new ReferredUserNotice(
                //         $referrer,
                //         referredUser: $user,
                //         level: 1
                //     )
                // );
                // send email
            }

            $user->assignRole('user');

            $verificationUrl = URL::temporarySignedRoute(
                'verification.link',
                now()->addMinutes(10),
                [
                    'user' => $user->id,
                    'hash' => sha1($user->email),
                ]
            );

            Mail::to($user->email)->send(new VerifyEmailMail(
                $verificationUrl,
            ));



            session()->regenerate();

            $this->reset('password', 'password_confirmation');

            DB::commit();
            return redirect()->intended(route('dashboard'));
        } catch (\Throwable $e) {
           DB::rollBack();
           
           \Log::error('User registration failed: '.$e->getMessage(), [
                'message' => $e->getMessage(),
                'email' => $this->email,
            ]);
            $this->addError('email', 'Registration failed. Please try again or contact support.');
            return;
        }

        
    }

    protected function generateUniqueReferralCode(int $length = 8): string {
        do {
            $code = strtoupper(Str::random($length));
        } while (User::where('affiliate_code', $code)->exists());

        return $code;
    }


    /**
     * Mount the component.
     */
    public function render(): \Illuminate\View\View {
        return view('livewire.auth.register');
    }
}