<?php
/**
 * Copyright 2020 SURFnet B.V.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Surfnet\StepupSelfService\SelfServiceBundle\Tests\Service\RemoteVetting;

use Mockery as m;
use PHPUnit_Framework_TestCase as UnitTest;
use Surfnet\StepupSelfService\SelfServiceBundle\Exception\InvalidRemoteVettingMappingException;
use Surfnet\StepupSelfService\SelfServiceBundle\Service\RemoteVetting\Dto\AttributeListDto;
use Surfnet\StepupSelfService\SelfServiceBundle\Service\RemoteVetting\IdentityProviderFactory;
use Surfnet\StepupSelfService\SelfServiceBundle\Service\RemoteVetting\AttributeMapper;

class AttributeMapperTest extends UnitTest
{
    /**
     * @var IdentityProviderFactory|m\Mock
     */
    private $identityProviderFactory;
    /**
     * @var AttributeMapper
     */
    private $attributeMapper;

    public function setUp()
    {
        $this->identityProviderFactory = m::mock(IdentityProviderFactory::class);
        $this->attributeMapper = new AttributeMapper($this->identityProviderFactory);
    }

    public function test_attribute_mapping()
    {
        $config = [
            'foo2' => 'baz2',
            'foo3' => 'baz1',
        ];

        $nameId = 'foo@bar.baz';
        $local = [
            'foo1' => ['bar1'],
            'foo2' => ['bar2'],
            'foo3' => ['bar3'],
        ];
        $external = [
            'baz1' => ['foobar1'],
            'baz2' => ['foobar2'],
            'baz3' => ['foobar3'],
        ];

        $this->identityProviderFactory->shouldReceive('getAttributeMapping')
            ->andReturn($config);

        $localAttributes = new AttributeListDto($local, $nameId);
        $externalAttributes = new AttributeListDto($external, '');

        $attributeMatchCollection = $this->attributeMapper->map('idp-name', $localAttributes, $externalAttributes);

        $result = json_encode($attributeMatchCollection->getAttributes());

        $this->assertSame('{"foo2":{"local":{"name":"foo2","value":["bar2"]},"remote":{"name":"baz2","value":["foobar2"]},"is-valid":false,"remarks":""},"foo3":{"local":{"name":"foo3","value":["bar3"]},"remote":{"name":"baz1","value":["foobar1"]},"is-valid":false,"remarks":""}}', $result);
    }


    public function test_attribute_mapping_missing_local()
    {
        $this->expectException(InvalidRemoteVettingMappingException::class);
        $this->expectExceptionMessage('Invalid remote vetting attribute mapping, local attribute with name "MISSING" not found');

        $config = [
            'MISSING' => 'baz2',
            'foo3' => 'baz1',
        ];

        $nameId = 'foo@bar.baz';
        $local = [
            'foo1' => ['bar1'],
            'foo2' => ['bar2'],
            'foo3' => ['bar3'],
        ];
        $external = [
            'baz1' => ['foobar1'],
            'baz2' => ['foobar2'],
            'baz3' => ['foobar3'],
        ];

        $this->identityProviderFactory->shouldReceive('getAttributeMapping')
            ->andReturn($config);

        $localAttributes = new AttributeListDto($local, $nameId);
        $externalAttributes = new AttributeListDto($external, '');

        $this->attributeMapper->map('idp-name', $localAttributes, $externalAttributes);
    }



    public function test_attribute_mapping_missing_external()
    {
        $this->expectException(InvalidRemoteVettingMappingException::class);
        $this->expectExceptionMessage('Invalid remote vetting attribute mapping, remote attribute with name "MISSING" not found');

        $config = [
            'foo2' => 'MISSING',
            'foo3' => 'baz1',
        ];

        $nameId = 'foo@bar.baz';
        $local = [
            'foo1' => ['bar1'],
            'foo2' => ['bar2'],
            'foo3' => ['bar3'],
        ];
        $external = [
            'baz1' => ['foobar1'],
            'baz2' => ['foobar2'],
            'baz3' => ['foobar3'],
        ];

        $this->identityProviderFactory->shouldReceive('getAttributeMapping')
            ->andReturn($config);

        $localAttributes = new AttributeListDto($local, $nameId);
        $externalAttributes = new AttributeListDto($external, '');

        $mappedAttributes = $this->attributeMapper->map('idp-name', $localAttributes, $externalAttributes);
    }
}