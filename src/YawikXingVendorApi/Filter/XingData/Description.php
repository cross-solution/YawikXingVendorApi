<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2016 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace YawikXingVendorApi\Filter\XingData;

use Zend\Filter\FilterInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class Description implements FilterInterface
{
    /**
     *
     *
     * @param \YawikXingVendorApi\Filter\XingFilterData $value
     *
     * @return bool|string|array
     */
    public function filter($value)
    {
        $xingData = $value->getXingData();
        $logger = $value->getLogger();
        $jobLink = $xingData->getPostingUrl();

        if (!$jobLink) {
            $logger && $logger->warn('No posting url in the Xing data object. Cannot fetch description.');
            return 'Missing posting_url. Cannot fetch description.';
        }

        $return = true;

        /*
        * description (required)
        *
        * The description of the posting. Should be text only but allows html for certain orders.
        * MAX 10000 characters.
        */

        $logger && $logger->info('---> Fetch description from ' . $jobLink);
        $description = @file_get_contents($jobLink);

        $dom = new \DOMDocument("1.0", "UTF-8");
        libxml_use_internal_errors(true);
        $dom->loadHTML($description);

        // remove Javascript
        $xpath = new \DOMXPath($dom);
        $entries = $xpath->query('//script');

        foreach ( $entries as $node) {
            $node->parentNode->removeChild($node);
        }

        // remove inline styles
        $xpath = new \DOMXPath($dom);
        $entries = $xpath->query('//style');

        foreach ( $entries as $node) {
            $node->parentNode->removeChild($node);
        }

        $description = trim(html_entity_decode(strip_tags($dom->saveHtml(),'<h1><p><br><ul><li><ol>')));

        if (false === $description) {
            $data = $value->getData();
            $description = isset($data['description'])
                         ? $data['description']
                         : $value->getJob()->getTemplateValues()->getDescription();

            $logger && $logger->notice('----> No description recieved. Fall back to transmitted description.');
            $return = 'Fetching description failed. Used transmitted description.';
        }

        $xingData->setDescription($description);

        return $return;
    }


}