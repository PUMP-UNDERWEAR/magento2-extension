<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Controller\Adminhtml\Ebay\Listing\PickupStore;

class StoresStep extends \Ess\M2ePro\Controller\Adminhtml\Ebay\Listing\PickupStore
{
    //########################################

    public function execute()
    {
        $this->initListing();
        $this->setAjaxContent($this->createBlock('Ebay\Listing\PickupStore\Step\Stores\Wrapper'));
        return $this->getResult();
    }

    //########################################
}