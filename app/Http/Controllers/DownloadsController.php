<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 5/15/16
 * Time: 7:44 AM
 */

namespace App\Http\Controllers;


use App\Models as Models;
use App\Models\ViewModels as ViewModels;
use App\Services\SVG as SVG;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Downloads Controller
 * @package App\Http\Controllers
 */
class DownloadsController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        parent::__construct();

        // check if the user is an administrator;
        $admin = Models\AppAdmin::where('username', '=', $this->currentUser)->first();

        // TODO: exception handling with Laravel
        if (!isset($admin)) {
            $url = $_ENV['HOME_PATH'] . "/error/401.html";
            header('Location:' . $url);
        }

    }


    /**
     * Function to construct index page
     * @return mixed
     */
    public function index()
    {

        /** @var get approved signatures $signatures */
        $signatures =
            Models\Signature::with('signaturereviews',
                'reviewstatus')
                ->where('statusId', '=', 2)
                ->orderBy('updated_at',
                    'desc')->get();


        $table = new \StdClass;
        $table->header = array('Preview','Lockup-text', 'Name','Download');
        $table->attributes = array('class'=>'table mobile-labels',
            'id'=>'downloadsTable');

        collect($signatures)->each(function ($item)use(&$table) {

            $preview_link = "<a  data-reveal-id=\"viewModal\"  href='" . \URL::to("getPreview") . "?id=" . $item->signatureid . "'
     class=\"modal tiptext\" onMouseOver=\"showThumbnail((this)); return true;\"
     onMouseOut=\"hideThumbnail((this)); return true;\">Preview<div  class='thumbnail'   data-attribute-id='" . $item->signatureid . "'></div></a>";
            $name =
                isset($item->fullName)?$item->fullName:$item->username;

            // this is to allow searching on primary,secondary,tertiary text
            $text = "<span class=\"hideText\">";
            if ($item->primaryText != "")
                $text .= trim($item->primaryText);
            if ($item->secondaryText != "")
                $text .= trim($item->secondaryText);
            if ($item->tertiaryText != "")
                $text .= trim($item->tertiaryText);
            $text .= "</span>";


            $download_link = "<a href='" . \URL::to("download")."?signatureid=". $item->signatureid . "'>Download</a>" . $text;

            $table->data[] = array($preview_link,
                join(",",array($item->primaryText,$item->secondaryText,
                    $item->tertiaryText)).($item->named==1?" <span class='current'>(named school)</span> ":""),
                $name,
                $download_link,
            );
        });

        return $this->view('admin.downloadsTable')
            ->model($table)
            ->pagePath('/tools/marketing-lockup/manage')
            ->sectionPath('/tools')
            ->title('All Approved Lockups - for download');

    }
}