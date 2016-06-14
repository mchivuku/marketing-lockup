<?php
/**
 * Created by
 * User: IU Communications
 * Date: 6/14/16
 */


namespace  App\Jobs;
use App\Services\LDAPService;
use Illuminate\Bus\Queueable;

use App\Models as Models;

class EmailJob
{


    /**
     * Send mass email
     * 1. read email ids from database - approved lockups
     * 2. Send email
     * 3. save information in csv file.
     */
    public function run(){

        // create a file to save information to whom locksup were sent
        $csv_filename = storage_path()."/app/lockup_emails_".date("Y-m-d",time()).".csv";
        $fd = fopen ($csv_filename, "w");

        $data = Models\Signature::where('statusId','=',2)->select('username')
            ->distinct('username')->get()->toArray();

        $usernames = array_flatten($data,function($item){
            return $item['username'];
        });


        $ldapService = new LDAPService();

        try{
            foreach($usernames as $k=>$v){
                $emails[] = ['name'=>sprintf("%s %s",$ldapService->getFirstName($v),$ldapService->getLastName($v)),
                    'email'=>$ldapService->getEmail($v)];
            }
        }catch(\Exception $ex){
            var_dump($ex->getMessage());
        }


        print_r($emails);
        fclose($fd);


    }


}
