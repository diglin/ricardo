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

            /* @var $feedItem Zend_Feed_Reader_Entry_Rss */
            foreach ($feed as $feedItem) {
                if ($count-- <= 0) {
                    break;
                }

                $feedUrl = parse_url(Mage::getStoreConfig(self::XML_PATH_NEWS_FEED));
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
}