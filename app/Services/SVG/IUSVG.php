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
    protected $primary,$secondary,$tertiary,$subprimary;
    protected $lookup = array('signatureOne','signatureTwo',
        'signatureThree',
        'signatureFour',
        'signatureFive',
        'signatureSix',
        'signatureSeven','signatureEight','signatureNine');

    const PRIMARY_FONT_SIZE   =   32.64;
    const SECONDARY_FONT_SIZE =   32.64;
    const TERTIARY_FONT_SIZE  =   19;


    protected $refPts = 10;
    protected $trident_serif= 44;
    protected $trident_top= 33;
    protected $second_line = 38;
    protected $trident_primary_top = 34;
    protected $trident_secondary_top=24;
    protected $xref;


    static $primary_font = array('family'=> "'BentonSansCond-Bold'",'svgfile'=>'benton-sans-cond-bold');
    static $secondary_font = array('family'=> "'BentonSansCond-Regular'",'svgfile'=>'benton-sans-cond-regular');
    static $tertiary_font = array('family'=> "'BentonSansCond-Regular'",'svgfile'=>'benton-sans-cond-regular');


    function __construct($p,$s,$t,$v) {

        parent::__construct();
        $this->tabColor='#951B1E';

        if(strlen($p)>24){
            //get words
            $string=  wordwrap($p, 24, "@");
            $strings = explode("@",$string);

            $this->primary=strtoupper($strings[0]);
            $this->subprimary=strtoupper($strings[1]);

        }else{

            $this->primary = strtoupper($p);

        }

        $this->secondary=strtoupper($s);
        $this->tertiary=$t;
        $key =$v-1;
        $func = $this->lookup[$key];


        $this->xref = ($this->tabWidth-2)+$this->refPts+$this->refPts/2;


       call_user_func(array($this,$func));

    }



    /**
     * Signature One - contains one element - Primary
     */
    public function signatureOne(){

        if(!$this->required_if_allofThese(array($this->primary)))return "";

        $font = self::$primary_font['svgfile'];
        $text = $this->primary;

        $svgFont = new SVGFont();
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);
        $total_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u']) + $this->refPts  +
            $this->tabWidth+$this->refPts/2;

        $this->init($total_width,$this->tabHeight);

        $textXML =
            "<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"  height=\"$this->tabHeight\"
viewBox='-$this->xref -$this->trident_serif  $total_width $this->tabHeight'>$result</svg>";

        $this->addXMLStr($this->xml,$textXML);

    }


    /**
     * Function to generate signature two format that has one line - primary and secondary.
     */
    public function signatureTwo(){
        //rules
        if(!$this->required_if_allofThese(array($this->primary,$this->secondary)))return "";


        $svgFont = new SVGFont();

        /**  PRIMARY TEXT  */
        $font = self::$primary_font['svgfile'];
        $text = $this->primary;
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $result = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);


        $p_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u']) ;

        $pXML = $result;

        /**  SECONDARY TEXT  */
        $font = self::$secondary_font['svgfile'];
        if(strlen($this->primary)+strlen($this->secondary)>=24){
            $text = substr($this->secondary,0,(24-strlen($this->primary)));
        }else{
            $text = $this->secondary;
        }
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $sXML = $svgFont->textToPaths($text, self::SECONDARY_FONT_SIZE,$extents);
        $s_width = $this->funitsToPx($extents['w'],self::SECONDARY_FONT_SIZE,$extents['u']) ;

        $total_width= $p_width + $s_width + $this->tabWidth+ $this->refPts + $this->refPts;

        //10px between the words
        $s_ref =  $this->xref+$p_width+($this->refPts-2);

        $this->init($total_width,$this->tabHeight);

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"
                 width=\"$total_width\"  height=\"$this->tabHeight\"
             viewBox='-$this->xref -$this->trident_serif  $total_width $this->tabHeight'>$pXML</svg>");

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"$total_width\"
            height=\"$this->tabHeight\"
            viewBox='-$s_ref -$this->trident_serif  $total_width $this->tabHeight'>$sXML</svg>");

    }


    /**
     * Function to generate signature three that has two lines - SEcondary Primary, Tertiary in second line
     */
    public function signatureThree(){

        //rules
        if(!$this->required_if_allofThese(array($this->primary,$this->secondary,$this->tertiary)))return "";


        $svgFont = new SVGFont();

        /**  SECONDARY $font */
        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $spath = $svgFont->textToPaths($text, self::SECONDARY_FONT_SIZE,$extents);


        $s_width = $this->funitsToPx($extents['w'],self::SECONDARY_FONT_SIZE,$extents['u']) ;

        //10 px width
        $xpref = $this->xref+$s_width+6;

        /**  PRIMARY $font */
        $font = self::$primary_font['svgfile'];
        if(strlen($this->primary)+strlen($this->secondary)>=24){
            $text = substr($this->primary,0,(24-strlen($this->secondary)));
        }else{
            $text = $this->primary;
        }
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $result = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);

        $p_width= $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u']) ;

        $ppath =$result;


        /**  TERTIARY $height */
        $height = ($this->tabHeight-10);
        $font = self::$tertiary_font['svgfile'];
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $tpath = $svgFont->textToPaths($this->tertiary, self::TERTIARY_FONT_SIZE,$extents);


        $view_port_height = $this->tabHeight ;
        $view_port_width = $this->tabWidth+$this->refPts+$p_width+$s_width+$this->refPts;
        $this->init($view_port_width,$view_port_height);

        $this->addXMLStr($this->xml, "<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
               width=\"$view_port_width\"  height=\"$this->tabHeight\"
             viewBox='-$this->xref -$this->trident_primary_top  $view_port_width $view_port_height'>
             $spath</svg>");
        $this->addXMLStr($this->xml, "<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
 width=\"$view_port_width\"  height=\"$this->tabHeight\"
             viewBox='-$xpref -$this->trident_top  $view_port_width $view_port_height'>
             $ppath</svg>");
        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
 width=\"$view_port_width\"  height=\"$this->tabHeight\"
                 viewBox='-$this->xref -$height  $view_port_width $view_port_height'>$tpath</svg>");

    }

    /**
     * Function to generate signature four that
     * has two lines -> Primary/Secondary
     *
     */
    public function signatureFour(){

        //rules
        if(!$this->required_if_allofThese(array($this->primary,$this->secondary)))return "";


        $svgFont = new SVGFont();

        $font = self::$primary_font['svgfile'];
        $text = $this->primary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $pXML = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);
        $p_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u']);


        /**  SECONDARY $height */
        $height = ($this->tabHeight - $this->refPts);

        $font=self::$secondary_font['svgfile'];
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $sXML = $svgFont->textToPaths($this->secondary, self::TERTIARY_FONT_SIZE,$extents);
        $view_port_height = $this->tabHeight ;
        $s_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u']);


        $total_width = ($p_width>$s_width?$p_width:$s_width) + $this->tabWidth+$this->refPts + $this->refPts;
        $this->init($total_width,
            $view_port_height);

        $this->addXMLStr($this->xml, "<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
width=\"$total_width\"  height=\"$view_port_height\"
             viewBox='-$this->xref -$this->trident_primary_top  $total_width $view_port_height'>
             $pXML</svg>");
        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
width=\"$total_width\"  height=\"$view_port_height\"
                 viewBox='-$this->xref -$height  $total_width $view_port_height'>$sXML</svg>");


    }

    /**
     * Function to generate signature four that
     * has two lines -> Secondary/Primary
     *
     */
    public function signatureFive(){

        //rules
        if(!$this->required_if_allofThese(array($this->primary,$this->secondary)))return "";

        $svgFont = new SVGFont();

        /**  SECONDARY  */
        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $sXML = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $s_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u']);

        $font=self::$primary_font['svgfile'];
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $pXML = $svgFont->textToPaths($this->primary, self::PRIMARY_FONT_SIZE,$extents);
        $p_width =$this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u']);


        $view_port_height = $this->tabHeight;
        $view_port_width= ($p_width>$s_width?$p_width:$s_width)+$this->tabWidth+$this->refPts+$this->refPts;

        $this->init($view_port_width,$view_port_height);

        $this->addXMLStr($this->xml, "<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
width=\"$view_port_width\"  height=\"$view_port_height\"
             viewBox='-$this->xref -$this->trident_secondary_top $view_port_width $view_port_height'>
             $sXML</svg>");
        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
width=\"$view_port_width\"  height=\"$view_port_height\"
                 viewBox='-$this->xref -58  $view_port_width $view_port_height'>$pXML</svg>");



    }

    public function signatureSix()
    {
        //rules
        if(!$this->required_if_allofThese(array($this->primary,$this->secondary,$this->tertiary)))return "";

        $svgFont = new SVGFont();

        $font = self::$primary_font['svgfile'];
        $text = $this->primary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $pXML = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);
        $p_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u']);


        /**  SECONDARY  */
        $height = ($this->tabHeight - $this->refPts);

        $font=self::$secondary_font['svgfile'];
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $sXML = $svgFont->textToPaths($this->secondary, self::TERTIARY_FONT_SIZE,$extents);
        $s_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u']);


        /**  TERTIARY  */

        $font=self::$tertiary_font['svgfile'];
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $tXML = $svgFont->textToPaths($this->tertiary, self::TERTIARY_FONT_SIZE,$extents);


        $view_port_height = $this->tabHeight+$this->refPts/2
            + $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,
                $extents['u'])-2;


        $t_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u']);

        $total_width = (($p_width>$s_width?$p_width:$s_width)>$t_width?($p_width>$s_width?$p_width:$s_width):$t_width)
            + $this->tabWidth + $this->refPts + $this->refPts ;

        $this->init($total_width,$view_port_height+abs($this->refPts/2),$view_port_height);

        $th = $view_port_height + 4;

        $this->addXMLStr($this->xml, "<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
            width=\"$total_width\"  height=\"$view_port_height\"
             viewBox='-$this->xref -$this->trident_primary_top  $total_width $view_port_height'>
             $pXML</svg>");
        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
 width=\"$total_width\"  height=\"$view_port_height\"
                 viewBox='-$this->xref -$height  $total_width $view_port_height'>$sXML</svg>");

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
 width=\"$total_width\"  height=\"$th\"
                 viewBox='-$this->xref -$view_port_height  $total_width  $th'>$tXML</svg>");

    }

    public function signatureSeven(){

        //rules
        if(!$this->required_if_allofThese(array($this->primary,$this->secondary,$this->tertiary)))return "";


        $svgFont = new SVGFont();

        /**  SECONDARY  */
        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $sXML = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $s_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u']);

        /**  PRIMARY  */
        $font=self::$primary_font['svgfile'];
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $pXML = $svgFont->textToPaths($this->primary, self::PRIMARY_FONT_SIZE,$extents);
        $p_width =$this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u']);


        /**  TERTIARY  */
        $font=self::$tertiary_font['svgfile'];
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $tXML = $svgFont->textToPaths($this->tertiary, self::TERTIARY_FONT_SIZE,$extents);


        $view_port_height = $this->tabHeight
            + $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,
                $extents['u'])+$this->refPts/2 -2;

        $t_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u']);


        $total_width = (($p_width>$s_width?$p_width:$s_width)>$t_width?($p_width>$s_width?$p_width:$s_width):$t_width)
            + $this->tabWidth + $this->refPts + $this->refPts;


        $this->init($total_width,$view_port_height+$this->refPts/2,$view_port_height);

        $th =$view_port_height+5;

        $this->addXMLStr($this->xml, "<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
 width=\"$total_width\"  height=\"$view_port_height\"

             viewBox='-$this->xref -$this->trident_secondary_top $total_width $view_port_height'>
             $sXML</svg>");
        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
 width=\"$total_width\"  height=\"$view_port_height\"

                 viewBox='-$this->xref -58  $total_width $view_port_height'>$pXML</svg>");

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
 width=\"$total_width\"  height=\"$th\"
 viewBox='-$this->xref -$view_port_height  $total_width $th'
                 >$tXML</svg>");


    }

    public function signatureEight(){

       //rules
        if(!$this->required_if_allofThese(array($this->primary,$this->subprimary,$this->secondary,$this->tertiary)))
            return "";




        $svgFont = new SVGFont();

        /**  SECONDARY  */
        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $sXML = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $s_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u']);

        /**  PRIMARY  */
        $font=self::$primary_font['svgfile'];
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $pXML1 = $svgFont->textToPaths($this->primary, self::PRIMARY_FONT_SIZE,$extents);
        $p1_width =$this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u']);


        $font=self::$primary_font['svgfile'];
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $subprimary='';
        if($this->subprimary!=''){
            $subprimary = $this->subprimary;
        }else{
            $subprimary=$this->primary;

        }
        $pXML2 = $svgFont->textToPaths($subprimary, self::PRIMARY_FONT_SIZE,$extents);
        $p2_width =$this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u']);

        $p_width = $p1_width>$p2_width?$p1_width:$p2_width;
        $p2_height=$this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);

        /**  TERTIARY  */
        $font=self::$tertiary_font['svgfile'];
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $tXML = $svgFont->textToPaths($this->tertiary, self::TERTIARY_FONT_SIZE,$extents);


        $view_port_height = $this->tabHeight+$p2_height
            + $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,
                $extents['u'])+ $this->refPts+$this->refPts;

        $t_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u']);


        $total_width = (($p_width>$s_width?$p_width:$s_width)>$t_width?($p_width>$s_width?$p_width:$s_width):$t_width)
            + $this->tabWidth + $this->refPts;


        $this->init($total_width,$view_port_height+$this->refPts/2,$view_port_height);

        $th = $view_port_height+5;

        $this->addXMLStr($this->xml, "<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
             width=\"$total_width\"  height=\"$view_port_height\"
             viewBox='-$this->xref -$this->trident_secondary_top $total_width $view_port_height'>
             $sXML</svg>");
        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
                width=\"$total_width\"  height=\"$view_port_height\"
                 viewBox='-$this->xref -58  $total_width $view_port_height'>$pXML1</svg>");

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
                width=\"$total_width\"  height=\"$view_port_height\"
                 viewBox='-$this->xref -91  $total_width $view_port_height'>$pXML2</svg>");


        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio='xMinYMin'
 width=\"$total_width\"  height=\"$th\"
                 viewBox='-$this->xref -$view_port_height  $total_width $th'>$tXML</svg>");


    }


    /** Signature Nine is to give - primary, secondary and tertiary in the second line */
    public function signatureNine(){

        //rules
        if(!$this->required_if_allofThese(array($this->primary,$this->secondary,$this->tertiary,$this->subprimary)))return "";


        $svgFont = new SVGFont();

        /**  PRIMARY TEXT  */
        $font = self::$primary_font['svgfile'];
        $text = $this->primary;
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $result = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);


        $p_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u']) ;

        $pXML = $result;

        /**  SECONDARY TEXT  */
        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $sXML = $svgFont->textToPaths($text, self::SECONDARY_FONT_SIZE,$extents);
        $s_width = $this->funitsToPx($extents['w'],self::SECONDARY_FONT_SIZE,$extents['u']) ;

        $total_width= $p_width + $s_width + $this->tabWidth+$this->refPts + $this->refPts;

        $s_ref =  $this->xref+$p_width+$this->refPts;

        /** Tertiary Text */
        $font = self::$tertiary_font['svgfile'];
        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $tXML = $svgFont->textToPaths($this->tertiary, self::TERTIARY_FONT_SIZE,$extents);



        $this->init($total_width,$this->tabHeight);

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"
                 width=\"$total_width\"  height=\"$this->tabHeight\"
             viewBox='-$this->xref -$this->trident_primary_top  $total_width $this->tabHeight'>
             $pXML</svg>");

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"$total_width\"
            height=\"$this->tabHeight\"
            viewBox='-$s_ref -$this->trident_primary_top  $total_width $this->tabHeight'>$sXML</svg>");

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"
                 width=\"$total_width\"  height=\"$this->tabHeight\"
             viewBox='-$this->xref -58  $total_width $this->tabHeight'>
             $tXML</svg>");

    }
}


