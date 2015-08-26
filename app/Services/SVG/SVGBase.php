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

    protected $xml,$tagXML;

    function __construct(){

        $this->xml  = simplexml_load_string('<svg class="svg" version="1.1"  xml:space="preserve"
        xmlns="http://www.w3.org/2000/svg"/>');
    }

    function init($width, $height){

        $w = $width."px";
        $h = $height."px";

        $this->xml['enable-background']="new 0 0 $width $height";
        $this->xml['x']= "0px";
        $this->xml['y']= "0px";

        $this->xml['preserveAspectRatio'] = "xMinYMin";

        $this->xml['viewBox']="0 0 $width $height";
        $this->xml['width']=$width."px";
        $this->xml['height']=$height."px";



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

    public function __toString() {
        if($this->xml->children()->saveXML()!="")
         return $this->prettyXML();
        return "";
    }

    function metrics($text='Hello world!', $font='bentonSansCond-Regular', $size=32, $maxWidth=400) {

        $fonts = array(
            'BentonSansCond-Regular' => 'benton-sans-cond-regular.ttf',
            'BentonSansCond-Bold' => 'benton-sans-cond-bold.ttf'
        );

        $f = "/ip/fonts/wwws/fonts/{$fonts['BentonSansCond-Bold']}";

        $a = imagettfbbox($size, 0.0, $f, $text);
        $o = new stdClass;
        $o->ascent = abs($a[7]);
        $o->descent = abs($a[1]);
        $o->height = $o->ascent+$o->descent;
        $o->width  = abs($a[0])+abs($a[2]);
        return $o;
    }

    function funitsToPx($units,$pointsize,$units_per_em){
        //http://search.cpan.org/dist/Font-TTFMetrics/lib/Font/TTFMetrics.pm
        //fIUnits * pointsize * resolution /(72 * units_per_em);
        return $units*$pointsize*72/(72*$units_per_em);

    }


}