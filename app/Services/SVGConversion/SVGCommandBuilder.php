<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 7/24/15
 * Time: 12:46 PM
 */
namespace App\Services\SVGConversion;
// SVG Command Builder contains three function
require_once 'ConvertCommand.php';
// One for each - EPSConverter, JPGConverter, PNGConverter

class SVGCommandBuilder{

    protected $source;
    protected $commandEPS;
    protected $commandPNG;
    protected $commandJPG;
    protected $options=array();


    public function __construct($source)
    {
        $this->source = $source;
    }

    public function addOption($option){
        $this->options[(string)$option]=$option;
        return $this;
    }


    private function __getSVGToFormatCommand($extension){
        $info = pathinfo($this->source);
        $name = $info['filename'];

        return (string)new ConvertCommand($this->source,$name.".".$extension,$this->options);
    }

    public function getSVGToEPSCommand()
    {
        return  $this->__getSVGToFormatCommand("eps");

    }
    public function getSVGToPNGCommand()
    {
        return  $this->__getSVGToFormatCommand("png");

    }

    public function getSVGToJPGCommand()
    {
        return  $this->__getSVGToFormatCommand("jpg");

    }


}