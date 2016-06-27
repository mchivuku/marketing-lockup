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

    private function cleanString($string){

        $s =  str_replace(" ","_",strtolower($string));
        return preg_replace("/[^a-zA-Z0-9.]/", "", $s);

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

            // save path
            $path = $status->message;
            $this->save_source_svgs_and_convert($path."/lockups/");

            // Zip archive
            $z = new \ZipArchive();
            $parts =pathinfo($path);

            $outputZipPath  = config('app.downloadPath')."/".$parts['filename'].".zip";
            $z->open($outputZipPath, \ZIPARCHIVE::CREATE);

            //open source path
            if($handle= opendir($path)){

                while(false!==($entry=readdir($handle))){


                    if( $entry!="." && $entry!=".." && $entry!='errorlog.txt'){

                        // lockups dir
                        if(is_dir($path."/".$entry)){
                            $lockups_dir = opendir($path."/".$entry);
                            $z->addEmptyDir($entry);
                            while(false!==($x=readdir($lockups_dir))){
                                if( $x!="." && $x!=".." && $x!='errorlog.txt'){
                                    $file = $path."/".$entry."/".$x;
                                    $new_filename = substr($file,strrpos($file,'/') + 1);
                                    $z->addFile($file, $entry."/".$new_filename);
                                }
                            }
                            closedir($lockups_dir);
                        }else{

                            $file = $path."/".$entry;
                            $new_filename = substr($file,strrpos($file,'/') + 1);
                            $z->addFile($file,$new_filename);
                        }
                    }
                }

                closedir($handle);
            }

            $z->close();

            //remove directory -
            $this->delete_destination_folders($status->message);

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
        $count = 1;
        foreach($this->htags as $tag){
            //1.svg version -> web, 2. svg version for print;

            if(strlen($this->primary)>25){
                $primary = substr($this->primary, 0, 25);
                $filename = $this->cleanString($primary)."_lockup_".$count."_h";
            }else{
                $filename = $this->cleanString($this->primary)."_lockup_".$count."_h";
            }


            $name = $file_get_save($path,'App\Services\SVG\IUSVG',$filename,$tag);

            $status =  ($name!="")?$this->convert_webversion($name):
                new ProcessStatus(true);

            if($status->status===false){
                return 'Failed to convert';
            }
            $count++;

        }
        $count = 1;
        foreach($this->vtags as $tag){

            if(strlen($this->primary)>25){
                $primary = substr($this->primary, 0, 25);
                $filename = $this->cleanString($primary)."_lockup_".$count."_v";
            }else{
                $filename = $this->cleanString($this->primary)."_lockup_".$count."_v";
            }

            $name = $file_get_save($path,'App\Services\SVG\IUSVG_V',$filename,$tag);
            $status =  ($name!="")?$this->convert_webversion($name):new ProcessStatus(true);

            if($status->status===false){
                return 'Failed to convert';
            }
            $count++;
        }

        return new ProcessStatus(true,'Successfully completed the build');

    }

    /**
     * Create Lockup download folder
     * @param $pathname
     * @return bool
     */
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


        //Add Timestamp
        $save_to_path = config('app.downloadPath')."/".implode("_",array_filter(
                array($this->cleanString($this->primary),
                    $this->cleanString($this->secondary),
                    $this->cleanString($this->tertiary))
            ));

        $save_to_path.='_'.date('m-d-Y');


        if($this->create_folder($save_to_path))
        {
            //1. copy - pdf and readme txt file.
            $exec = new Exec();


            $docs =config('app.instructions_readme_docs');
            foreach($docs['files'] as $doc){

                $command = sprintf("cp %s %s",$docs['location'].$doc['src'],
                    $save_to_path."/".$doc['dest']);
                $exec->run($command);

            }

            // 2. create lockup folder
            $mklockupfolder = sprintf("mkdir %s",
                $save_to_path."/lockups");

            $exec->run($mklockupfolder);

            return new ProcessStatus(true,$save_to_path);

        }

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
        $command = new SVGCommandBuilder($name);
        $shell = new Exec();

        $info = pathinfo($name);
        $error_file_name = trim($info['dirname']."/"."errorlog".".txt");
        $shell->run($command->getSVGToPNGCommand());

		$this->createPDF($name);

        if($shell->getReturnValue()!=0){
            if(file_exists($error_file_name))
                $result .= (file_get_contents($error_file_name));
        }

        return $result!=""?new ProcessStatus(false,$result):new ProcessStatus(true);

    }

    public function createPdf($name){
        $svgName = $name;
        $pdfName = str_replace('.svg', '.pdf', $name);

        $cmd = '/ip/brandiu/bin/node/bin/node';
        $script = '/ip/brandiu/.jay/svgtopdf/svgtopdf.js';
        $inFile = $name;
        $outFile = str_replace('.svg', '.pdf', $name);

        $cmd = "$cmd $script $inFile $outFile\n";
        exec($cmd);
    }
	




}