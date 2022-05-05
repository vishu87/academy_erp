<?php

namespace App\View\Components\web;

use Illuminate\View\Component;

class button extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $type;
    public $spin;

    public function __construct($type = "button", $spin = "")
    {
        $this->type = $type;
        $this->spin = $spin;
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
