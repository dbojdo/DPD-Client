<?php
/**
 * Created by PhpStorm.
 * User: dbojdo
 * Date: 08/08/2017
 * Time: 13:52
 */

namespace Webit\DPDClient\PackagesPickupCall;

use Webit\DPDClient\AbstractApiTest;
use Webit\DPDClient\DPDPickupCallParams\ContactInfoDPPV1;
use Webit\DPDClient\DPDPickupCallParams\DpdPickupCallParamsV1;
use Webit\DPDClient\DPDPickupCallParams\PolicyDPPEnumV1;
use Webit\DPDClient\DPDPickupCallParams\ProtocolDPPV1;
use Webit\DPDClient\DPDServicesParams\PickupAddressDSPV1;

class PackagesPickupCallV1ApiTest extends AbstractApiTest
{
    /**
     * @var PackagesPickupCallV1
     */
    private $packagesPickupCall;

    protected function setUp()
    {
        $this->packagesPickupCall = new PackagesPickupCallV1($this->soapExecutor());
    }

    /**
     * @group api
     * @test
     * @dataProvider protocols
     * @param array $protocols
     * @param array $status
     */
    public function shouldCallPickup(array $protocols, array $status)
    {
        $autData = $this->authData();
        $params = new DpdPickupCallParamsV1(
            PolicyDPPEnumV1::STOP_ON_FIRST_ERROR,
            new PickupAddressDSPV1(
                $autData->masterFid(),
                $this->faker()->name,
                $this->faker()->company,
                $this->faker()->streetAddress,
                $this->faker()->city,
                'PL',
                $this->faker()->postcode,
                $this->faker()->phoneNumber,
                $this->faker()->email
            ),
            new ContactInfoDPPV1(
                $this->faker()->name,
                $this->faker()->company,
                $this->faker()->phoneNumber,
                $this->faker()->email,
                $this->randomString(64)
            ),
            $protocols,
            date_create('now + 2 days'),
            '10:00',
            '16:00'
        );

        $result = $this->packagesPickupCall->__invoke(
            $params,
            $autData
        );

        $this->assertInstanceOf(
            'Webit\DPDClient\PackagesPickupCall\PackagesPickupCallResponseV1',
            $result
        );

        foreach ($result->protocols() as $k => $protocol) {
            $this->assertEquals(
                new ProtocolPCRV1(
                    $protocols[$k]->documentId(),
                    new StatusInfoPCRV1($status[$k])
                ),
                $protocol
            );
        }
    }

    public function protocols()
    {
        return array(
            'single protocol' => array(
                array(
                    new ProtocolDPPV1(
                        9962
                    )
                ),
                array('OK')
            ),
            'more than one protocol' => array(
                array(
                    new ProtocolDPPV1(
                        9962
                    ),
                    new ProtocolDPPV1(
                        9963
                    )
                ),
                array('OK', 'OK')
            ),
            'invalid protocol' => array(
                array(
                    new ProtocolDPPV1(
                        214423
                    )
                ),
                array('INCORRECT_PICKUP_ADDRESS_FID')
            ),
        );
    }
}