<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ActionMessage extends Component{
    public $showConfirm;
    public $type;
    public $title;
    public $message;
    public $warning;
    public $confirmText;

    public function __construct(
        $showConfirm = false,
        $type = 'warning',
        $title = '',
        $message = '',
        $warning = null,
        $confirmText = 'Confirm'
    ) {
        $this->showConfirm = $showConfirm;
        $this->type = $type;
        $this->title = $title;
        $this->message = $message;
        $this->warning = $warning;
        $this->confirmText = $confirmText;
    }

    public function render() {
        return view('components.action-message');
    }
}