<?php

namespace Webit\DPDClient\DPDServices\PackagesPickupCall;

use Webit\DPDClient\DPDServices\Common\AuthDataV1;
use Webit\DPDClient\DPDServices\DPDPickupCallParams\DpdPickupCallParamsV3;
use Webit\SoapApi\AbstractApi;

class PackagesPickupCallV3 extends AbstractApi
{
    /**
     * @param DpdPickupCallParamsV3 $pickupCallParams
     * @param AuthDataV1 $authData
     * @return PackagesPickupCallResponseV3
     */
    public function __invoke(DpdPickupCallParamsV3 $pickupCallParams, AuthDataV1 $authData)
    {
        /** @var PackagesPickupCallResponseV3 $response */
        $response = $this->doRequest(
            'packagesPickupCallV3',
            array(
                'dpdPickupParamsV3' => $pickupCallParams,
                'authDataV1' => $authData
            )
        );

        return $response;
    }
}