<?php


namespace Webit\DPDClient\DPDInfoServices;

use Webit\DPDClient\DPDInfoServices\Client\ClientEnvironments;
use Webit\DPDClient\DPDInfoServices\Client\SoapExecutorFactory;
use Webit\DPDClient\DPDInfoServices\Common\AuthDataV1;
use Webit\SoapApi\Util\Dumper\PhpFileDumper;
use Webit\SoapApi\Util\Dumper\StaticNameGenerator;
use Webit\SoapApi\Util\Dumper\VoidDumper;

abstract class AbstractApiTest extends AbstractIntegrationTest
{
    /**
     * @return \Webit\SoapApi\Executor\SoapApiExecutor
     */
    protected function soapExecutor()
    {
        $factory = new SoapExecutorFactory(
            null,
            $this->normaliserFactory(),
            $this->hydratorFactory()
        );
        return $factory->create(
            $this->getEnv('dpd.info_services_wsdl') ?: ClientEnvironments::wsdl(ClientEnvironments::TEST)
        );
    }

    /**
     * @return AuthDataV1
     * @throw \PHPUnit_Framework_SkippedTestError
     */
    protected function authData()
    {
        $authData = new AuthDataV1(
            $this->getEnv('dpd.login'),
            $this->getEnv('dpd.password'),
            $this->getEnv('dpd.fid')
        );

        if (!$this->isAuthDataSet($authData)) {
            $this->markTestSkipped(
                'The dpd.login or the dpd.password is not set. Change your phpunit.xml settings.'
            );
        }

        if ($authData->channel() == 0) {
            $this->markTestSkipped('The dpd.fid is not set. Change your phpunit.xml settings.');
        }

        return $authData;
    }

    /**
     * @param $key
     * @return null
     */
    protected function getEnv($key)
    {
        return isset($_ENV[$key]) ? $_ENV[$key] : null;
    }

    /**
     * @param AuthDataV1 $authData
     * @return bool
     */
    private function isAuthDataSet(AuthDataV1 $authData)
    {
        return !in_array('changeme', array($authData->login(), $authData->password()))
            && $authData->login()
            && $authData->password();
    }

    protected function ioDumper()
    {
        $dir = $this->ioDumperDir();
        if (!$dir) {
            return new VoidDumper();
        }
        return new PhpFileDumper(
            $dir,
            new StaticNameGenerator('SoapIoDump-'.self::$runId)
        );
    }
}