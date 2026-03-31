<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

#[Layout('components.layouts.auth')]
class  RegistrationSuccess extends Component {


    /**
     * Mount the component.
     */
    public function render(): \Illuminate\View\View {
        return view('livewire.auth.registration-success');
    }
}