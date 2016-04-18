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
use Zend\Filter\StripTags;
use YawikXingVendorApi\Filter\XingFilterData;

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
        $description = $xingData->getDescription();

        if (!$jobLink && !$description) {
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

        if (!$description) {
            $logger && $logger->info('---> Fetch description from ' . $jobLink);
            $description = @file_get_contents($jobLink);
        }

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

        $stripTags = new StripTags();
        $stripTags->setTagsAllowed(['h1','p','br','li','ol','ul']);
        $stripTags->setAttributesAllowed(array());
        $description = trim(html_entity_decode($stripTags($dom->saveHTML())));

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