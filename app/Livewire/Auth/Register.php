<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

#[Layout('components.layouts.auth', [
    'title' => 'Create Your Account',
    'subtitle' => "Sign up to start your journey with us and manage your investments securely"
])]
class  Register extends Component {
    public $email = '';
    public $password = '';
    public $remember = false;
    public $fullname = '';
    public $password_confirmation = '';
    public bool $accept_terms = false;

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

    public function register() {
        // Validate input
        $credentials = $this->validate();

        $nameParts = explode(' ', $this->fullname, 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        $username = Str::slug($firstName . '-' . $lastName) . rand(100, 999);

        $user = User::create([
            'fullname' => $this->fullname,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);



        session()->regenerate();

        $this->reset('password', 'password_confirmation');

        return redirect()->intended(route('dashboard'));
    }


    /**
     * Mount the component.
     */
    public function render(): \Illuminate\View\View {
        return view('livewire.auth.register');
    }
}