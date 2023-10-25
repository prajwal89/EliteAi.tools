<?php

namespace App\View\Components\ToolCard;

use App\Models\Tool;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Horizontal extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Tool $tool)
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tool-card.horizontal');
    }
}
