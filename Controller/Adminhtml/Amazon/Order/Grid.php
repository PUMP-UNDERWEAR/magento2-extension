<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Controller\Adminhtml\Amazon\Order;

use Ess\M2ePro\Controller\Adminhtml\Amazon\Order;

class Grid extends Order
{
    public function execute()
    {
        $this->setAjaxContent($this->createBlock('Amazon\Order\Grid'));

        return $this->getResult();
    }
}