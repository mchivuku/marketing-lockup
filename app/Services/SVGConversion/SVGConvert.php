<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 7/23/15
 * Time: 11:48 AM
 */
namespace App\Services\SVGConversion;



//Download Path
use App\Services\SVG;

require_once __DIR__."/../SVG/IUSVG.php";
require_once __DIR__."/../SVG/IUSVG_PRINT.php";


define('downloadPath',"/ip/iubrand/wwws/admin/public/downloads");
/***
 * Class SVGConvert
 * Class the builds the imagick convert command
 */
class SVGConvert
{
    const DIRECTORY_SEPARATOR="/";
    protected $mode=0777;

    protected $primary;
    protected $secondary;
    protected $tertiary;
    protected $htags,$vtags;


    public function __construct($p,$s,$t,$htags,$vtags){

        $this->primary=$p;
        $this->secondary=$s;
        $this->tertiary=$t;
        $this->htags = $htags;
        $this->vtags = $vtags;



    }

    /**
     * Build performs
     * 1. create destination folder
     * 2. get curl source files/save and convert to eps,jpg, and png format
     * 3. zip the folder and return;
     * @return string
     */
    public function build(){
        $status = $this->create_destination_folders();

        if($status->status){

            // save the source to the path
            $path = $status->message;
            $this->save_source_svgs_and_convert($path);

            $z = new \ZipArchive();
            $parts =pathinfo($path);

            $outputZipPath  = downloadPath."/".$parts['filename'].".zip";
            $z->open($outputZipPath, \ZIPARCHIVE::CREATE);

            //open source path
            if($handle= opendir($path)){

                while(false!==($entry=readdir($handle))){

                    if($entry!="." && $entry!=".." && $entry!='errorlog.txt'
                        //&&
                       // !preg_match("/^(svg_print_([0-9]+).svg)$/i",$entry)
                    ){

                        $file = $path."/".$entry;
                        $new_filename = substr($file,strrpos($file,'/') + 1);
                        $z->addFile($file,$new_filename);

                    }
                }
                closedir($handle);

            }

            $z->close();

            //remove directory -
             $this->delete_destination_folders($status->message);


            //remove the dir
            return $outputZipPath;

        }


        return null;
    }


    protected function getDateFormat(){
        return date('m-d-Y');
    }

    /** Function to save svgs and convert */
    function save_source_svgs_and_convert($path){

        $CI = $this;
        // create svg fi le and run convert command
        $file_get_save = function($path,$classname,$filename,$tag)use($CI){
            $contents = (new $classname($CI->primary,$CI->secondary,$CI->tertiary,$tag));
            $name = $path."/".$filename.".svg";
            if($contents!="")
            {
                file_put_contents($name,$contents);
                return $name;
            }

            //Empty SVG
            return "";
        };

        // Horizontal
        foreach($this->htags as $tag){
            //1.svg version -> web, 2. svg version for print;
            $name = $file_get_save($path,'App\Services\SVG\IUSVG','svg_'.$tag,$tag);
            $status =  ($name!="")?$this->convert_webversion($name):new ProcessStatus(true);

            if($status->status===false){
                return 'Failed to convert';
            }

            $name = $file_get_save($path,'App\Services\SVG\IUSVG_PRINT','svg_print_'.$tag,$tag);
            // no need to generate print versions
            //$status =  ($name!="")?$this->convert_printversion($name):new ProcessStatus(true);

        }

        foreach($this->vtags as $tag){

            //1.svg version -> web, 2. svg version for print;
            $name = $file_get_save($path,'App\Services\SVG\IUSVG_V','svg_v_'.$tag,$tag);
            $status =  ($name!="")?$this->convert_webversion($name):new ProcessStatus(true);


            if($status->status===false){
                return 'Failed to convert';
            }

            $name = $file_get_save($path,'App\Services\SVG\IUSVG_V_PRINT','svg_v_print_'.$tag,$tag);
           // $status =  ($name!="")?$this->convert_printversion($name):new ProcessStatus(true);
        }

        return new ProcessStatus(true,'Successfully completed the build');

    }


    //help from php.net
    private function create_folder($pathname){

        //check with directory path exists
        if (is_dir($pathname) || empty($pathname)) {
            return true;
        }

        // rnsure a file does not already exist with the same name
        $pathname = str_replace(array('/', ''), DIRECTORY_SEPARATOR, $pathname);
        if (is_file($pathname)) {
            return false;
        }

        // Crawl up the directory tree
        $next_pathname = substr($pathname, 0, strrpos($pathname, DIRECTORY_SEPARATOR));
        if ($this->create_folder($next_pathname, $this->mode)) {
            if (!file_exists($pathname)) {
                return mkdir($pathname, $this->mode);
            }
        }

    }

    public function create_destination_folders(){

        $clean_string =function($string){
            return str_replace(" ","_",strtolower($string));
        };
        //Add Timestamp

        $save_to_path = downloadPath."/".implode("_",array_filter(
                array($clean_string($this->primary),$clean_string($this->secondary),$clean_string($this->tertiary))
            ));

        if($this->create_folder($save_to_path))
           return new ProcessStatus(true,$save_to_path);


        return new ProcessStatus(false,"failed to create destination folder");

    }


    public function delete_destination_folders($name){

        // Removes non-empty directory
        $command = "rm -rf $name/";
       exec($command);

    }


    /**
     *  Convert webversion -> jpg - low resolution, jpg - high resolution
     */
    public function convert_webversion($name){

        $result = "";
        $shell = new Exec();


        $info = pathinfo($name);
        $error_file_name = trim($info['dirname']."/"."errorlog".".txt");


        $command = new SVGCommandBuilder($name);
        $shell->run($command->getSVGToJPGHighResolutionCommand());

        //failed
        if($shell->getReturnValue()!=0){
            if(file_exists($error_file_name))
                $result .= (file_get_contents($error_file_name));

        }

        // No Resolution - JPG - remove
       // $shell_jpg = new Exec();
      //  $shell_jpg->run($command->getSVGToJPGLowResolutionCommand());

     //   if($shell_jpg->getReturnValue()!=0){
      //      $result.=file_get_contents($error_file_name);
      //  }

        $shell_png = new Exec();
        $shell_png->run($command->getSVGToPNGCommand());


        if($shell_png->getReturnValue()!=0){
            if(file_exists($error_file_name))
                $result .= (file_get_contents($error_file_name));
        }

        return $result!=""?new ProcessStatus(false,$result):new ProcessStatus(true);

    }


    /***
     * Convert print version -> png
     * @param $name
     */
    public function convert_printversion($name){
        $result = "";
        $shell = new Exec();


        $info = pathinfo($name);
        $error_file_name = trim($info['dirname']."/"."errorlog".".txt");

        $command = new SVGCommandBuilder($name);
        $shell->run($command->getSVGToPNGCommand());


        if($shell->getReturnValue()!=0){
            if(file_exists($error_file_name))
                $result .= (file_get_contents($error_file_name));
        }

        return $result!=""?new ProcessStatus(false,$result):new ProcessStatus(true);

    }


}