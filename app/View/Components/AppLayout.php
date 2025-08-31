<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $user = Auth::user();
        $layoutFile = 'layouts.app'; // Default layout

        if ($user) {
            if ($user->type === 'admin') {
                $layoutFile = 'layouts.layoutAdmin';
            } elseif ($user->type === 'service_ced') {
                $layoutFile = 'layouts.layout';
            } elseif ($user->type === 'directeur') {
                $layoutFile = 'layouts.layoutDirecteur';
            }
        }

        return view($layoutFile);
    
    }
}
