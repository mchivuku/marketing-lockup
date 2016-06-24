<?php

require_once 'iet/XSLT.php';

$xmlFile = $_ENV['XML_PATH'].'navigation-xml.xml';
$xslFile = $_ENV['PHP_PATH'].'nav-section.xsl.txt';


$params = array('pagePath'=>$pagePath,
                'sectionPath'=>$sectionPath,'rooturl'=>$_ENV['HOME_PATH']);

$html = XSLT::FromFile($xmlFile, $xslFile, $params);

if(isset($_GET['callback'])) {
    $json = json_encode($html);
    $html = "{$_GET['callback']}($json)";
}
echo $html;

?>