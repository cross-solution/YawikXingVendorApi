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
             [ 'skills','testValue' ],
             [ 'street','testValue' ], 
             [ 'studentClassification','testValue' ], 
             [ 'tellMeMore','__bool__' ],
             [ 'videoLink','testValue' ], 
             [ 'zipcode','testValue' ], 
             [ 'tags','testValue' ],
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

    public function testSetAndGetTags()
    {
        $target = new XingData();
        $target->setTags('chunk');
        $target->setTags('first');
        $this->assertEquals('first', $target->getTags());

        $target->setTags('second', 'append');
        $this->assertEquals('first,second', $target->getTags());

        $target->setTags(['third', 'fourth'], 'prepend');
        $this->assertEquals('third,fourth,first,second', $target->getTags());

    }

    public function testSetAndGetSalary()
    {
        $target = new XingData();

        $target->setSalary('nonumeric');
        $this->assertNull($target->getSalary());

        $target->setSalary('1,2');
        $this->assertSame((float) '1.2', $target->getSalary());

        $target->setSalary(3.4);
        $this->assertSame(3.4, $target->getSalary());
    }

    /**
     * @covers ::toArray()
     */
    public function testToArray()
    {
        $target = new XingData();


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
            'salary' => 1.2,
            'skills' => 'testValue',
            'street' => 'testValue',
            'student_classification' => 'testValue',
            'tell_me_more' => true,
            'video_link' => 'testValue',
            'zipcode' => 'testValue',

        ];

        foreach ($expected as $s => $v) { $s = str_replace('_', '', $s); $target->{"set$s"}($v); }

        $this->assertEquals($expected, $target->toArray());
    }

    /**
     * @covers ::toJson()
     */
    public function testToJson()
    {
        $target = new XingData();



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
            'salary' => 1.2,
            'skills' => 'testValue',
            'street' => 'testValue',
            'student_classification' => 'testValue',
            'tell_me_more' => true,
            'video_link' => 'testValue',
            'zipcode' => 'testValue',

        ];
        foreach ($expected as $s => $v) { $s = str_replace('_', '', $s); $target->{"set$s"}($v); }

        $this->assertEquals(json_encode($expected), $target->toJson());
    }
}
