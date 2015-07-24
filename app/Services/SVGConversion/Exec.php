<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 7/24/15
 * Time: 1:21 PM
 */
namespace App\Services\SVGConversion;

class Exec
{
    protected $output;
    protected $returnValue;

    public function run($command)
    {
        return exec($command, $this->output, $this->returnValue);
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function getReturnValue()
    {
        return $this->returnValue;
    }

}