<?php
declare(strict_types = 1);
namespace Nixwoodgrid;

use Nixwoodgrid\Controls\AllControls;

class SetUp
{
	
	
    public function __construct()
    {
        try {
            new AllControls;
        } catch (\Throwable $error) {
           
        }
    }
	
	
}
