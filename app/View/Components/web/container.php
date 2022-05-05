<?php

namespace App\View\Components\web;

use Illuminate\View\Component;

class container extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $background;
    public $logo;
    public $controller;
    public $init;

    public function __construct($background, $logo, $controller = null, $init = null)
    {
        $this->background = $background;
        $this->logo = $logo;
        $this->controller = $controller;
        $this->init = $init;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.web.container');
    }
}
