<?php

namespace App\View\Components\web;

use Illuminate\View\Component;

class Button extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $type;
    public $ngClick;
    public $loading;

    public function __construct($type = "button", $loading = "", $ngClick = "")
    {
        $this->type = $type;
        $this->loading = $loading;
        $this->ngClick = $ngClick;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.web.button');
    }
}
