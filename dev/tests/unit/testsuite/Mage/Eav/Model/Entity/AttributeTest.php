<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Mage_Eav
 * @subpackage  unit_tests
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Eav_Model_Entity_AttributeTest extends PHPUnit_Framework_TestCase
{
    /**
     * Attribute model to be tested
     * @var Mage_Eav_Model_Entity_Attribute|PHPUnit_Framework_MockObject_MockObject
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = $this->getMock('Mage_Eav_Model_Entity_Attribute', null, array(), '', false);
    }

    protected function tearDown()
    {
        $this->_model = null;
    }

    /**
     * @param string $givenFrontendInput
     * @param string $expectedBackendType
     * @dataProvider dataGetBackendTypeByInput
     */
    public function testGetBackendTypeByInput($givenFrontendInput, $expectedBackendType)
    {
        $this->assertEquals($expectedBackendType, $this->_model->getBackendTypeByInput($givenFrontendInput));
    }

    public static function dataGetBackendTypeByInput()
    {
        return array(
            array('unrecognized-frontend-input', null),
            array('text', 'varchar'),
            array('gallery', 'varchar'),
            array('media_image', 'varchar'),
            array('multiselect', 'varchar'),
            array('image', 'text'),
            array('textarea', 'text'),
            array('date', 'datetime'),
            array('select', 'int'),
            array('boolean', 'int'),
            array('price', 'decimal'),
            array('weight', 'decimal'),
        );
    }

    /**
     * @param string $givenFrontendInput
     * @param string $expectedDefaultValue
     * @dataProvider dataGetDefaultValueByInput
     */
    public function testGetDefaultValueByInput($givenFrontendInput, $expectedDefaultValue)
    {
        $this->assertEquals($expectedDefaultValue, $this->_model->getDefaultValueByInput($givenFrontendInput));
    }

    public static function dataGetDefaultValueByInput()
    {
        return array(
            array('unrecognized-frontend-input', ''),
            array('select', ''),
            array('gallery', ''),
            array('media_image', ''),
            array('multiselect', null),
            array('text', 'default_value_text'),
            array('price', 'default_value_text'),
            array('image', 'default_value_text'),
            array('weight', 'default_value_text'),
            array('textarea', 'default_value_textarea'),
            array('date', 'default_value_date'),
            array('boolean', 'default_value_yesno'),
        );
    }

    /**
     * @param array|null $sortWeights
     * @param float $expected
     * @dataProvider getSortWeightDataProvider
     */
    public function testGetSortWeight($sortWeights, $expected)
    {
        $setId = 123;
        $this->_model->setAttributeSetInfo(array($setId => $sortWeights));
        $this->assertEquals($expected, $this->_model->getSortWeight($setId));
    }

    /**
     * @return array
     */
    public function getSortWeightDataProvider()
    {
        return array(
            'empty set info' => array(
                'sortWeights' => null,
                'expectedWeight' => 0
            ),
            'no group sort' => array(
                'sortWeights' => array(
                    'sort' => 5
                ),
                'expectedWeight' => 0.0005
            ),
            'no sort' => array(
                'sortWeights' => array(
                    'group_sort' => 7
                ),
                'expectedWeight' => 7000
            ),
            'group sort and sort' => array(
                'sortWeights' => array(
                    'group_sort' => 7,
                    'sort' => 5
                ),
                'expectedWeight' => 7000.0005
            ),
        );
    }
}
