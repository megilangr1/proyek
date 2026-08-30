<?php

namespace App\View\Components\Table;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Th extends Component
{
    public ?string $label;

    public ?string $field;

    public ?string $orderBy;

    public ?string $orderType;

    /**
     * Create a new component instance.
     */
    public function __construct(?string $label = null, ?string $field = null, ?string $orderBy = null, ?string $orderType = null)
    {
        $this->label = $label ?? null;
        $this->field = $field ?? null;
        $this->orderBy = $orderBy ?? null;
        $this->orderType = $orderType ?? null;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table.th');
    }
}
