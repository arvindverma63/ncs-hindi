<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WebAppLayout extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View|Closure|string
    {
        // IMPORTANT: Ensure this matches exactly where your file is located
        // If the file is in resources/views/components/webapp-layout.blade.php, use:
        return view('components.webapp-layout');
    }
}
