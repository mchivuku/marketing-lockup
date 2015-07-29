<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 7/23/15
 * Time: 11:48 AM
 */
namespace App\Services\SVGConversion;


//Download Path
define('downloadPath',storage_path().'/downloads');
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


    public function __construct($p,$s,$t,$tags){

        $this->primary=$p;
        $this->secondary=$s;
        $this->tertiary=$t;
        $this->tags = $tags;

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
            $source_save_convert_status= $this->save_source_svgs_and_convert($path);

            //TODO check the return path
            $z = new \ZipArchive();
            $parts =pathinfo($path);

            $outputZipPath  = $path."/".$parts['filename'].".zip";
            $z->open($outputZipPath, \ZIPARCHIVE::CREATE);

            //open source path
            if($handle= opendir($path)){

                while(false!==($entry=readdir($handle))){
                     if($entry!="." && $entry!=".."){
                        $file = $path."/".$entry;
                        $new_filename = substr($file,strrpos($file,'/') + 1);
                        $z->addFile($file,$new_filename);

                    }
                }
                closedir($handle);

            }

            $z->close();

            return new ProcessStatus(true,$outputZipPath);

        }


        return $status->message;
    }


    protected function getDateFormat(){
        return date('m-d-Y');
    }

    function save_source_svgs_and_convert($path){
        $file_curl_get_put_contents = function($p,$s,$t,$attr,$path){

            $info = pathinfo($path);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://iet.communications.iu.edu/mercerjd/svg/s.php?p=' . urlencode
                ($p) .'&s=' .urlencode($s)  . '&t=' .
                urlencode($t) . '&v=' . $attr);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $contents = curl_exec($ch);
            curl_close($ch);

            $name = $path."/".$info['filename']."_".$attr."_".$this->getDateFormat().".svg";
            file_put_contents($name,$contents);
            return $name;

        };


        foreach($this->tags as $tag){
            $name = $file_curl_get_put_contents($this->primary,$this->secondary,$this->tertiary,$tag,$path);
            $status = $this->convert($name);
            if($status->status==false){
                return $status->message;
            }
        }

        //ZIp Archive and Save
        //http://php.net/manual/en/class.ziparchive.php
        return new ProcessStatus(true,'successfully completed the build');

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

    //

    public function create_destination_folders(){

        $clean_string =function($string){
            return str_replace(" ","_",strtolower($string));
        };
        //Add Timestamp
        $save_to_path = downloadPath."/".implode("_",array_filter(
                array($clean_string($this->primary),$clean_string($this->secondary),$clean_string($this->tertiary))
            ));

        if($this->create_folder($save_to_path));
        return new ProcessStatus(true,$save_to_path);


        return new ProcessStatus(false,"failed to create destination folder");


    }


    public function convert($src){
        $result = "";
        $shell = new Exec();

        $info = pathinfo($src);
        $error_file_name = trim($info['dirname']."/"."errorlog_".$this->getDateFormat().".txt");


        $command = new SVGCommandBuilder($src);

        //run commands
        $shell->run($command->getSVGToEPSCommand());

        //failed
        if($shell->getReturnValue()!=0){

            if(file_exists($error_file_name))
                $result .= (file_get_contents($error_file_name));

        }

        $shell_jpg = new Exec();

        $shell_jpg->run($command->getSVGToJPGCommand());

        if($shell_jpg->getReturnValue()!=0){
            $result.=file_get_contents($error_file_name);


        }


        //run commands
        $shell_png = new Exec();

        $shell_png->run($command->getSVGToPNGCommand());
        if($shell_png->getReturnValue()!=0){
            $result.=file_get_contents($error_file_name);


        }

        return $result!=""?new ProcessStatus(false,$result):new ProcessStatus(true);

    }


}