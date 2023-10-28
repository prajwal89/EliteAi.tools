<?php

namespace App\View\Components\ToolCards;

use App\Models\Tool;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HorizontalCard extends Component
{
    public function __construct(public Tool $tool)
    {
    }

    public function render(): View|Closure|string
    {
        return view('components.tool-cards.horizontal-card');
    }
}
