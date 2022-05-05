<?php

namespace App\View\Components;

use Illuminate\View\Component;

class modals extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $id;
    public $title;
    public $size;

    public function __construct($title, $id, $size = "")
    {
        $this->title = $title;
        $this->id = $id;
        $this->size = $size;
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
