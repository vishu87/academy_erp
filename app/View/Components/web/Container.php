<?php

namespace App\View\Components\web;

use Illuminate\View\Component;

class Container extends Component
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
    public $footer;

    public function __construct($background, $logo, $controller = null, $init = null, $footer)
    {
        $this->background = $background;
        $this->logo = $logo;
        $this->controller = $controller;
        $this->init = $init;
        $this->footer = $footer;
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
