<?php
declare(strict_types = 1);
namespace Nixwoodgrid\Controls;

use Nixwoodgrid\Widgets\DataControl;

class Grid
{
    private $controls;
    private $grid;
    private $global;

    public function __construct()
    {
        $this->global = 3.2;

        $this->controls = [
            $this->spaceBetweenColumnsCheck(),
            $this->spaceBetweenColumns(),
            $this->bootstrap(),
        ];
        $this->createNxwGrid();
    }

    public function controls(): array
    {
        return $this->controls;
    }

    private function createNxwGrid()
    {
        $gridStructure = [
            "2" => [
                "1/3 + 2/3", 
                "2/3 + 1/3", 
                "1/4 + 3/4", 
                "3/4 + 1/4",
            ],
            "3" => [
                "1/4 + 2/4 + 1/4",
                "1/4 + 1/4 + 2/4",
                "2/4 + 1/4 + 1/4",
                "1/6 + 2/6 + 3/6",
                "1/5 + 2/5 + 2/5",
            ],
        ];
        $this->constructColumns($gridStructure);

       // $this->display($this->controls);

        // $twoColumns = $this->calculateWidth($gridStructure[3]);
    }

    private function constructColumns(array $gridStructure)
    {
        foreach ($gridStructure as $key => $grid) {
            $control = new Control;
            $control->element("section");
            $control->tab("section_layout");
            $control->position("before_section_end");
            $control->type("responsive");

            $dataControl = new DataControl([
                "id" => "nxw".$key."Columns",
                "label" => esc_attr__('Bootstrap structure', 'nixwood'),
                "type" => "select",
                'condition' => [
                    'space_between_columns_check' => 'yes',
                    'nxw_bootstrap' => 'yes',
                    'number_of_columns' => "$key",
                ],
                "options" => $grid,
                "default" => "0",
            ]);
            $control->data($dataControl);
            $this->controls[] = $control;
            $this->constructSubColumns($key, $grid);
        }
    }

    private function constructSubColumns(int $columns, array $gridStructure)
    {
        foreach ($gridStructure as $key => $value) {
            
            $control = new Control;
            $control->element("section");
            $control->tab("section_layout");
            $control->position("before_section_end");
            $control->type("responsive");

            $dataControl = new DataControl([
                "id" => "nxwSub".$columns."_".$key."Columns",
                "label" => esc_attr__('Bootstrap structure', 'nixwood'),
                "type" => "switcher",
                'condition' => [
                    "space_between_columns_check" => "yes",
                    "nxw_bootstrap" => "yes",
                    "number_of_columns" => "$columns",
                    "nxw".$columns."Columns" => "$key",
                ],
                'default' => 'yes',
                'selectors' => $this->calcSelectors($columns, $value),
            ]);
            $control->data($dataControl);
            // $this->display($control);
            $this->controls[] = $control;
        }
    }

    private function calcSelectors(int $columns, string $grid): array
    {
        $selectors = [];
    
        $colInGrid = $this->colInGrid($grid);
        $positions = $this->positions($grid);
        $width = $this->gridFormula($colInGrid, $positions);
        
        for ($i=1; $i < $columns + 1; $i++) { 
            $key = '{{WRAPPER}} > div > div:nth-child('.$i.')';
            $selectors[$key] = "width: ".$width[$i]."%;";
        }

        return $selectors;
    }

    private function calculateWidth(array $grid): array
    {
        $structuredWidth = [];
        foreach ($grid as $value) {
            $colInGrid = $this->colInGrid($value);
            $positions = $this->positions($value);
            $structuredWidth[] = $this->gridFormula($colInGrid, $positions);
        }
        return $structuredWidth;
    }

    private function gridFormula(int $colInGrid, array $positions): array
    {
        $columnWidths = [];
        $global = $this->global;
        $realWidth = 100 - $global * ($colInGrid - 1);
        $oneColumnWidth = $realWidth / $colInGrid;
        $i = 0;
        foreach ($positions as $position) { $i++;
            $posColWidth = $oneColumnWidth * $position;
            $result = $position * $global - $global + $posColWidth;
            $columnWidths[$i] = $result;
        }

        return $columnWidths;
    }

    private function calculateStandartWidth(): array
    {
        $global = $this->global;
        $widths = [];
        for ($i=1; $i < 7; $i++) { 
            $realWidth = 100 - $global * ($i - 1);
            $widths[$i] = $realWidth / $i;
        }
        return $widths;
    }

    private function colInGrid(string $value): int
    {
        $columns = explode("/", $value);
        return intval(end($columns));
    }

    private function positions(string $value): array
    {
        $positions = [];
        $columns = explode("+", $value);
        foreach ($columns as $column) {
            $positions[] = intval(explode("/", $column)[0]);
        }
        return $positions;
    }

    private function display($variable)
    {
        file_put_contents(nxwError."all.log", print_r($variable, true), FILE_APPEND);
    }

    private function spaceBetweenColumnsCheck(): Control
    {
        $control = new Control;
        $control->element("section");
        $control->tab("section_layout");
        $control->position("before_section_end");
        $dataControl = new DataControl([
            "id" => "space_between_columns_check",
            "label" => esc_attr__('Space between columns', 'nixwood'),
            "type" => "switcher",
            'selectors_dictionary' => [
                'no' => '',
                'yes' => 'justify-content: space-between;',
            ],
            'default' => '0',
            'selectors' => [
                '{{WRAPPER}} > div' => '{{VALUE}}',
            ],
        ]);
        $control->data($dataControl);
        return $control;
    }

    private function spaceBetweenColumns(): Control
    {
        $width = $this->calculateStandartWidth();
        $control = new Control;
        $control->element("section");
        $control->tab("section_layout");
        $control->position("before_section_end");
        $control->type("responsive");
        $dataControl = new DataControl([
            "id" => "number_of_columns",
            "label" => esc_attr__('Number of columns', 'nixwood'),
            "type" => "select",
            'condition' => [
                'space_between_columns_check' => 'yes',
            ],
            "options" => [
                "0" => esc_attr__('None', 'nixwood'),
                "1" => esc_attr__('1 column', 'nixwood'),
                "2" => esc_attr__('2 columns', 'nixwood'),
                "3" => esc_attr__('3 columns', 'nixwood'),
                "4" => esc_attr__('4 columns', 'nixwood'),
                "5" => esc_attr__('5 columns', 'nixwood'),
                "6" => esc_attr__('6 columns', 'nixwood'),
            ],
            'selectors_dictionary' => [
                '0' => "",
                '1' => 'width: '.$width[1].'%',
                '2' => 'width: '.$width[2].'%',
                '3' => 'width: '.$width[3].'%',
                '4' => 'width: '.$width[4].'%',
                '5' => 'width: '.$width[5].'%',
                '6' => 'width: '.$width[6].'%',
            ],
            'default' => '0',
            'selectors' => [
                '(desktop) {{WRAPPER}} > div > div' => '{{VALUE}}',
                '(mobile) {{WRAPPER}} > div > div' => '{{VALUE}}  !important;',
            ],
        ]);
        $control->data($dataControl);
        return $control;
    }

    private function bootstrap(): Control
    {
        $control = new Control;
        $control->element("section");
        $control->tab("section_layout");
        $control->position("before_section_end");
        $control->type("responsive");
        $dataControl = new DataControl([
            "id" => "nxw_bootstrap",
            "label" => esc_attr__('Bootstrap', 'nixwood'),
            "type" => "switcher",
            'condition' => [
                'space_between_columns_check' => 'yes',
            ],
        ]);
        $control->data($dataControl);
        return $control;
    }

    private function bootstrap2columns_1323(): Control
    {
        $control = new Control;
        $control->element("section");
        $control->tab("section_layout");
        $control->position("before_section_end");
        $control->type("responsive");
        $red = "blue";
        $hide = "";
        $dataControl = new DataControl([
            "id" => "bootstrap2columns_1323",
            "label" => esc_attr__('1323', 'nixwood'),
            "type" => "switcher",
            'condition' => [ 
                'space_between_columns_check' => 'yes',
                'nxw_bootstrap' => 'yes',
                'number_of_columns' => '2',
                'bootstrap2columns' => '1323'
            ],
            'default' => 'yes',
            'selectors' => [
                '{{WRAPPER}} > div > div' => "background: $red;",
            ],
        ]);

        $control->data($dataControl);
        return $control;
    }

}