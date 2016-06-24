<?php
    require_once 'iet/XSLT.php';

    $xmlFile = $_ENV['XML_PATH'].'navigation-xml.xml';
    $xslFile = $_ENV['PHP_PATH'].'nav-mobile.xsl.txt';



    $params = array('pagePath'=>$pagePath,'rooturl'=>$_ENV['HOME_PATH']);


    if($admin){
        $params['lockupAdmin']="true";
    }


    $html = XSLT::FromFile($xmlFile, $xslFile, $params);
    if(isset($_GET['callback'])) {
        $json = json_encode($html);
        $html = "{$_GET['callback']}($json)";
    }
    echo $html;
?>


