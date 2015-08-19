<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 8/5/15
 * Time: 11:08 AM
 */
namespace App\Services\SVG;
class IUSVGBase extends SVGBase {

    protected $tabHeight = 68;
    protected $tabWidth = 58;

    function __construct() {
        parent::__construct();

    }

    function addTab($h) {
        $r = $this->xml->addChild('rect');
        $r['width'] = $this->tabWidth;
        $r['height'] = $h;
        $r['fill'] = '#951B1E';
    }

    function addLogo() {
        $logo = '<polygon transform="translate(-1 0)" fill="#FFFFFF" points="35.6,16.4 35.6,20 38.1,20 38.1,40.1 32.8,40.1 32.8,13.6 35.6,13.6 35.6,10 22.4,10 22.4,13.6
	25.2,13.6 25.2,40.1 19.9,40.1 19.9,20 22.4,20 22.4,16.4 10,16.4 10,20 12.8,20 12.8,43.3 17.4,47.9 25.2,47.9 25.2,53.3
	22.4,53.3 22.4,58 35.6,58 35.6,53.3 32.8,53.3 32.8,47.9 40.6,47.9 45.2,43.3 45.2,20 48,20 48,16.4 "/>';
        $this->addXMLStr($this->xml, $logo);
    }

}