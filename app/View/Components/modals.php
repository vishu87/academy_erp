<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modals extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $id;
    public $title;
    public $modalSize;

    public function __construct($title, $id, $modalSize = "modal-lg")
    {
        $this->title = $title;
        $this->id = $id;
        $this->modalSize = $modalSize;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.modals');
    }
}
