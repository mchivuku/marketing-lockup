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
    protected $lookup = array('signatureOne',
        'signatureTwo',
        'signatureThree',
        'signatureFour',
        'signatureFive',
        'signatureSix','signatureSeven'
    );

    protected $primary_leading_x =  0.41;

    protected $secondary_leading_x = 0.35;
    protected $tertiary_leading_x = 0.3;

    const PRIMARY_FONT_SIZE   =   32.64;
    const SECONDARY_FONT_SIZE =   32.64;
    const TERTIARY_FONT_SIZE  =   19;


    protected $refPts = 10;
    protected $xref;


    static $primary_font = array('family'=> "'BentonSansCond-Bold'",'svgfile'=>'benton-sans-cond-bold');
    static $secondary_font = array('family'=> "'BentonSansCond-Regular'",'svgfile'=>'benton-sans-cond-regular');
    static $tertiary_font = array('family'=> "'BentonSansCond-Regular'",'svgfile'=>'benton-sans-cond-regular');


    function __construct($p,$s,$t,$v) {


        $split_sec = false;

        if(in_array($v,array(6,7)))$split_sec=true;

        parent::__construct($p,$s,$t,true,$split_sec);

        $key =$v-1;
        $func = $this->lookup[$key];

        $this->xref = 8;

        call_user_func(array($this,$func));
    }


    /**
     * Signature One - contains one element - Primary
     */
    public function signatureOne(){

        if(!$this->required(array($this->primary)))return "";


        $svgFont = new SVGFont();

        $font = self::$primary_font['svgfile'];
        $text = $this->primary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $pXML = $svgFont->textToPaths($text, self::PRIMARY_FONT_SIZE,$extents);
        $p_width = ($this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u'])) - $this->primary_leading_x;
        $p_height =  $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);

        $sXML="";$s_width=0;$s_height=0;
        if($this->subprimary!=''){
            $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
            $sXML = $svgFont->textToPaths($this->subprimary, self::PRIMARY_FONT_SIZE,$extents);
            $s_width =  $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u'])-$this->primary_leading_x;
            $s_height =  $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);
        }




        if($p_width>$s_width)
            $total_width = $p_width;
        else
            $total_width= $s_width;



        $p_x = ((($total_width/2)) - $p_width/2);
        $s_x = ((($total_width/2)) - $s_width/2);

        $trident_x = ($total_width/2) - ($this->tabWidth/2);


        $trident_y = 15;

        $total_height  = ($this->refPts + $this->refPts/2) + $this->tabHeight + ($this->refPts + $this->refPts/2) +
            $p_height+($this->refPts + $this->refPts/2)+$s_height+($this->refPts + $this->refPts/2);

        $p_y = $total_height - ($this->refPts + $this->refPts/2) - $s_height - $p_height + $this->refPts - 1 ;

        $s_y = $p_y + $p_height + $this->refPts + $this->refPts-4;

        $this->init($total_width+$this->refPts+5,$total_height,$this->tabHeight,
            $trident_x,
            $trident_y);

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$p_x -$p_y  $total_width $total_height'>$pXML</svg>");

        if($sXML!="")
            $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$s_x -$s_y  $total_width $total_height'>$sXML</svg>");


    }

    /**
     * Signature Two - contains one element - Primary, second line secondary
     */
    public function signatureTwo(){

        //rules
        if(!$this->required(array($this->primary,$this->secondary)))return "";

        /** @var PRIMARY $pXML2 */
        $pXML2='';
        $p2_width=0;
        $p2_height=0;

        $svgFont = new SVGFont();
        $font = self::$primary_font['svgfile'];

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $pXML1 = $svgFont->textToPaths($this->primary, self::PRIMARY_FONT_SIZE,$extents);
        $p1_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u'])-$this->primary_leading_x;
        $p1_height =  $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);

        if($this->subprimary!=''){
            $pXML2 = $svgFont->textToPaths($this->subprimary, self::PRIMARY_FONT_SIZE,$extents);
            $p2_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u'])-$this->primary_leading_x;
            $p2_height =  $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);

        }
        $p_width = $p1_width>$p2_width?$p1_width:$p2_width;

        //parallax
        $p_height = $pXML2!=""?$p1_height+$p2_height + 2*$this->refPts-4:$p1_height;

        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $sXML = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $s_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u'])- $this->secondary_leading_x;
        $s_height =  $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,$extents['u']);


        if($p_width>$s_width)
            $total_width = $p_width;
        else
            $total_width=$s_width;


        $p1_x = $total_width/2 - $p1_width/2;
        $p2_x = $total_width/2 - $p2_width/2;
        $s_x = $total_width/2 - $s_width/2;


        $trident_x = $total_width/2 - $this->tabWidth/2;
        $trident_y = 15;

        $total_height  = ($this->refPts + $this->refPts/2) + $this->tabHeight + ($this->refPts + $this->refPts/2) +
            $p_height+($this->refPts + $this->refPts/2)+$s_height+($this->refPts + $this->refPts/2);


        $p1_y = $total_height - ($this->refPts + $this->refPts/2) - $s_height - $p_height + $this->refPts - 1 ;
        $p2_y = $p1_y + $p2_height + $this->refPts + $this->refPts-4; // -4 parallax  - primary parallax

        $s_y = $p1_y + $p_height + $this->refPts - 4; // -4 parallax  - primary parallax

        $this->init($total_width+$this->refPts+5,$total_height,$this->tabHeight,
            $trident_x,
            $trident_y);


        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$p1_x -$p1_y  $total_width $total_height'>$pXML1</svg>");

        if($pXML2!=''){
            $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$p2_x -$p2_y  $total_width $total_height'>$pXML2</svg>");
        }

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$s_x -$s_y  $total_width $total_height'>$sXML</svg>");


    }



    /**
     * Signature Three - contains one element - Primary, second line secondary
     */
    public function signatureThree(){

        //rules
        if(!$this->required(array($this->primary,$this->secondary,$this->tertiary)))return "";


        //Primary
        $pXML2='';
        $p2_width=0;
        $p2_height=0;

        $svgFont = new SVGFont();
        $font = self::$primary_font['svgfile'];

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $pXML1 = $svgFont->textToPaths($this->primary, self::PRIMARY_FONT_SIZE,$extents);
        $p1_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u']) - $this->primary_leading_x;
        $p1_height =  $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);

        if($this->subprimary!=''){
            $pXML2 = $svgFont->textToPaths($this->subprimary, self::PRIMARY_FONT_SIZE,$extents);
            $p2_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u'])- $this->primary_leading_x;
            $p2_height =  $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);

        }
        $p_width = $p1_width>$p2_width?$p1_width:$p2_width;


        //parallax
        $p_height = $pXML2!=""?$p1_height+$p2_height + 2*$this->refPts-4:$p1_height;


        //secondary
        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $sXML = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $s_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u'])- $this->secondary_leading_x;
        $s_height =  $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,$extents['u']);


        //tertiary
        $font = self::$tertiary_font['svgfile'];
        $text = $this->tertiary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $tXML = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $t_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u'])- $this->tertiary_leading_x;
        $t_height =  $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,$extents['u']);

        if($p_width>$s_width)
            $total_width = $p_width;
        else
            $total_width=$s_width;

        if($total_width<$t_width)
            $total_width=$t_width;


        $p1_x = $total_width/2 - $p1_width/2;
        $p2_x = $total_width/2 - $p2_width/2;


        $s_x = $total_width/2 - $s_width/2;
        $t_x = $total_width/2 - $t_width/2;


        $trident_x = $total_width/2 - $this->tabWidth/2;
        $trident_y = 15;

        $total_height  = ($this->refPts + $this->refPts/2) + $this->tabHeight + ($this->refPts + $this->refPts/2) +
            $p_height+($this->refPts + $this->refPts/2)+$s_height+($this->refPts + $this->refPts/2)+$t_height+ ($this->refPts + $this->refPts/2);

        $p1_y = $total_height - (2*($this->refPts + $this->refPts/2)) - $s_height - $p_height  - 2 ;
        $p2_y = $p1_y + $p2_height + $this->refPts + $this->refPts-4; // -4 parallax  - primary parallax


        $s_y = $p1_y+$p_height + $this->refPts - 4; // -4 parallax  - primary parallax

        $t_y = $s_y+$s_height +$this->refPts +2; // -2 secondary parallax


        $this->init($total_width+$this->refPts+5,$total_height,$this->tabHeight,
            $trident_x,
            $trident_y);

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$p1_x -$p1_y  $total_width $total_height'>$pXML1</svg>");

        if($pXML2!='')
            $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$p2_x -$p2_y  $total_width $total_height'>$pXML2</svg>");


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
        if(!$this->required(array($this->primary,$this->secondary,$this->tertiary)))return "";

        //Primary
        $pXML2='';
        $p2_width=0;
        $p2_height=0;

        $svgFont = new SVGFont();
        $font = self::$primary_font['svgfile'];

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $pXML1 = $svgFont->textToPaths($this->primary, self::PRIMARY_FONT_SIZE,$extents);
        $p1_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u'])-$this->primary_leading_x;
        $p1_height =  $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);

        if($this->subprimary!=''){
            $pXML2 = $svgFont->textToPaths($this->subprimary, self::PRIMARY_FONT_SIZE,$extents);
            $p2_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u'])-$this->primary_leading_x;
            $p2_height =  $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);

        }
        $p_width = $p1_width>$p2_width?$p1_width:$p2_width;


        //parallax
        $p_height = $pXML2!=""?$p1_height+$p2_height + 2*$this->refPts-4:$p1_height;

        //secondary
        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $sXML = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $s_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u'])-$this->secondary_leading_x;
        $s_height =  $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,$extents['u']);


        //tertiary
        $font = self::$tertiary_font['svgfile'];
        $text = $this->tertiary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $tXML = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $t_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u'])-$this->tertiary_leading_x;
        $t_height =  $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,$extents['u']);


        if($p_width>$s_width)
            $total_width = $p_width;
        else
            $total_width=$s_width;

        if($total_width<$t_width)
            $total_width=$t_width;

        $p1_x = $total_width/2 - $p1_width/2;
        $p2_x = $total_width/2 - $p2_width/2;

        $s_x = $total_width/2 - $s_width/2;
        $t_x = $total_width/2 - $t_width/2;


        $trident_x = $total_width/2 - $this->tabWidth/2;
        $trident_y = 15;

        $total_height  = ($this->refPts + $this->refPts/2) + $this->tabHeight + ($this->refPts + $this->refPts/2) +
            $p_height+($this->refPts + $this->refPts/2)+$s_height+($this->refPts + $this->refPts/2)+$t_height+ ($this->refPts + $this->refPts/2);



        $s_y = $total_height - 2*($this->refPts + $this->refPts/2) - $s_height - $p_height - $t_height - 2;

        $p1_y = $s_y+$s_height + $this->refPts+15;
        $p2_y = $p1_y + $p2_height + $this->refPts + $this->refPts-4; // -4 parallax  - primary parallax
        $t_y = $p1_y+$p_height +$this->refPts - 4; // -4 secondary parallax


        $this->init($total_width+$this->refPts+5,$total_height,$this->tabHeight,
            $trident_x,
            $trident_y);


        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$s_x -$s_y  $total_width $total_height'>$sXML</svg>");

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$p1_x -$p1_y  $total_width $total_height'>$pXML1</svg>");

        if($pXML2!='')
            $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$p2_x -$p2_y  $total_width $total_height'>$pXML2</svg>");


        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$t_x -$t_y  $total_width $total_height'>$tXML</svg>");


    }

    /**
     * Signature Five - contains one element - Primary, second line secondary
     */
    public function signatureFive(){

        //rules
        if(!$this->required(array($this->primary,$this->secondary,$this->tertiary)))return "";

        //Primary
        $pXML2='';
        $p2_width=0;
        $p2_height=0;

        $svgFont = new SVGFont();
        $font = self::$primary_font['svgfile'];

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $pXML1 = $svgFont->textToPaths($this->primary, self::PRIMARY_FONT_SIZE,$extents);
        $p1_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u'])-$this->primary_leading_x;
        $p1_height =  $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);

        if($this->subprimary!=''){
            $pXML2 = $svgFont->textToPaths($this->subprimary, self::PRIMARY_FONT_SIZE,$extents);
            $p2_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u'])-$this->primary_leading_x;
            $p2_height =  $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);

        }

        $p_width = $p1_width>$p2_width?$p1_width:$p2_width;
        //parallax
        $p_height = $pXML2!=""?$p1_height+$p2_height + 2*$this->refPts-4:$p1_height;



        //secondary
        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $sXML = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $s_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u'])-$this->secondary_leading_x;
        $s_height =  $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,$extents['u']);


        //tertiary
        $font = self::$tertiary_font['svgfile'];
        $text = $this->tertiary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");


        if($p_width>$s_width)
            $total_width = $p_width;
        else
            $total_width=$s_width;


        $p1_x = $total_width/2 - $p1_width/2;
        $p2_x = $total_width/2 - $p2_width/2;

        $s_x = $total_width/2 - $s_width/2;


        $trident_x = $total_width/2 - $this->tabWidth/2;
        $trident_y = 15;

        $total_height  = ($this->refPts + $this->refPts/2) + $this->tabHeight + ($this->refPts + $this->refPts/2) +
            $p_height+($this->refPts + $this->refPts/2)+$s_height+($this->refPts + $this->refPts/2)+ ($this->refPts + $this->refPts/2);

        $s_y = $total_height - 2*($this->refPts + $this->refPts/2) - $s_height - $p_height  -2;
        $p1_y = $s_y+$s_height + $this->refPts+15;
        $p2_y = $p1_y + $p2_height + $this->refPts + $this->refPts-4; // -4 parallax  - primary parallax



        $this->init($total_width+$this->refPts+5,$total_height,$this->tabHeight,
            $trident_x,
            $trident_y);

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$s_x -$s_y  $total_width $total_height'>$sXML</svg>");

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$p1_x -$p1_y  $total_width $total_height'>$pXML1</svg>");

        if($pXML2!='')
            $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$p2_x -$p2_y  $total_width $total_height'>$pXML2</svg>");


    }


    /**
     * Signature Two - contains one element - Primary, second line secondary
     */
    public function signatureSix(){

        //rules
        if(!$this->required(array($this->primary,$this->secondary,$this->subsecondary)))return "";

        /** @var PRIMARY $pXML2 */
        $pXML2='';
        $p2_width=0;
        $p2_height=0;

        $svgFont = new SVGFont();
        $font = self::$primary_font['svgfile'];

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $pXML1 = $svgFont->textToPaths($this->primary, self::PRIMARY_FONT_SIZE,$extents);
        $p1_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u'])-$this->primary_leading_x;
        $p1_height =  $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);

        if($this->subprimary!=''){
            $pXML2 = $svgFont->textToPaths($this->subprimary, self::PRIMARY_FONT_SIZE,$extents);
            $p2_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u'])-$this->primary_leading_x;
            $p2_height =  $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);

        }
        $p_width = $p1_width>$p2_width?$p1_width:$p2_width;

        //parallax
        $p_height = $pXML2!=""?$p1_height+$p2_height + 2*$this->refPts-4:$p1_height;

        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $s1XML = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $s1_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u'])- $this->secondary_leading_x;
        $s1_height =  $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,$extents['u']);

        $s2XML = $svgFont->textToPaths($this->subsecondary, self::TERTIARY_FONT_SIZE,$extents);
        $s2_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u'])-$this->secondary_leading_x;
        $s2_height =  $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,$extents['u']);


        $s_width = $s1_width>=$s2_width?$s1_width:$s2_width;
        $s_height = $s1_height>=$s2_height?$s1_height:$s2_height;


        if($p_width>$s_width)
            $total_width = $p_width;
        else
            $total_width= $s_width;


        $p1_x = $total_width/2 - $p1_width/2;
        $p2_x = $total_width/2 - $p2_width/2;
        $s1_x = $total_width/2 - $s1_width/2;
        $s2_x = $total_width/2 - $s2_width/2;


        $trident_x = $total_width/2 - $this->tabWidth/2;
        $trident_y = 15;

        $total_height  = ($this->refPts + $this->refPts/2) + $this->tabHeight + ($this->refPts + $this->refPts/2) +
            $p_height+($this->refPts + $this->refPts/2)+$s_height+($this->refPts + $this->refPts/2)+($this->refPts);

        $p1_y = $total_height - $s_height - $p_height -  (($this->refPts)  + ($this->refPts/2))-1;
        $p2_y = $p1_y + $p2_height + $this->refPts + $this->refPts-4; // -4 parallax  - primary parallax

        $s1_y = $p1_y + $p_height + $this->refPts - 4; // -4 parallax  - primary parallax
        $s2_y = $s1_y + $s2_height + $this->refPts- 3;

        $sh = $total_height+$this->refPts+1;
        $this->init($total_width+$this->refPts+5,
            $sh,$this->tabHeight,
            $trident_x,
            $trident_y);



        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$p1_x -$p1_y  $total_width $total_height'>$pXML1</svg>");

        if($pXML2!=''){
            $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$p2_x -$p2_y  $total_width $total_height'>$pXML2</svg>");
        }

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$s1_x -$s1_y  $total_width $total_height'>$s1XML</svg>");


        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$sh\"
     viewBox='-$s2_x -$s2_y  $total_width $total_height'>$s2XML</svg>");

    }

    /**
     * Signature Two - contains one element - Primary, second line secondary
     */
    public function signatureSeven(){

        //rules
        if(!$this->required(array($this->primary,$this->secondary,$this->subsecondary)))return "";

        /** @var PRIMARY $pXML2 */
        $pXML2='';
        $p2_width=0;
        $p2_height=0;

        $svgFont = new SVGFont();
        $font = self::$primary_font['svgfile'];

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");

        $pXML1 = $svgFont->textToPaths($this->primary, self::PRIMARY_FONT_SIZE,$extents);
        $p1_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u'])-$this->primary_leading_x;
        $p1_height =  $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);

        if($this->subprimary!=''){
            $pXML2 = $svgFont->textToPaths($this->subprimary, self::PRIMARY_FONT_SIZE,$extents);
            $p2_width = $this->funitsToPx($extents['w'],self::PRIMARY_FONT_SIZE,$extents['u'])-$this->primary_leading_x;
            $p2_height =  $this->funitsToPx($extents['h'],self::PRIMARY_FONT_SIZE,$extents['u']);

        }
        $p_width = $p1_width>$p2_width?$p1_width:$p2_width;

        //parallax
        $p_height = $pXML2!=""?$p1_height+$p2_height + 2*$this->refPts-4:$p1_height;

        $font = self::$secondary_font['svgfile'];
        $text = $this->secondary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $s1XML = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $s1_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u'])- $this->secondary_leading_x;
        $s1_height =  $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,$extents['u']);

        $s2XML = $svgFont->textToPaths($this->subsecondary, self::TERTIARY_FONT_SIZE,$extents);
        $s2_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u'])-$this->secondary_leading_x;
        $s2_height =  $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,$extents['u']);


        //tertiary
        $font = self::$tertiary_font['svgfile'];
        $text = $this->tertiary;

        $svgFont->load("/ip/fonts/wwws/fonts/$font.svg");
        $tXML = $svgFont->textToPaths($text, self::TERTIARY_FONT_SIZE,$extents);
        $t_width = $this->funitsToPx($extents['w'],self::TERTIARY_FONT_SIZE,$extents['u'])- $this->tertiary_leading_x;
        $t_height =  $this->funitsToPx($extents['h'],self::TERTIARY_FONT_SIZE,$extents['u']);


        $s_width = $s1_width>=$s2_width?$s1_width:$s2_width;
        $s_height = $s1_height>=$s2_height?$s1_height:$s2_height;

        if($p_width>$s_width)
            $total_width = $p_width;
        else
            $total_width=$s_width;

        if($total_width<$t_width)
            $total_width=$t_width;



        $p1_x = $total_width/2 - $p1_width/2;
        $p2_x = $total_width/2 - $p2_width/2;
        $s1_x = $total_width/2 - $s1_width/2;
        $s2_x = $total_width/2 - $s2_width/2;
        $t_x = $total_width/2 - $t_width/2;

        $trident_x = $total_width/2 - $this->tabWidth/2;
        $trident_y = 15;

        $total_height  = ($this->refPts + $this->refPts/2) + $this->tabHeight + ($this->refPts + $this->refPts/2) +
            $p_height+($this->refPts + $this->refPts/2)+$s_height+($this->refPts + $this->refPts/2)+($this->refPts);

        $p1_y = $total_height - $s_height - $p_height -  (($this->refPts)  + ($this->refPts/2))-1;
        $p2_y = $p1_y + $p2_height + $this->refPts + $this->refPts-4; // -4 parallax  - primary parallax

        $s1_y = $p1_y + $p_height + $this->refPts - 4; // -4 parallax  - primary parallax
        $s2_y = $s1_y + $s2_height + $this->refPts- 3;

        $t_y = $s2_y + $t_height +$this->refPts+$this->refPts;

        $sh = $total_height+$this->refPts+1;
        $th = $t_y+$this->refPts+$this->refPts +$this->refPts + $this->refPts -2;

        $this->init($total_width+$this->refPts+5,
            $th,$this->tabHeight,
            $trident_x,
            $trident_y);

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$p1_x -$p1_y  $total_width $total_height'>$pXML1</svg>");

        if($pXML2!=''){
            $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$p2_x -$p2_y  $total_width $total_height'>$pXML2</svg>");
        }

        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$total_height\"
     viewBox='-$s1_x -$s1_y  $total_width $total_height'>$s1XML</svg>");


        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$sh\"
     viewBox='-$s2_x -$s2_y  $total_width $total_height'>$s2XML</svg>");


        $this->addXMLStr($this->xml,"<svg xmlns=\"http://www.w3.org/2000/svg\"  width=\"$total_width\"
     height=\"$th\"
     viewBox='-$t_x -$t_y  $total_width $th'>$tXML</svg>");

    }
}