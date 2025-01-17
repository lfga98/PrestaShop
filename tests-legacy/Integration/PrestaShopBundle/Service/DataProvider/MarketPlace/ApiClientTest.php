<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace LegacyTests\Integration\PrestaShopBundle\Service\DataProvider\MarketPlace;

use GuzzleHttp\Psr7\Response;
use Phake;
use PHPUnit\Framework\MockObject\MockObject;
use PrestaShopBundle\Service\DataProvider\Marketplace\ApiClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group addons
 */
class ApiClientTest extends KernelTestCase
{
    /**
     * @var ApiClient
     */
    protected $apiClient;

    protected function setUp()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $kernel->getContainer()->set('prestashop.adapter.legacy.context', $this->mockLegacyContext());

        $this->apiClient = $kernel->getContainer()->get('prestashop.addons.client_api');
        $this->apiClient->setClient($this->mockClient());
    }

    protected function tearDown()
    {
        $this->apiClient = null;
    }

    public function testGetNativeModules()
    {
        $this->assertCount(0, $this->apiClient->getNativesModules());
    }

    /**
     * @return MockObject
     */
    protected function mockClient()
    {
        $clientMock = $this->getMockBuilder('\GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $clientMock->method('get')
            ->with($this->anything())
            ->willReturn(new Response(200, [], json_encode((object) ['modules' => []])));

        return $clientMock;
    }

    /**
     * @return \PrestaShop\PrestaShop\Adapter\LegacyContext
     */
    protected function mockLegacyContext()
    {
        $context = Phake::mock('Context');
        $context->language = Phake::mock('Language');

        $legacyContext = Phake::mock('\PrestaShop\PrestaShop\Adapter\LegacyContext');
        Phake::when($legacyContext)->getContext()->thenReturn($context);

        return $legacyContext;
    }
}
