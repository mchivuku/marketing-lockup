<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 8/5/15
 * Time: 11:09 AM
 */
namespace App\Services\SVG;

class IUSVG extends IUSVGBase {

    protected $primary,$secondary,$tertiary;
    protected $lookup = array('signatureOne','signatureTwo','signatureThree','signatureFour',
        'signatureFive','signatureSix','signatureSeven');

    const PRIMARY_FONT_SIZE   =   32.64;
    const SECONDARY_FONT_SIZE =   32.64;
    const TERTIARY_FONT_SIZE  =   19;

    protected $start_x = 72;
    protected $start_y = 5;

    static $primary_font = array('family'=> "'BentonSansCond-Bold'",'svgfile'=>'benton-sans-cond-bold');
    static $secondary_font = array('family'=> "'BentonSansCond-Regular'",'svgfile'=>'benton-sans-cond-regular');
    static $tertiary_font = array('family'=> "'BentonSansCond-Regular'",'svgfile'=>'benton-sans-cond-regular');


    function __construct($p,$s,$t,$v) {

        parent::__construct();

        $this->primary = $p;
        $this->secondary=$s;
        $this->tertiary=$t;

        $this->line('0','10.2','1020','10.2');
        $this->line('0','59.2','1020','59.2');

        $methodName = $this->lookup[$v-1];
        call_user_func(array($this,$methodName));

    }

    private function addPrimary($x,$y,$font_size=self::PRIMARY_FONT_SIZE){

        $font = self::$primary_font['svgfile'];
        $text = $this->primary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, $font_size,$extents);
        $result = "<svg xmlns=\"http://www.w3.org/2000/svg\"viewBox=\"-$x -$y 612 792\">$result</svg>";
        $this->addXMLStr($this->xml, $result);
        return $extents;
    }

    private function addSecondary($x,$y,$font_size=self::SECONDARY_FONT_SIZE){

        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, $font_size,$extents);
        $result = "<svg xmlns=\"http://www.w3.org/2000/svg\"viewBox=\"-$x -$y 612 792\">$result</svg>";
        $this->addXMLStr($this->xml, $result);
        return $extents;

    }

    private function addTertiary($x,$y,$font_size=self::TERTIARY_FONT_SIZE){

        $font = self::$tertiary_font['svgfile'];
        $text = $this->tertiary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, $font_size,$extents);
        $result = "<svg xmlns=\"http://www.w3.org/2000/svg\"viewBox=\"-$x -$y 612 792\">$result</svg>";
        $this->addXMLStr($this->xml, $result);
        return $extents;


    }

    /**
     * Signature One - contains one element - Primary
     */
    public function signatureOne(){

         $this->addPrimary($this->start_x,$this->start_y,self::PRIMARY_FONT_SIZE);
    }

    /**
     * Signature One - contains two elements - Primary,secondary
     */
    public function signatureTwo(){

        // add primary
        $x = $this->start_x; $y = $this->start_y;
        $horizonAdvY = $this->addPrimary($x,$y,self::PRIMARY_FONT_SIZE);

        // add secondary
        $y = 5;
        $x += $horizonAdvY["w"] * 0.015625;

        $this->addSecondary($x,$y);

    }

    /**
     * Method to display Primary Secondary and Tertiary
     */
    public function signatureThree(){
        $x = $this->start_x; $y = $this->start_y;
        $horizonAdvY = $this->addSecondary($x,$y);

        // add secondary
        $y = 5;
        $x += $horizonAdvY["w"] * 0.015625;

        $horizonAdvY = $this->addPrimary($x,$y);
        $y+=$horizonAdvY["h"] * 0.015625;

        $x = $this->start_x;
        $this->addTertiary($x,$y);

    }

    /***
     * Method to display Primary, Secondary in two lines
     */
    public function signatureFour(){

        //add primary
        $x = $this->start_x; $y = $this->start_y;
        $horizonAdvY = $this->addPrimary($x,$y);

        // add secondary
        $y += $horizonAdvY["h"] * 0.015625;
        $this->addSecondary($x,$y,self::TERTIARY_FONT_SIZE);

    }

    public function signatureFive(){

        //add primary
        $x = $this->start_x; $y = $this->start_y;
        $horizonAdvY = $this->addSecondary($x,$y,self::TERTIARY_FONT_SIZE);

        // add secondary
        $y += $horizonAdvY["h"] * $horizonAdvY['s'];
        $this->addPrimary($x,$y,self::PRIMARY_FONT_SIZE);


    }

    public function signatureSix(){

        //add primary
        $x = $this->start_x; $y = $this->start_y;
        $horizonAdvY = $this->addPrimary($x,$y,self::PRIMARY_FONT_SIZE);

        // add secondary
        $y += $horizonAdvY["h"] * $horizonAdvY['s'];
        $this->addSecondary($x,$y,self::TERTIARY_FONT_SIZE);


    }

    public function signatureSeven(){

        //add secondary
        $x = $this->start_x; $y = $this->start_y;
        $horizonAdvY = $this->addSecondary($x,$y,self::TERTIARY_FONT_SIZE);

        // add primary
        $y += $horizonAdvY["h"] * $horizonAdvY['s'];
        $horizonAdvY = $this->addPrimary($x,$y,self::PRIMARY_FONT_SIZE);

        // add tertiary
        $y += $horizonAdvY["h"] * $horizonAdvY['s'];
        $this->addTertiary($x,$y,self::TERTIARY_FONT_SIZE);

    }


}

echo new IUSVG('Primary','Secondary','Tertiary',1);