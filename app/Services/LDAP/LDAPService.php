<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 8/27/14
 * Time: 8:35 AM
 */

namespace App\Services;

require_once 'Attributes.php';

//TODO : move passwords;

class LDAPService
{

    protected $ldapconn;
    protected $domain='LDAP://ads.iu.edu/';
    protected $port=389;
    protected $user='xxxx';
    protected $password='xxxx';
    protected $error;
    protected $sizeLimit = 20;
    const  DefaultSearchRoot = "OU=Accounts,DC=ads,DC=iu,DC=edu" ;

    private function getSearchSubExpression($field_name,$search)
    {

        return sprintf("(%s=%s)", $field_name, $search);
    }


    public function __construct(){


       // $this->parseINI();

        $this->ldapconn   = ldap_connect($this->domain,$this->port);

        ldap_set_option($this->ldapconn, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($this->ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ldapconn, LDAP_OPT_SIZELIMIT, $this->sizeLimit);


        if (!$this->ldapconn) {
            $this->error = sprintf(
                'Unable to connect to LDAP server (Error: %s)',
                ldap_error($this->ds)
            );
            $this->errno = ldap_errno($this->ds);
            return false;
        }

    }

    public function parseINI()
    {
       $prefs =    parse_ini_file(app_path().'/config/ldap.php');
       foreach ($prefs as $k=>$v) $this->$k = $v;

    }

    public function search($search_fields){
        $search_filter_path = "";


        if(isset($search_fields['username']) && $search_fields['username']!=""){
            $search_filter_path.= $this->getSearchSubExpression('cn',$search_fields['username']);
        }

        if(isset($search_fields['firstName']) && $search_fields['firstName']!=""){
            $search_filter_path.= $this->getSearchSubExpression('givenName',sprintf("%s*",$search_fields['firstName']));
        }

        if(isset($search_fields['lastName']) && $search_fields['lastName']!=""){
            $search_filter_path.= $this->getSearchSubExpression('sn',sprintf("%s*",$search_fields['lastName']));
        }

        $searchPath = sprintf("(&(objectCategory=user)%s)", $search_filter_path);

        $search_results = array();
        if ($this->ldapconn){
            $link_id = ldap_bind($this->ldapconn, "ADS\\" . $this->user, $this->password);

            $cookie='';
            //bind successful
            if ($link_id) {

                     //size exceedde bug.
                    ldap_control_paged_result($this->ldapconn, $this->sizeLimit, true, $cookie);

                    $sr = ldap_search($this->ldapconn, self::DefaultSearchRoot, $searchPath);
                    $info = ldap_get_entries($this->ldapconn,$sr);

                    for ($i = 0; $i < $info["count"]; $i++) {


                        $network_id = $this->extract_value($info[$i],(string)new NetworkId());
                        $first_name = $this->extract_value($info[$i],(string)new FirstName());
                        $last_name = $this->extract_value($info[$i],(string)new LastName());
                        $email = ($network_id!="")?sprintf("%s@iu.edu",$network_id):"";
                        $search_results[]=array('username'=>$network_id,'firstName'=>$first_name,"lastName"=>$last_name,
                            'email'=>$email);

                    }




                return $search_results;



            }
        }

    }


    private function extract_value($entry,$string){
        return isset($entry[$string])?$entry[$string][0]:'';
    }


}