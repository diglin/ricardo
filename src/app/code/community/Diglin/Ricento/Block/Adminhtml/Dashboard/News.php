<?php
class Diglin_Ricento_Block_Adminhtml_Dashboard_News extends Mage_Adminhtml_Block_Template
{
    const XML_PATH_NEWS_COUNT = 'ricento/rss/news_count';
    const XML_PATH_NEWS_FEED = 'ricento/rss/news_feed';

    public function _construct()
    {
        $this->setData('cache_lifetime', 86400);
        parent::_construct();
    }

    /**
     * @return Zend_Feed_Reader_EntryInterface[]
     * @throws Zend_Feed_Exception
     */
    public function getNewsFromFeed()
    {
        $newsEntries = array();

        $lang = Mage::helper('diglin_ricento')->getDefaultSupportedLang();
        $feedConfig = Mage::getStoreConfig(self::XML_PATH_NEWS_FEED . '_' .$lang);

        try {
            $count = Mage::getStoreConfig(self::XML_PATH_NEWS_COUNT);
            $feed = Zend_Feed_Reader::import($feedConfig);

            /* @var $feedItem Zend_Feed_Reader_Entry_Rss */
            foreach ($feed as $feedItem) {
                if ($count-- <= 0) {
                    break;
                }

                $feedUrl = parse_url($feedConfig);
                $baseUrl = $feedUrl['scheme'] . '://' . $feedUrl['host'];
                $description = str_replace('src="/', 'src="'. $baseUrl .'/', $feedItem->getDescription());

                $feedItem = new Varien_Object(array('title' => $feedItem->getTitle(), 'description' => $description, 'link' => $feedItem->getLink()));

                $newsEntries[] = $feedItem;
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
        return $newsEntries;
    }

    /**
     * Get cache key informative items
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return array(
            'BLOCK_TPL',
            Mage::app()->getStore()->getCode(),
            $this->getTemplateFile(),
            'template' => $this->getTemplate(),
            Mage::helper('diglin_ricento')->getDefaultSupportedLang()
        );
    }
}