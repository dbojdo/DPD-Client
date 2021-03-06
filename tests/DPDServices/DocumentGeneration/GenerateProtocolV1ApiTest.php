<?php

namespace Webit\DPDClient\DPDServices\DocumentGeneration;

use Webit\DPDClient\DPDServices\AbstractApiTest;
use Webit\DPDClient\DPDServices\DPDServicesParams\DPDServicesParamsV1;
use Webit\DPDClient\DPDServices\DPDServicesParams\PackageDSPV1;
use Webit\DPDClient\DPDServices\DPDServicesParams\PickupAddressDSPV1;
use Webit\DPDClient\DPDServices\DPDServicesParams\PolicyDSPEnumV1;
use Webit\DPDClient\DPDServices\DPDServicesParams\SessionDSPV1;
use Webit\DPDClient\DPDServices\PackagesGeneration\GeneratePackagesNumbersV1;
use Webit\DPDClient\DPDServices\PackagesGeneration\PkgNumsGenerationPolicyEnumV1;

/**
 * @group api
 */
class GenerateProtocolV1ApiTest extends AbstractApiTest
{
    /** @var GeneratePackagesNumbersV1 */
    private $generatePackagesNumbers;

    /** @var GenerateProtocolV1 */
    private $generateProtocol;

    protected function setUp()
    {
        $this->generatePackagesNumbers = new GeneratePackagesNumbersV1($this->soapExecutor());
        $this->generateProtocol = new GenerateProtocolV1($this->soapExecutor());
    }

    /**
     * @test
     */
    public function shouldGenerateProtocol()
    {
        $openUml = $this->generateOpenUmlf(false, false, null);
        $numbers = $this->generatePackagesNumbers->__invoke(
            $openUml,
            PkgNumsGenerationPolicyEnumV1::allOrNothing(),
            $this->authData()
        );

        $packages = array();
        foreach ($numbers->packages() as $package) {
            $packages[] = PackageDSPV1::fromPackageId($package->packageId());
        }

        $response = $this->generateProtocol->__invoke(
            new DPDServicesParamsV1(
                PolicyDSPEnumV1::stopOnFirstError(),
                SessionDSPV1::fromSession($numbers->sessionId()),
                PickupAddressDSPV1::fromFid($this->authData()->masterFid()),
                null
            ),
            OutputDocFormatDSPEnumV1::pdf(),
            OutputDocPageFormatDSPEnumV1::a4(),
            $this->authData()
        );

        $this->assertInstanceOf('Webit\DPDClient\DPDServices\DocumentGeneration\DocumentGenerationResponseV1', $response);
    }
}