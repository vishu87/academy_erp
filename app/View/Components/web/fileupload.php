<?php

namespace App\View\Components\web;

use Illuminate\View\Component;

class fileupload extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $label;
    public $name;
    public $required;

    public function __construct($label, $name, $required = false)
    {
        $this->label = $label;
        $this->name = $name;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.web.fileupload');
    }
}
