<?php

declare(strict_types=1);

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RecursiveTable extends Component
{
    public mixed $data;
    public int $level;

    public function __construct(mixed $data, int $level = 0)
    {
        $this->data = $data;
        $this->level = $level;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.recursive-table', [
            'data' => $this->data,
            'level' => $this->level,
        ]);
    }
}
