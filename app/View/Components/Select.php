<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Select extends Component
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
    public $ngChange;

    public function __construct($label, $required = false, $name, $options = [], $ngChange = null)
    {
        $this->label = $label;
        $this->required = $required;
        $this->name = $name;
        $this->options = $options;
        $this->ngChange = $ngChange;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.select');
    }
}
