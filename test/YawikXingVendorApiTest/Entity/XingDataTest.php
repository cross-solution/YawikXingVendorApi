<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2016 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace YawikXingVendorApiTest\Entity;

use YawikXingVendorApi\Entity\XingData;

/**
 * Tests for \YawikXingVendorApi\Entity\XingData
 * 
 * @covers \YawikXingVendorApi\Entity\XingData
 * @coversDefaultClass \YawikXingVendorApi\Entity\XingData
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @group YawikXingVendorApi
 * @group YawikXingVendorApi.Entity
 */
class XingDataTest extends \PHPUnit_Framework_TestCase
{
    
    public function provideSetterAndGetterTestData()
    {
        return [
             [ 'city','testValue' ], 
             [ 'companyName','testValue' ], 
             [ 'companyProfileUrl','testValue' ], 
             [ 'country','testValue' ], 
             [ 'description','testValue' ], 
             [ 'disciplineId','testValue' ], 
             [ 'function','testValue' ], 
             [ 'industryId','testValue' ], 
             [ 'jobType','testValue' ], 
             [ 'language','testValue' ], 
             [ 'level','testValue' ], 
             [ 'orderId','testValue' ], 
             [ 'replySetting','testValue' ],
             [ 'organizationId','testValue' ], 
             [ 'pointOfContactType','testValue' ], 
             [ 'tags','testValue' ], 
             [ 'userRole','testValue' ], 
             [ 'ba','__bool__' ],
             [ 'createStoryOnActivation','__bool__' ],
             [ 'currency','testValue' ], 
             [ 'jobCode','testValue' ], 
             [ 'posterUrl','testValue' ], 
             [ 'postingLogoContent','testValue' ], 
             [ 'postingPdfContent','testValue' ], 
             [ 'postingUrl','testValue' ], 
             [ 'publishToCompany','__bool__' ],
             [ 'region','testValue' ], 
             [ 'replyEmail','testValue' ], 
             [ 'replyUrl','testValue' ], 
             [ 'salary','testValue' ], 
             [ 'skills','testValue' ], 
             [ 'street','testValue' ], 
             [ 'studentClassification','testValue' ], 
             [ 'tellMeMore','__bool__' ],
             [ 'videoLink','testValue' ], 
             [ 'zipcode','testValue' ], 
             [ 'keywords','testValue' ], 

        ];
    }

    /**
     * @dataProvider provideSetterAndGetterTestData
     *
     * @param string $method
     * @param string $value
     */
    public function testSetterAndGetter($method, $value)
    {
        $target = new XingData();
        $setter = "set" . $method;
        $getter = "get" . $method;

        if ('__bool__' == $value) {
            $this->assertSame($target, $target->$setter(false), 'Fluent interface broken.');
            $this->assertFalse($target->$getter(), 'Setting FALSE failed.');
            $target->$setter(true);
            $this->assertTrue($target->$getter(), 'Setting TRUE failed.');
            $target->$setter('mustBeTrue');
            $this->assertTrue($target->$getter(), 'Setting implicit TRUE failed.');
            $target->$setter('');
            $this->assertFalse($target->$getter(), 'Setting implicit FALSE failed.');
        } else {
            $this->assertSame($target, $target->$setter($value), 'Fluent interface broken.');
            $this->assertEquals($value, $target->$getter());
        }
    }

    /**
     * @covers ::toArray()
     */
    public function testToArray()
    {
        $target = new XingData();

        foreach ($this->provideSetterAndGetterTestData() as $spec) {
            $setter = "set" . $spec[0];
            if ('__bool__' == $spec[1]) {
                $target->$setter(true);
            } else {
                $target->$setter($spec[1]);
            }
        }

        $expected = [
            'city' => 'testValue',
            'company_name' => 'testValue',
            'company_profile_url' => 'testValue',
            'country' => 'testValue',
            'description' => 'testValue',
            'discipline_id' => 'testValue',
            'function' => 'testValue',
            'industry_id' => 'testValue',
            'job_type' => 'testValue',
            'language' => 'testValue',
            'level' => 'testValue',
            'order_id' => 'testValue',
            'reply_setting' => 'testValue',
            'organization_id' => 'testValue',
            'point_of_contact_type' => 'testValue',
            'tags' => 'testValue',
            'user_role' => 'testValue',
            'ba' => true,
            'create_story_on_activation' => true,
            'currency' => 'testValue',
            'job_code' => 'testValue',
            'poster_url' => 'testValue',
            'posting_logo_content' => 'testValue',
            'posting_pdf_content' => 'testValue',
            'posting_url' => 'testValue',
            'publish_to_company' => true,
            'region' => 'testValue',
            'reply_email' => 'testValue',
            'reply_url' => 'testValue',
            'salary' => 'testValue',
            'skills' => 'testValue',
            'street' => 'testValue',
            'student_classification' => 'testValue',
            'tell_me_more' => true,
            'video_link' => 'testValue',
            'zipcode' => 'testValue',
            'keywords' => 'testValue',
        ];

        $this->assertEquals($expected, $target->toArray());
    }

    /**
     * @covers ::toJson()
     */
    public function testToJson()
    {
        $target = new XingData();

        foreach ($this->provideSetterAndGetterTestData() as $spec) {
            $setter = "set" . $spec[0];
            if ('__bool__' == $spec[1]) {
                $target->$setter(true);
            } else {
                $target->$setter($spec[1]);
            }
        }

        $expected = json_encode([
            'city' => 'testValue',
            'company_name' => 'testValue',
            'company_profile_url' => 'testValue',
            'country' => 'testValue',
            'description' => 'testValue',
            'discipline_id' => 'testValue',
            'function' => 'testValue',
            'industry_id' => 'testValue',
            'job_type' => 'testValue',
            'language' => 'testValue',
            'level' => 'testValue',
            'order_id' => 'testValue',
            'reply_setting' => 'testValue',
            'organization_id' => 'testValue',
            'point_of_contact_type' => 'testValue',
            'tags' => 'testValue',
            'user_role' => 'testValue',
            'ba' => true,
            'create_story_on_activation' => true,
            'currency' => 'testValue',
            'job_code' => 'testValue',
            'poster_url' => 'testValue',
            'posting_logo_content' => 'testValue',
            'posting_pdf_content' => 'testValue',
            'posting_url' => 'testValue',
            'publish_to_company' => true,
            'region' => 'testValue',
            'reply_email' => 'testValue',
            'reply_url' => 'testValue',
            'salary' => 'testValue',
            'skills' => 'testValue',
            'street' => 'testValue',
            'student_classification' => 'testValue',
            'tell_me_more' => true,
            'video_link' => 'testValue',
            'zipcode' => 'testValue',
            'keywords' => 'testValue',
        ]);

        $this->assertEquals($expected, $target->toJson());
    }
}