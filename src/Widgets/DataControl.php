<?php
declare(strict_types = 1);
namespace Nixwoodgrid\Widgets;

use \Elementor\Controls_Manager as Type;
use \Elementor\Group_Control_Typography as Typography;
use \Elementor\Group_Control_Box_Shadow as Box_Shadow;
use \ElementorPro\Modules\QueryControl\Module;

class DataControl
{ 
    private $id;
    private $data;
    private $controlType;

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        unset($data["id"]);
        $this->data = $data;
        $this->type();
    }

    private function type()
    {
        $type = "";
        switch ($this->data["type"]) {
            case 'text':
                $type = Type::TEXT;
                break;
            case 'number':
                $type = Type::NUMBER;
                break;
            case 'select':
                $type = Type::SELECT;
                break;
            case 'select2':
                $type = Type::SELECT2;
                break;
            case 'choose':
                $type = Type::CHOOSE;
                break;
            case 'slider':
                $type = Type::SLIDER;
                break;
            case 'color':
                $type = Type::COLOR;
                break;
            case 'media':
                $type = Type::MEDIA;
                break;
            case 'hidden':
                $type = Type::HIDDEN;
                break;
            case 'switcher':
                $this->setSwitcherDefault();
                $type = Type::SWITCHER;
                break;
            case 'heading':
                $type = Type::HEADING;
                break;
            case 'query':
                $type = Module::QUERY_CONTROL_ID;
                break;
            case 'url':
                $type = Type::URL;
                break;
            case 'typography':
                $this->controlType = "group";
                $this->typographySet();
                break;
            case 'box_shadow':
                $this->controlType = "group";
                $this->boxShadowSet();
                break;
            case 'range': 
                $this->controlType = "responsive";
                $type = Type::SLIDER;
                break;
            case 'dimensions':
                $this->controlType = "responsive";
                $type = Type::DIMENSIONS;
                break;
        }
        if (!empty($type)) {
            if (!isset($this->controlType)) {
                $this->controlType = "usual";
            }
            $this->data["type"] = $type;
        }
    }

    private function typographySet()
    {
        $this->data["name"] = $this->id;
        $this->id = Typography::get_type();
    }

    private function boxShadowSet()
    {
        $this->data["name"] = $this->id;
        $this->id = Box_Shadow::get_type();
    }

    private function setSwitcherDefault()
    {
        $this->data['label_off'] = esc_attr__('Off', 'nixwoodgrid');
        $this->data['label_on'] = esc_attr__('On', 'nixwoodgrid');
        $this->data['render_type'] = 'ui';
        $this->data['frontend_available'] = true;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function data(): array
    {
        return $this->data;
    }

    public function controlType(): string
    {
        return $this->controlType;
    }
}
