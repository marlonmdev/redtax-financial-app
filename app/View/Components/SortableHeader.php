<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SortableHeader extends Component
{
    public $field;
    public $currentField;
    public $currentDirection;
    public $route;
    public $search;
    public $perPage;

    public function __construct($field, $currentField, $currentDirection, $route, $search, $perPage)
    {
        $this->field = $field;
        $this->currentField = $currentField;
        $this->currentDirection = $currentDirection;
        $this->route = $route;
        $this->search = $search;
        $this->perPage = $perPage;
    }

    public function render(): View|Closure|string
    {
        return view('components.sortable-header');
    }
}
