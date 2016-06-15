<?php
/**
 * Created by
 * User: IU Communications
 * Date: 6/14/16
 */


namespace  App\Jobs;
use App\Services\LDAPService as LDAPService;
use Illuminate\Bus\Queueable;

use App\Models as Models;

require_once app_path()."/Services/LDAP/LDAPService.php";

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

        foreach($usernames as $k=>$v){
            if($v == 'taramaso')continue; // person not present
            $name = sprintf("%s %s",$ldapService->getFirstName($v),
                   $ldapService->getLastName($v));

            $email =$ldapService->getEmail($v);

         /*   \Mail::send('emails.lockupNews',[],function($message)use($email,$name,$fd)
            {
                $message->to($email, $name)->subject('Marketing Lockup Update');
                echo 'Sent to - '.$name."<".$email.">".PHP_EOL;
                fputs($fd,$name."<".$email.">");
            });*/

        }

        fclose($fd);

    }


}
