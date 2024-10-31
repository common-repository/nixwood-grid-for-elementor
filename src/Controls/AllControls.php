<?php
declare(strict_types = 1);
namespace Nixwoodgrid\Controls;

use Nixwoodgrid\Widgets\DataControl;

class AllControls
{
    public function __construct()
    {
        $grid = new Grid();
        $grid = $grid->controls();

        foreach ($grid as $key => $control) {
            Control::activateControl($control);
        }
    }
}
