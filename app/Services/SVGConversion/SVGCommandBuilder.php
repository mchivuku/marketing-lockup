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

    public function __construct($source)
    {
        $this->source = $source;

    }


    private function __getSVGToFormatCommand($extension,$options,$filename=""){
        $info = pathinfo($this->source);

        if($filename!=""){
            $dest = $filename;
        }else{
            $dest = $info['dirname']."/".$info['filename'].".".$extension;

        }

        return (string)new ConvertCommand($this->source,$dest,$options);
    }


    /** PNG Command to have transparent background */
    public function getSVGToPNGCommand()
    {

        // 1. PRINT WEB - with transparent background
        // 2. convert -background none svg.svg svg_version2.png,convert -identify -resize 200%
        return  $this->__getSVGToFormatCommand("png",array('-background none','-quality 05',
            'png:compression-level=9',
           '-density 300'));

    }

    public function getSVGToJPGHighResolutionCommand()
    {
        $options = array();
        $options[] = "-density 300";
       // $options[] = "-resize 200%";  //size 200% / Brian said remove resize option;
        $options[] = "-quality 12";   // quality 12

        $info = pathinfo($this->source);
        $dest = $info['dirname']."/".$info['filename']."_300dpi.jpg";
        return  $this->__getSVGToFormatCommand("jpg",$options,$dest);

    }


}