<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 8/5/15
 * Time: 11:02 AM
 */
namespace App\Services\SVG;
class SVGBase {

    public $fonts = array(
        'BentonSansCond-Regular' => 'benton-sans-cond-regular.ttf',
        'BentonSansCond-Bold' => 'benton-sans-cond-bold.ttf');

    protected $default_width = 600 ;
    protected $default_height = 719;


    function __construct($width=null, $height=null, $fixed=false){
        $this->xml  = simplexml_load_string('<svg class="svg" version="1.1"  xml:space="preserve"
        xmlns="http://www.w3.org/2000/svg"/>');

        if(isset($width)){
            $w = $width;
        }else{
            $w = $this->default_width;
        }

        if(isset($height)){
            $h = $height;
        }else{
            $h = $this->default_height;
        }


        if($fixed) {
            $this->xml['width'] = $w;
            $this->xml['height'] = $h;
        }

        $this->xml['viewBox'] = "0 0 $w $h";
        $this->xml['preserveAspectRatio'] = "xMinYMin";
        $this->xml['x']= "0px";
        $this->xml['y']= "0px";
        $this->xml['enable-background']="new 0 0 $w $height";

    }

    function addXMLStr($parent, $childStr) {


        $parentDom = dom_import_simplexml($parent);

        $childDom = dom_import_simplexml(simplexml_load_string($childStr));
        $childDom = $parentDom->ownerDocument->importNode($childDom, true);
        $parentDom->appendChild($childDom);
        return $childDom;
    }

    function prettyXML() {
        $dom = new \DomDocument();
        $dom->formatOutput = true;
        $dom->loadXml($this->xml->saveXML());
        $xml = $dom->saveXML();
        $xml = substr($xml, 22);
        return $xml;
    }

    function __toString() {
        return $this->prettyXML();
    }

    function line($x1, $y1, $x2, $y2) {

        $stoke_color = "'#FF0000'";
        $stroke_width = "'2.500000e-02'";

        $e = "<line fill='none' x1='$x1' y1='$y1' x2='$x2'
              y2='$y2'stroke='$stoke_color' stroke-width='$stroke_width'/>";
        $line = $this->addXMLStr($this->xml, $e);
    }

    function grid() {
        for($y=0; $y<1000; $y+=10) $this->line(0, $y, 1000, $y);
        for($x=0; $x<1000; $x+=10) $this->line($x, 0, $x, 1000);
    }

    function metrics($text='Hello world!', $font='bentonSansCond-Regular', $size=32, $maxWidth=400) {
        $f = "/ip/fonts/wwws/fonts/{$this->fonts[$font]}";
        $a = imagettfbbox($size, 0.0, $f, $text);
        $o = new stdClass;
        $o->ascent = abs($a[7]);
        $o->descent = abs($a[1]);
        $o->height = $o->ascent+$o->descent;
        $o->width  = abs($a[0])+abs($a[2]);
        return $o;
    }
}