<?php
/**
 * Rubedo -- ECM solution
 * Copyright (c) 2013, WebTales (http://www.webtales.fr/).
 * All rights reserved.
 * licensing@webtales.fr
 *
 * Open Source License
 * ------------------------------------------------------------------------------------------
 * Rubedo is licensed under the terms of the Open Source GPL 3.0 license. 
 *
 * @category   Rubedo
 * @package    Rubedo
 * @copyright  Copyright (c) 2012-2013 WebTales (http://www.webtales.fr)
 * @license    http://www.gnu.org/licenses/gpl.html Open Source GPL 3.0 license
 */
namespace Rubedo\Blocks\Controller;

use Rubedo\Services\Manager;
use Zend\Debug\Debug;
use Zend\View\Model\JsonModel;
use Zend\Json\Json;

/**
 *
 * @author dfanchon
 * @category Rubedo
 * @package Rubedo
 */
class CheckoutController extends AbstractController
{
    public function indexAction ()
    {
        $blockConfig = $this->params()->fromQuery('block-config', array());
        $output = $this->params()->fromQuery();
        if ((isset($blockConfig["signupContentId"]))&&(!empty($blockConfig["signupContentId"]))) {
            $introContent = Manager::getService('Contents')->findById($blockConfig["signupContentId"], true, false);
            if ($introContent){
                $output['introContentId'] = $blockConfig["signupContentId"];
                $output['introContentText'] = $introContent["fields"]["body"];
            }
        }
        $currentUser = Manager::getService("CurrentUser")->getCurrentUser();
        if (!$currentUser){
            $output['currentStep']=1;
        } else {
            $output['currentStep']=2;
        }


        $template = Manager::getService('FrontOfficeTemplates')->getFileThemePath("blocks/checkout.html.twig");
        $css = array(
            $this->getRequest()->getBasePath() . '/' . Manager::getService('FrontOfficeTemplates')->getFileThemePath("css/checkout.css")
        );
        $js = array(
            $this->getRequest()->getBasePath() . '/' . Manager::getService('FrontOfficeTemplates')->getFileThemePath("js/checkout.js")
        );
        return $this->_sendResponse($output, $template, $css, $js);
    }
}