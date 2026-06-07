<?php

namespace App\Livewire;

use Livewire\Component;

class LandingPage extends Component
{
    public function render()
    {
        return view('livewire.landing-page', [
            'packages' => \App\Models\Package::where('status', 'active')->get()
        ]);
    }
}
