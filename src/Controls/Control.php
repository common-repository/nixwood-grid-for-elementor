<?php
declare(strict_types = 1);
namespace Nixwoodgrid\Controls;

use Nixwoodgrid\Widgets\DataControl;

class Control
{
    private $element;
    private $tab;
    private $position;
    private $slug;
    private $data;
    private $type;
    private $injection;
    private $multiple;

    public function __construct()
    {
        $this->type = "usual";
    }

    public function element(string $element)
    {
        $this->element = $element;
    }

    public function tab(string $tab)
    {
        $this->tab = $tab;
    }

    public function injection(string $injection)
    {
        $this->injection = $injection;
    }

    public function position(string $position)
    {
        $this->position = $position;
    }

    public function slug(?string $slug = null): ?string
    {
        if ($slug !== null) {
            $this->slug = $slug;
            return null;
        }
        return $this->slug;
    }

    public function type(?string $type = null): ?string
    {
        if ($type !== null) {
            $this->type = $type;
            return null;
        }
        return $this->type;
    }

    public function action(): string
    {
        return 'elementor/element/'.$this->element.'/'.$this->tab.'/'.$this->position;
    }

    public function multiple(array $controls)
    {
        $this->multiple = $controls;   
    }

    public function data(dataControl $dataControl = null): array
    {
        if (isset($dataControl)) {
            $this->data = $dataControl->data();
            $this->slug = $dataControl->id();
        }
        return $this->data;
    }

    public static function activateControl(Control $control)
    {
        $priority = 10;
        $acceptedArgs = 2;

        add_action(
            $control->action(),
            function (object $element, array $args) use ($control) {

                if (isset($control->injection)) {
                    $element->start_injection([
                        'at' => 'after', 
                        'of' => $control->injection,
                    ]);    
                }

                $mctrl = (isset($control->multiple)) ? $control->multiple : [$control];
                
                foreach ($mctrl as $key => $ctrl) {

                    if (isset($control->multiple)) {
                        $slug = $ctrl->id();
                        $type = $ctrl->controlType();
                    }else{
                        $slug = $ctrl->slug();
                        $type = $ctrl->type();
                    }

                    switch ($type) {
                        case 'usual':
                            $element->add_control($slug, $ctrl->data());
                            break;
                        case 'group':
                            $element->add_group_control($slug, $ctrl->data());
                            break;
                        case 'responsive':
                            $element->add_responsive_control($slug, $ctrl->data());
                            break;
                    }
                }

                if (isset($control->injection)) {
                    $element->end_injection();
                }

            },
            $priority,
            $acceptedArgs
        );
    }
}
