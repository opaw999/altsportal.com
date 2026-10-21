<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\Login_model;

use App\Models\supplier\Supplier_model;
use App\Models\supplier\Message_model;
use App\Models\supplier\Dtr_model;
use App\Models\supplier\Contact_model;
use App\Models\supplier\Concession_model;

use App\Models\admins\Email_model;
use App\Models\admins\Setup_model;
use App\Models\admins\Violation_model;
use App\Models\admins\Masterfile_model;
use App\Models\admins\Portal_model;
use App\Models\admins\Import_model;
use App\Models\admins\Admin_model;

use App\Models\admins\Cms_model;

use \App\Libraries\Pusher;
use Mpdf\Mpdf;
use ZipArchive;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    protected $request;

    protected $supplier_model;
    protected $message_model;
    protected $dtr_model;
    protected $contact_model;
    protected $concession_model;

    protected $login_model;
    protected $email_model;
    protected $setup_model;
    protected $violation_model;
    protected $masterfile_model;
    protected $portal_model;
    protected $import_model;
    protected $admin_model;

    protected $cms_model;

    protected $pusher;
    protected $pdf;
    protected $zip;
    protected $timestamp;
    protected $today;
    protected $time;

    protected $helpers = ['url', 'server', 'filesystem'];


    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
        $this->supplier_model   = new Supplier_model();
        $this->message_model    = new Message_model();
        $this->dtr_model        = new Dtr_model();
        $this->contact_model    = new Contact_model();
        $this->concession_model = new Concession_model();


        $this->login_model      = new Login_model();
        $this->email_model      = new Email_model();
        $this->setup_model      = new Setup_model();
        $this->violation_model  = new Violation_model();
        $this->masterfile_model = new Masterfile_model();
        $this->portal_model     = new Portal_model();
        $this->import_model     = new Import_model();
        $this->admin_model      = new Admin_model();

        $this->cms_model      = new Cms_model();

        $this->pusher       = new Pusher();
        $this->pdf          = new Mpdf();
        $this->zip          = new ZipArchive();
        $this->timestamp    = date('Y-m-d H:i:s');
        $this->today        = date('Y-m-d');
        $this->time         = date('H:i:s');
    }
}
