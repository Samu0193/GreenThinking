<?php

namespace App\Controllers;

use Mpdf\Mpdf;
use App\Utils\ResponseUtil;
use CodeIgniter\Controller;
use App\Utils\PHPMailerService;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Psr\Log\LoggerInterface;

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
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * Instance of the main ResponseUtil object.
     *
     * @var ResponseUtil
     */
    protected $responseUtil;

    /**
     * Instance of the main ResponseUtil object.
     *
     * @var PHPMailer
     */
    protected $phpMailer;

    /**
     * Instance of the main ResponseUtil object.
     *
     * @var Mpdf
     */
    protected $mpdf;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        $this->session      = \Config\Services::session();
        $this->responseUtil = new ResponseUtil();
        $this->phpMailer    = new PHPMailerService();
        $this->mpdf         = new Mpdf();
    }
}
