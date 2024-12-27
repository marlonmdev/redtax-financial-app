<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PerPageSelector extends Component
{
    public $route;
    public $sortField;
    public $sortDirection;
    public $search;
    public $perPage;

    public function __construct($route, $sortField = 'id', $sortDirection = 'desc', $search = '', $perPage = 10)
    {
        $this->route = $route;
        $this->sortField = $sortField;
        $this->sortDirection = $sortDirection;
        $this->search = $search;
        $this->perPage = $perPage;
    }


    public function render(): View|Closure|string
    {
        return view('components.per-page-selector');
    }
}
