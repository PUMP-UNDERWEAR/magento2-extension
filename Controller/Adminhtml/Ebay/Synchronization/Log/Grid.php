<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Controller\Adminhtml\Ebay\Synchronization\Log;

class Grid extends \Ess\M2ePro\Controller\Adminhtml\Ebay\Settings
{
    //########################################

    public function execute()
    {
        $response = $this->createBlock('Ebay\Synchronization\Log\Grid')->toHtml();
        $this->setAjaxContent($response);

        return $this->getResult();
    }

    //########################################
}