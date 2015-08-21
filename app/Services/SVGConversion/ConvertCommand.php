<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 7/23/15
 * Time: 2:28 PM
 */
namespace App\Services\SVGConversion;


/*** Class to build convert command */
class ConvertCommand{


    protected $command = "convert";


    protected $options = array();
    const OPTION_PREFIX =  '-';

    protected $src;
    protected $dest;

    public function __construct($src,$dest,$options=array())
    {
        $this->src = $src;
        $this->dest = $dest;
        $this->options = $options;
    }

    protected function pipeerror()
    {
        $name = pathinfo($this->src);

        return trim("2>".$name['dirname']."/"."errorlog".".txt");

    }



    public function __toString()
    {
        return sprintf(
            '%s%s%s%s%s',
            $this->command,
            $this->apply_padding(join(" ",$this->options)),
            $this->apply_padding($this->src),
            $this->apply_padding($this->dest),
            $this->apply_padding($this->pipeerror())
        );


    }


    private function apply_padding($string)
    {
        $string = (string) $string;
        if (!empty($string)) {
            return " $string";
        }
        return $string;
    }





}