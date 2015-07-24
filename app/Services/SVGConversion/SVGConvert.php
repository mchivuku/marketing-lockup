<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 7/23/15
 * Time: 11:48 AM
 */
namespace App\Services\SVGConversion;
require_once 'OperationStatus.php';
require_once "SVGCommandBuilder.php";
require_once "Exec.php";


/***
 * Class SVGConvert
 * Class the builds the imagick convert command
 */
class SVGConvert
{

    public function __construct($src){
        $this->source = $src;
    }


    public function convert(){
        $result = "";
        $shell = new Exec();

        $info = pathinfo($this->source);
        $error_file_name = "errorlog_".$info['filename'];

        $command = new SVGCommandBuilder($this->source);

        //run commands
        $shell->run($command->getSVGToEPSCommand());

        //failed
        if($shell->getReturnValue()!=0){
            $result.=
                file_exists($error_file_name)?implode("\n",file_get_contents($error_file_name)):
                    "Failed converting to eps format";

        }

         $shell_jpg = new Exec();

        $shell_jpg->run($command->getSVGToJPGCommand());

        if($shell_jpg->getReturnValue()!=0){
            $result.=
                file_exists($error_file_name)?implode("\n",file_get_contents($error_file_name)):
                    "Failed converting to jpg format";

        }


        //run commands
        $shell_png = new Exec();

        $shell_png->run($command->getSVGToPNGCommand());
        if($shell_png->getReturnValue()!=0){
            $result.=
                file_exists($error_file_name)?implode("\n",file_get_contents($error_file_name)):
                    "Failed converting to png format";

        }

        echo 'i am here';
        return $result!=""?new OperationStatus(false,$result):new OperationStatus(true);

    }


}