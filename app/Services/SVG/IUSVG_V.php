<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 8/24/15
 * Time: 4:05 PM
 */

namespace App\Services\SVG;


class IUSVG_V extends IUSVGBase {

    protected $text_xml="";
    protected $primary,$secondary,$tertiary,$subprimary;
    protected $lookup = array('signatureOne',
        'signatureTwo',
        'signatureThree',
        'signatureFour',
        'signatureFive'
       );

    const PRIMARY_FONT_SIZE   =   32.64;
    const SECONDARY_FONT_SIZE =   32.64;
    const TERTIARY_FONT_SIZE  =   19;


    protected $refPts = 10;
    protected $xref;


    static $primary_font = array('family'=> "'BentonSansCond-Bold'",'svgfile'=>'benton-sans-cond-bold');
    static $secondary_font = array('family'=> "'BentonSansCond-Regular'",'svgfile'=>'benton-sans-cond-regular');
    static $tertiary_font = array('family'=> "'BentonSansCond-Regular'",'svgfile'=>'benton-sans-cond-regular');


    function __construct($p,$s,$t,$v) {

        $this->tabColor='#951B1E';

        parent::__construct();


        $this->primary = strtoupper($p);

        $this->secondary=strtoupper($s);
        $this->tertiary=$t;
        $key =$v-1;
        $func = $this->lookup[$key];

        $this->xref = 8;

        call_user_func(array($this,$func));
    }

    private function isLongPrimary(){
        return strlen($this->primary)>24?true:false;
    }


    /**
     * Signature One - contains one element - Primary
     */
    public function signatureOne(){

        if(!$this->required_if_allofThese(array($this->primary)))return "";

        if($this->isLongPrimary())return "";

        $font = self::$primary_font['svgfile'];
        $text = $this->primary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);
        $total_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u']);

        $text_y =  $this->tabHeight+2*($this->refPts+$this->refPts/2)+$this->refPts/2;
        $trident_y =  $this->refPts+$this->refPts/2;

        $trident_x = $total_width/2 - $this->tabWidth/2;

        $total_text_height = $text_y + $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);

        $this->init($total_width+$this->refPts+5,$total_text_height+$this->refPts,$this->tabHeight,
            $trident_x,
            $trident_y);

      $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_text_height\"
     viewBox='0 -$total_text_height  $total_width $total_text_height'>$result</svg>");

    }

    /**
     * Signature Two - contains one element - Primary, second line secondary
     */
    public function signatureTwo(){

        //rules
        if(!$this->required_if_allofThese(array($this->primary,$this->secondary)))return "";

        if($this->isLongPrimary())return "";

        $svgFont = new SVGFont();

        $font = self::$primary_font['svgfile'];
        $text = $this->primary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $pXML = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);
        $p_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u']);
        $p_height =  $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);


        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $sXML = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $s_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u']);
        $s_height =  $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,$extents['u']);


        if($p_width>$s_width)
            $total_width = $p_width;
        else
            $total_width=$s_width;


        $p_x = $total_width/2 - $p_width/2;
        $s_x = $total_width/2 - $s_width/2;

        $trident_x = $total_width/2 - $this->tabWidth/2;
        $trident_y = 15;

        $total_height  = ($this->refPts + $this->refPts/2) + $this->tabHeight + ($this->refPts + $this->refPts/2) +
            $p_height+($this->refPts + $this->refPts/2)+$s_height+($this->refPts + $this->refPts/2);


        $p_y = $total_height - ($this->refPts + $this->refPts/2) - $s_height - $p_height + $this->refPts - 1 ;
        $s_y = $p_y+$p_height + $this->refPts - 4; // -4 parallax  - primary parallax

        $this->init($total_width+$this->refPts+5,$total_height,$this->tabHeight,
            $trident_x,
            $trident_y);

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$p_x -$p_y  $total_width $total_height'>$pXML</svg>");

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$s_x -$s_y  $total_width $total_height'>$sXML</svg>");


    }



    /**
     * Signature Three - contains one element - Primary, second line secondary
     */
    public function signatureThree(){

        //rules
        if(!$this->required_if_allofThese(array($this->primary,$this->secondary,$this->tertiary)))return "";
        if($this->isLongPrimary())return "";

        $svgFont = new SVGFont();

        $font = self::$primary_font['svgfile'];
        $text = $this->primary;

        //Primary
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $pXML = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);
        $p_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u']);
        $p_height =  $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);


        //secondary
        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $sXML = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $s_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u']);
        $s_height =  $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,$extents['u']);


        //tertiary
        $font = self::$tertiary_font['svgfile'];
        $text = $this->tertiary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $tXML = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $t_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u']);
        $t_height =  $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,$extents['u']);


        if($p_width>$s_width)
            $total_width = $p_width;
        else
            $total_width=$s_width;

        if($total_width<$t_width)
            $total_width=$t_width;


        $p_x = $total_width/2 - $p_width/2;
        $s_x = $total_width/2 - $s_width/2;
        $t_x = $total_width/2 - $t_width/2;


        $trident_x = $total_width/2 - $this->tabWidth/2;
        $trident_y = 15;

        $total_height  = ($this->refPts + $this->refPts/2) + $this->tabHeight + ($this->refPts + $this->refPts/2) +
            $p_height+($this->refPts + $this->refPts/2)+$s_height+($this->refPts + $this->refPts/2)+$t_height+ ($this->refPts + $this->refPts/2);


        $p_y = $total_height - 2*($this->refPts + $this->refPts/2) - $s_height - $p_height - $t_height + $this->refPts - 1 ;
        $s_y = $p_y+$p_height + $this->refPts - 4; // -4 parallax  - primary parallax

        $t_y = $s_y+$s_height +$this->refPts +2; // -2 secondary parallax


        $this->init($total_width+$this->refPts+5,$total_height,$this->tabHeight,
            $trident_x,
            $trident_y);

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$p_x -$p_y  $total_width $total_height'>$pXML</svg>");

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$s_x -$s_y  $total_width $total_height'>$sXML</svg>");

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$t_x -$t_y  $total_width $total_height'>$tXML</svg>");


    }

    /**
     * Signature Four - contains one element - Primary, second line secondary, third line tertiary
     */
    public function signatureFour(){

        //rules
        if(!$this->required_if_allofThese(array($this->primary,$this->secondary,$this->tertiary)))return "";
        if($this->isLongPrimary())return "";
        $svgFont = new SVGFont();

        $font = self::$primary_font['svgfile'];
        $text = $this->primary;

        //Primary
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $pXML = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);
        $p_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u']);
        $p_height =  $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);


        //secondary
        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $sXML = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $s_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u']);
        $s_height =  $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,$extents['u']);


        //tertiary
        $font = self::$tertiary_font['svgfile'];
        $text = $this->tertiary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $tXML = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $t_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u']);
        $t_height =  $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,$extents['u']);


        if($p_width>$s_width)
            $total_width = $p_width;
        else
            $total_width=$s_width;

        if($total_width<$t_width)
            $total_width=$t_width;


        $p_x = $total_width/2 - $p_width/2;
        $s_x = $total_width/2 - $s_width/2;
        $t_x = $total_width/2 - $t_width/2;


        $trident_x = $total_width/2 - $this->tabWidth/2;
        $trident_y = 15;

        $total_height  = ($this->refPts + $this->refPts/2) + $this->tabHeight + ($this->refPts + $this->refPts/2) +
            $p_height+($this->refPts + $this->refPts/2)+$s_height+($this->refPts + $this->refPts/2)+$t_height+ ($this->refPts + $this->refPts/2);



        $s_y = $total_height - 2*($this->refPts + $this->refPts/2) - $s_height - $p_height - $t_height - 2;
        $p_y = $s_y+$s_height +$p_height + $this->refPts - 1 ; // -2 parallax  - secondary parallax

        $t_y = $p_y+$p_height +$this->refPts - 4; // -4 secondary parallax


        $this->init($total_width+$this->refPts+5,$total_height,$this->tabHeight,
            $trident_x,
            $trident_y);


        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$s_x -$s_y  $total_width $total_height'>$sXML</svg>");

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$p_x -$p_y  $total_width $total_height'>$pXML</svg>");

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$t_x -$t_y  $total_width $total_height'>$tXML</svg>");


    }

    /**
     * Signature Five - contains one element - Primary, second line secondary
     */
    public function signatureFive(){

        //rules
        if(!$this->required_if_allofThese(array($this->primary,$this->secondary,$this->tertiary)))return "";
        if($this->isLongPrimary())return "";
        $svgFont = new SVGFont();

        $font = self::$primary_font['svgfile'];
        $text = $this->primary;

        //Primary
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $pXML = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);
        $p_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u']);
        $p_height =  $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);


        //secondary
        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $sXML = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $s_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u']);
        $s_height =  $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,$extents['u']);


        //tertiary
        $font = self::$tertiary_font['svgfile'];
        $text = $this->tertiary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");


        if($p_width>$s_width)
            $total_width = $p_width;
        else
            $total_width=$s_width;


        $p_x = $total_width/2 - $p_width/2;
        $s_x = $total_width/2 - $s_width/2;


        $trident_x = $total_width/2 - $this->tabWidth/2;
        $trident_y = 15;

        $total_height  = ($this->refPts + $this->refPts/2) + $this->tabHeight + ($this->refPts + $this->refPts/2) +
            $p_height+($this->refPts + $this->refPts/2)+$s_height+($this->refPts + $this->refPts/2)+ ($this->refPts + $this->refPts/2);

        $s_y = $total_height - 2*($this->refPts + $this->refPts/2) - $s_height - $p_height  -2;
        $p_y = $s_y+$s_height+$this->refPts+$this->refPts + $this->refPts/2;


        $this->init($total_width+$this->refPts+5,$total_height,$this->tabHeight,
            $trident_x,
            $trident_y);

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$s_x -$s_y  $total_width $total_height'>$sXML</svg>");

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$p_x -$p_y  $total_width $total_height'>$pXML</svg>");


    }

}
