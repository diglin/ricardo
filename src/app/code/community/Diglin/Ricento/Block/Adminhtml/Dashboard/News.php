<?php
class Diglin_Ricento_Block_Adminhtml_Dashboard_News extends Mage_Adminhtml_Block_Template
{
    const XML_PATH_NEWS_COUNT = 'ricento/rss/news_count';
    const XML_PATH_NEWS_FEED = 'ricento/rss/news_feed';

    /**
     * @return Zend_Feed_Reader_EntryInterface[]
     * @throws Zend_Feed_Exception
     */
    public function getNewsFromFeed()
    {
        $newsEntries = array();
        try {
            $count = Mage::getStoreConfig(self::XML_PATH_NEWS_COUNT);
            $feed = Zend_Feed_Reader::import(Mage::getStoreConfig(self::XML_PATH_NEWS_FEED));
            foreach ($feed as $feedItem) {
                if ($count-- <= 0) {
                    break;
                }
                $newsEntries[] = $feedItem;
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
        return $newsEntries;
    }
}