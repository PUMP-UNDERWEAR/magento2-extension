<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  2011-2015 ESS-UA [M2E Pro]
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Model\Magento\Product;

class StockItem extends \Ess\M2ePro\Model\AbstractModel
{
    private $stockConfiguration;

    /** @var \Magento\CatalogInventory\Model\Stock\Item */
    private $stockItem = null;

    /** @var \Magento\CatalogInventory\Model\Indexer\Stock\Processor */
    private $indexStockProcessor = null;

    /** @var \Magento\CatalogInventory\Model\Spi\StockStateProviderInterface */
    private $stockStateProvider;

    /** @var bool */
    private $stockStatusChanged = false;

    //########################################

    public function __construct(
        \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration,
        \Ess\M2ePro\Helper\Factory $helperFactory,
        \Ess\M2ePro\Model\Factory $modelFactory,
        \Magento\CatalogInventory\Model\Indexer\Stock\Processor $indexStockProcessor,
        \Magento\CatalogInventory\Model\Spi\StockStateProviderInterface $stockStateProvider,
        \Magento\CatalogInventory\Model\Stock\Item $stockItem
    ){
        $this->stockConfiguration  = $stockConfiguration;
        $this->indexStockProcessor = $indexStockProcessor;
        $this->stockStateProvider  = $stockStateProvider;
        $this->stockItem           = $stockItem;

        parent::__construct($helperFactory, $modelFactory);
    }

    //########################################

    /**
     * @return \Magento\CatalogInventory\Model\Stock\Item
     * @throws \Ess\M2ePro\Model\Exception\Logic
     */
    public function getStockItem()
    {
        if (is_null($this->stockItem)) {
            throw new \Ess\M2ePro\Model\Exception\Logic('Stock Item is not set.');
        }

        return $this->stockItem;
    }

    public function subtractQty($qty, $save = true)
    {
        $stockItem = $this->getStockItem();

        if ($stockItem->getQty() - $stockItem->getMinQty() - $qty < 0) {
            switch ($stockItem->getBackorders()) {
                case \Magento\CatalogInventory\Model\Stock::BACKORDERS_YES_NONOTIFY:
                case \Magento\CatalogInventory\Model\Stock::BACKORDERS_YES_NOTIFY:
                    break;
                default:
                    throw new \Ess\M2ePro\Model\Exception('The requested Quantity is not available.');
            }
        }

        if ($stockItem->getManageStock() && $this->stockConfiguration->canSubtractQty()) {
            $stockItem->setQty($stockItem->getQty() - $qty);
        }

        if ($stockItem->getManageStock() && !$this->stockStateProvider->verifyStock($stockItem)) {
            $this->stockStatusChanged = true;
        }

        if ($save) {
            $stockItem->save();
            $this->afterSave();
        }
    }

    public function addQty($qty, $save = true)
    {
        $stockItem = $this->getStockItem();
        $stockItem->setQty($stockItem->getQty() + $qty);

        if ($stockItem->getQty() > $stockItem->getMinQty()) {
            $stockItem->setIsInStock(true);
            $this->stockStatusChanged = true;
        }

        if ($save) {
            $stockItem->save();
            $this->afterSave();
        }
    }

    //########################################

    public function afterSave()
    {
        if ($this->indexStockProcessor->isIndexerScheduled()) {

            $this->indexStockProcessor->reindexRow($this->stockItem->getProductId(), true);
        }
    }

    //----------------------------------------

    public function isStockStatusChanged()
    {
        return (bool)$this->stockStatusChanged;
    }

    //########################################
}