<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 8/5/15
 * Time: 11:09 AM
 */
namespace App\Services\SVG;

class IUSVG extends IUSVGBase {

    protected $text_xml="";
    protected $primary,$secondary,$tertiary;
    protected $lookup = array(
        'signatureOne',
        'signatureTwo',
        'signatureThree',
        'signatureFour',
        'signatureFive',
        'signatureSix',
        'signatureSeven');

    const PRIMARY_FONT_SIZE   =   32.64;
    const SECONDARY_FONT_SIZE =   32.64;
    const TERTIARY_FONT_SIZE  =   19;


    protected $start_x = 72;
    protected $start_y = 10;
    protected $refPts = 10;
    protected $trident_serif=40;
    protected $trident_top= 35 ;//(40 - 5);



    static $primary_font = array('family'=> "'BentonSansCond-Bold'",'svgfile'=>'benton-sans-cond-bold');
    static $secondary_font = array('family'=> "'BentonSansCond-Regular'",'svgfile'=>'benton-sans-cond-regular');
    static $tertiary_font = array('family'=> "'BentonSansCond-Regular'",'svgfile'=>'benton-sans-cond-regular');


    function __construct($p,$s,$t,$v) {

        parent::__construct();

        $this->primary = strtoupper($p);
        $this->secondary=strtoupper($s);
        $this->tertiary=$t;
        $func = $this->lookup[$v-1];
        call_user_func(array($this,$func));

    }


    /**
     * Signature One - contains one element - Primary
     */
    public function signatureOne(){

        $x = $this->tabWidth + $this->refPts;

        $this->addTab($this->tabHeight);
        $this->addLogo();

        $font = self::$primary_font['svgfile'];
        $text = $this->primary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);

        $textXML = "<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
         width='$this->default_width' height='$this->default_height'
         viewBox='-$x -$this->trident_serif  $this->default_width $this->default_height'>$result</svg>";

        $this->addXMLStr($this->xml,$textXML);

    }

    public function signatureTwo(){

        $x = $this->tabWidth + $this->refPts; //points ;

        $this->addTab($this->tabHeight);
        $this->addLogo();

        $font = self::$primary_font['svgfile'];
        $text = $this->primary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);
        $pXML =
            "<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
         width='$this->default_width' height='$this->default_height'
         viewBox='-$x -$this->trident_serif  $this->default_width $this->default_height'>$result</svg>";

        // add secondary
        $x += ($extents["w"] * $extents['s'])+$this->refPts;

        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::SECONDARY_FONT_SIZE,$extents);

        $this->addXMLStr($this->xml,$pXML);
        $this->addXMLStr($this->xml,
            "<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
         width='$this->default_width' height='$this->default_height'
         viewBox='-$x -$this->trident_serif  $this->default_width $this->default_height'>$result</svg>");


    }


    public function signatureThree(){

        $x = $this->tabWidth + $this->refPts; //points ;
        $y = $this->trident_top;

        // add secondary
        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::SECONDARY_FONT_SIZE,$extents);

        $sXML = "<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
         width='$this->default_width' height='$this->default_height'
         viewBox='-$x -$y  $this->default_width $this->default_height'>$result</svg>";

        // add primary
        $x += ($extents["w"] * $extents['s']) + ($this->refPts);

        $font = self::$primary_font['svgfile'];
        $text = $this->primary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);

        $pXML ="<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
         width='$this->default_width' height='$this->default_height'
         viewBox='-$x -$y  $this->default_width $this->default_height'>$result</svg>";

        // add tertiary
        // $y += ($extents["h"] * $extents['s']) + ($this->refPts);
        $y = ($this->tabHeight-$this->refPts);
        $x = $this->tabWidth + $this->refPts;

        $font = self::$tertiary_font['svgfile'];
        $text = $this->tertiary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $text3XML = "<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
         width='$this->default_width' height='$this->default_height'
         viewBox='-$x -$y  $this->default_width $this->default_height'>$result</svg>";


        $this->addTab($this->tabHeight);
        $this->addLogo();

        $this->addXMLStr($this->xml,$sXML);
        $this->addXMLStr($this->xml,$pXML);
        $this->addXMLStr($this->xml,$text3XML);


    }

    public function signatureFour(){

        $x = $this->tabWidth + $this->refPts; //points ;
        $y = $this->trident_top;

        // add secondary
        $font = self::$primary_font['svgfile'];
        $text = $this->primary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);

        $pXML = "<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
         width='$this->default_width' height='$this->default_height'
         viewBox='-$x -$y  $this->default_width $this->default_height'>$result</svg>";

        // add primary
        $y += ($extents['h']*$extents['s'])+$this->refPts;

        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);

        $sXML ="<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
         width='$this->default_width' height='$this->default_height'
         viewBox='-$x -$y  $this->default_width $this->default_height'>$result</svg>";



        $this->addTab($this->tabHeight);
        $this->addLogo();

        $this->addXMLStr($this->xml,$pXML);
        $this->addXMLStr($this->xml,$sXML);
    }

    public function signatureFive(){

        $x = $this->tabWidth + $this->refPts; //points ;
        $y = 23;

        // add secondary
        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);

        $pXML = "<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
         width='$this->default_width' height='$this->default_height'
         viewBox='-$x -$y  $this->default_width $this->default_height'>$result</svg>";

        // add primary
        //$y += ($extents['h']*$extents['s'])+ $this->refPts;
        $y = $this->tabHeight-10;

        $font = self::$primary_font['svgfile'];
        $text = $this->primary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);

        $sXML ="<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
         width='$this->default_width' height='$this->default_height'
         viewBox='-$x -$y  $this->default_width $this->default_height'>$result</svg>";



        $this->addTab($this->tabHeight);
        $this->addLogo();

        $this->addXMLStr($this->xml,$pXML);
        $this->addXMLStr($this->xml,$sXML);

    }

    public function signatureSix(){

        $x = $this->tabWidth + $this->refPts; //points ;
        $y = $this->trident_top;

        // add secondary
        $font = self::$primary_font['svgfile'];
        $text = $this->primary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);

        $pXML = "<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
         width='$this->default_width' height='$this->default_height'
         viewBox='-$x -$y  $this->default_width $this->default_height'>$result</svg>";

        // add primary
        $y+=($extents['h']*$extents['s'])+($this->refPts/2);

        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);

        $sXML ="<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
         width='$this->default_width' height='$this->default_height'
         viewBox='-$x -$y  $this->default_width $this->default_height'>$result</svg>";

        $y = 82;

        $font = self::$tertiary_font['svgfile'];
        $text = $this->tertiary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);

        $tXML ="<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
         width='$this->default_width' height='$this->default_height'
         viewBox='-$x -$y  $this->default_width $this->default_height'>$result</svg>";


        $this->addTab($this->tabHeight+$this->refPts+($this->refPts/2));
        $this->addLogo();

        $this->addXMLStr($this->xml,$pXML);
        $this->addXMLStr($this->xml,$sXML);
        $this->addXMLStr($this->xml,$tXML);

    }


    public function signatureSeven(){

        $x = $this->tabWidth + $this->refPts; //points ;
        $y = 23;

        // add secondary
        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);

        $pXML = "<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
         width='$this->default_width' height='$this->default_height'
         viewBox='-$x -$y  $this->default_width $this->default_height'>$result</svg>";

        // add primary
        $y = $this->tabHeight-10;

        $font = self::$primary_font['svgfile'];
        $text = $this->primary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);

        $sXML ="<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
         width='$this->default_width' height='$this->default_height'
         viewBox='-$x -$y  $this->default_width $this->default_height'>$result</svg>";

        $y = 82;

        $font = self::$tertiary_font['svgfile'];
        $text = $this->tertiary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);

        $tXML ="<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
         width='$this->default_width' height='$this->default_height'
         viewBox='-$x -$y  $this->default_width $this->default_height'>$result</svg>";


        $this->addTab($this->tabHeight+$this->refPts+($this->refPts/2));
        $this->addLogo();

        $this->addXMLStr($this->xml,$pXML);
        $this->addXMLStr($this->xml,$sXML);
        $this->addXMLStr($this->xml,$tXML);

    }
}
