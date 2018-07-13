<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Controller\Adminhtml\Ebay\Listing\Other;

class ViewGrid extends \Ess\M2ePro\Controller\Adminhtml\Ebay\Listing\Other
{
    public function execute()
    {
        $block = $this->getLayout()->createBlock(
            'Ess\M2ePro\Block\Adminhtml\Ebay\Listing\Other\View\Grid'
        );

        $this->setAjaxContent($block);
        return $this->getResult();
    }
}