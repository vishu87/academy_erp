<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NgForm extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $name;
    public $ngSubmit;

    public function __construct($name, $ngSubmit)
    {
        $this->name = $name;
        $this->ngSubmit = $ngSubmit;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.ngform');
    }
}
