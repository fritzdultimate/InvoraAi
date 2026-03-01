<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class Settings extends Component {

    public $id_front;
    public $id_back;
    public $selfie;
    public $address_proof;
    public $tab = 'profile';
    public function render()
    {
        return view('livewire.dashboard.settings');
    }
}
