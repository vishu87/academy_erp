<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Radio extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $label;
    public $required;
    public $name;
    public $options;
    public $ng;

    public function __construct($label, $required = false, $name, $options, $ng = true)
    {
        $this->label = $label;
        $this->required = $required;
        $this->name = $name;
        $this->options = $options;
        $this->ng = $ng;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.radio');
    }
}
