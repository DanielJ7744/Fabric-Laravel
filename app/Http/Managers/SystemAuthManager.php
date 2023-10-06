<?php

namespace App\Http\Managers;

use App\Exceptions\MissingSystemAuthAttributesException;
use App\Http\Abstracts\SystemAuthAbstract;
use App\Http\Helpers\SoapHelper;
use App\Http\Interfaces\SystemAuthManagerInterface;
use App\Http\Services\Auth\BigcommerceService;
use App\Http\Services\Auth\BIService;
use App\Http\Services\Auth\Brightpearlv2Service;
use App\Http\Services\Auth\CommerceToolsService;
use App\Http\Services\Auth\CsvService;
use App\Http\Services\Auth\CybertillService;
use App\Http\Services\Auth\DynamicsNavService;
use App\Http\Services\Auth\EmarsysService;
use App\Http\Services\Auth\EposNowService;
use App\Http\Services\Auth\FarfetchService;
use App\Http\Services\Auth\InboundApiService;
use App\Http\Services\Auth\JsonService;
use App\Http\Services\Auth\KhaosService;
use App\Http\Services\Auth\LinnworksService;
use App\Http\Services\Auth\MagentotwoService;
use App\Http\Services\Auth\MiraklService;
use App\Http\Services\Auth\NetsuiteService;
use App\Http\Services\Auth\OmetriaService;
use App\Http\Services\Auth\PeoplevoxService;
use App\Http\Services\Auth\PrimaService;
use App\Http\Services\Auth\ReboundService;
use App\Http\Services\Auth\S3Service;
use App\Http\Services\Auth\SFTPService;
use App\Http\Services\Auth\SapB1Service;
use App\Http\Services\Auth\SekoAPIService;
use App\Http\Services\Auth\SekoService;
use App\Http\Services\Auth\ShopifyService;
use App\Http\Services\Auth\SyteLineService;
use App\Http\Services\Auth\TorqueAPIService;
use App\Http\Services\Auth\TxtService;
use App\Http\Services\Auth\VeeqoService;
use App\Http\Services\Auth\VendService;
use App\Http\Services\Auth\VisualsoftService;
use App\Http\Services\Auth\WooCommerceService;
use App\Http\Services\Auth\XmlService;
use App\Http\Services\Auth\ZigZagService;
use Aws\S3\S3Client;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Manager;
use Illuminate\Support\Str;
use InvalidArgumentException;
use SoapClient;
use SoapFault;
use phpseclib\Net\SFTP;

class SystemAuthManager extends Manager implements SystemAuthManagerInterface
{
    /**
     * Get a driver instance.
     *
     * @param string|null $driver
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     *
     * @throws InvalidArgumentException
     * @throws MissingSystemAuthAttributesException
     * @throws SoapFault
     */
    public function driver($driver = null, array $credentials = []): SystemAuthAbstract
    {
        if (is_null($driver)) {
            throw new InvalidArgumentException(
                sprintf('Unable to resolve NULL driver for [%s].', static::class)
            );
        }

        if (!isset($this->drivers[$driver])) {
            $this->drivers[$driver] = $this->createDriver($driver, $credentials);
        }

        return $this->drivers[$driver];
    }

    /**
     * Create a new driver instance.
     *
     * @param string $driver
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     *
     * @throws InvalidArgumentException
     * @throws MissingSystemAuthAttributesException
     * @throws SoapFault
     */
    protected function createDriver($driver, array $credentials = []): SystemAuthAbstract
    {
        if (isset($this->customCreators[$driver])) {
            return $this->callCustomCreator($driver);
        }

        $method = 'create' . Str::studly($driver) . 'Driver';
        if (method_exists($this, $method)) {
            return $this->$method($credentials);
        }

        throw new InvalidArgumentException("Driver [$driver] not supported.");
    }

    protected function getService(string $driver, array $credentials): string
    {
        $method = 'get' . Str::studly($driver) . 'Service';

        if (!method_exists($this, $method)) {
            throw new InvalidArgumentException("Driver [$driver] not supported.");
        }

        return $this::$method($credentials);
    }

    public function getRules(string $driver, array $credentials): array
    {
        $service = $this->getService($driver, $credentials);

        return $service::getRules();
    }

    public function getUpdateRules(string $driver, array $credentials): array
    {
        $service = $this->getService($driver, $credentials);

        return $service::getUpdateRules();
    }

    public static function getBigcommerceService(array $credentials): string
    {
        return BigcommerceService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createBigcommerceDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(BigcommerceService::class, ['attributes' => $credentials]);
    }

    public static function getBrightpearlv2Service(array $credentials): string
    {
        return Brightpearlv2Service::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createBrightpearlv2Driver(array $credentials): SystemAuthAbstract
    {
        return App::make(Brightpearlv2Service::class, ['attributes' => $credentials]);
    }

    public static function getCommerceToolsService(array $credentials): string
    {
        return CommerceToolsService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createCommerceToolsDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(CommerceToolsService::class, ['attributes' => $credentials]);
    }

    public static function getDynamicsNavService(array $credentials): string
    {
        return DynamicsNavService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createDynamicsNavDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(DynamicsNavService::class, ['attributes' => $credentials, 'client' => new Client(['verify' => false])]);
    }

    public static function getZigZagService(array $credentials): string
    {
        return ZigZagService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createZigZagDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(ZigZagService::class, ['attributes' => $credentials, 'client' => new Client(['verify' => false])]);
    }

    /**
     * @throws MissingSystemAuthAttributesException
     */
    public static function getEmarsysService(array $credentials): string
    {
        return EmarsysService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     * @throws MissingSystemAuthAttributesException
     */
    protected function createEmarsysDriver(array $credentials): SystemAuthAbstract
    {
        if (isset($credentials['ftp_host'])) {
            return App::make(
                EmarsysService::class,
                [
                    'attributes' => $credentials,
                    'client' => new Client(),
                    'sftpClient' => new SFTP($credentials['ftp_host'], 22)
                ]
            );
        }

        throw new MissingSystemAuthAttributesException('Missing required attribute `ftp_host`.', 422);
    }

    public static function getInboundApiService(array $credentials): string
    {
        return InboundApiService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createInboundApiDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(InboundApiService::class, ['attributes' => $credentials]);
    }

    public static function getKhaosService(array $credentials): string
    {
        return KhaosService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     * @throws SoapFault
     * @throws MissingSystemAuthAttributesException
     */
    protected function createKhaosDriver(array $credentials): SystemAuthAbstract
    {
        if (!isset($credentials['url'])) {
            throw new MissingSystemAuthAttributesException('Missing required attribute `url`.', 422);
        }

        try {
            $soapClient = new SoapClient($credentials['url'], SoapHelper::getSoapParameters());
        } catch (Exception $exception) {
            $soapClient = null;
        }

        return App::make(KhaosService::class, [
            'attributes' => $credentials,
            'soapClient' => $soapClient
        ]);
    }

    public static function getLinnworksService(array $credentials): string
    {
        return LinnworksService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createLinnworksDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(LinnworksService::class, ['attributes' => $credentials]);
    }

    public static function getMagentotwoService(array $credentials): string
    {
        return MagentotwoService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createMagentotwoDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(MagentotwoService::class, ['attributes' => $credentials]);
    }

    public static function getMiraklService(array $credentials): string
    {
        return MiraklService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createMiraklDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(MiraklService::class, ['attributes' => $credentials]);
    }

    public static function getNetsuiteService(array $credentials): string
    {
        return NetsuiteService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createNetsuiteDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(NetsuiteService::class, ['attributes' => $credentials]);
    }

    public static function getPeoplevoxService(array $credentials): string
    {
        return PeoplevoxService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     * @throws SoapFault
     * @throws MissingSystemAuthAttributesException
     */
    protected function createPeoplevoxDriver(array $credentials): SystemAuthAbstract
    {
        if (!isset($credentials['url'], $credentials['client_id'])) {
            throw new MissingSystemAuthAttributesException(
                'Missing one of required attributes `url`, `client_id`.',
                422
            );
        }

        $wsdl = sprintf(
            '%s/%s/%s',
            $credentials['url'],
            $credentials['client_id'],
            config('external-app.authentication_endpoints.peoplevox')
        );

        $soapClient = new SoapClient($wsdl, SoapHelper::getSoapParameters());

        return App::make(PeoplevoxService::class, ['attributes' => $credentials, 'soapClient' => $soapClient]);
    }

    public static function getReboundService(array $credentials): string
    {
        return ReboundService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createReboundDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(ReboundService::class, ['attributes' => $credentials]);
    }

    public static function getSekoAPIService(array $credentials): string
    {
        return SekoAPIService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createSekoAPIDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(SekoAPIService::class, ['attributes' => $credentials]);
    }

    public static function getSekoService(array $credentials): string
    {
        return SekoService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     * @throws MissingSystemAuthAttributesException
     */
    protected function createSekoDriver(array $credentials): SystemAuthAbstract
    {
        if (!isset($credentials['ftp_host'], $credentials['ftp_port'])) {
            throw new MissingSystemAuthAttributesException(
                'Missing one of required attributes `ftp_host`, `ftp_port`.',
                422
            );
        }

        return App::make(SekoService::class, [
            'attributes' => $credentials,
            'sftpClient' => new SFTP($credentials['ftp_host'], $credentials['ftp_port'])
        ]);
    }

    public static function getSFTPService(array $credentials): string
    {
        return SFTPService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     * @throws MissingSystemAuthAttributesException
     */
    protected function createSFTPDriver(array $credentials): SystemAuthAbstract
    {
        if (!isset($credentials['host'], $credentials['port'])) {
            throw new MissingSystemAuthAttributesException(
                'Missing one of required attributes `host`, `port`.',
                422
            );
        }

        return App::make(SFTPService::class, [
            'attributes' => $credentials,
            'sftp' => new SFTP($credentials['host'], $credentials['port'])
        ]);
    }

    public static function getShopifyService(array $credentials): string
    {
        return ShopifyService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createShopifyDriver(array $credentials)
    {
        return App::make(ShopifyService::class, ['attributes' => $credentials]);
    }

    public static function getVendService(array $credentials): string
    {
        return VendService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createVendDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(VendService::class, ['attributes' => $credentials]);
    }

    public static function getVisualsoftService(array $credentials): string
    {
        return VisualsoftService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     * @throws SoapFault
     * @throws MissingSystemAuthAttributesException
     */
    protected function createVisualsoftDriver(array $credentials): SystemAuthAbstract
    {
        if (!isset($credentials['url'], $credentials['url'])) {
            throw new MissingSystemAuthAttributesException('Missing one of required attributes `url`.', 422);
        }

        return App::make(VisualsoftService::class, [
            'attributes' => $credentials,
            'soapClient' => new SoapClient($credentials['url'] . '/api/soap/wsdl')
        ]);
    }

    public static function getWooCommerceService(array $credentials): string
    {
        return WooCommerceService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createWooCommerceDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(WooCommerceService::class, ['attributes' => $credentials]);
    }

    public static function getVeeqoService(array $credentials): string
    {
        return VeeqoService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createVeeqoDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(VeeqoService::class, ['attributes' => $credentials]);
    }

    public static function getEposNowService(array $credentials): string
    {
        return EposNowService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createEposNowDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(EposNowService::class, ['attributes' => $credentials]);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */

    protected function createSapB1Driver(array $credentials): SystemAuthAbstract
    {
        return App::make(SapB1Service::class, ['attributes' => $credentials]);
    }

    public static function getSapB1Service(array $credentials): string
    {
        return SapB1Service::class;
    }

    protected function createCybertillDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(CybertillService::class, ['attributes' => $credentials]);
    }

    public static function getCybertillService(array $credentials): string
    {
        return CybertillService::class;
    }

    protected function createTorqueAPIDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(TorqueAPIService::class, ['attributes' => $credentials]);
    }

    public static function getTorqueAPIService(array $credentials): string
    {
        return TorqueAPIService::class;
    }

    public static function getOmetriaService(array $credentials): string
    {
        return OmetriaService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createOmetriaDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(OmetriaService::class, ['attributes' => $credentials]);
    }

    public static function getCsvService(array $credentials): string
    {
        return CsvService::class;
    }

    protected function createCsvDriver(array $credentials): SystemAuthAbstract
    {
        if (!isset($credentials['host'], $credentials['port'])) {
            throw new MissingSystemAuthAttributesException(
                'Missing one of required attributes `host`, `port`.',
                422
            );
        }

        return App::make(CsvService::class, [
            'attributes' => $credentials,
            'sftp' => new SFTP($credentials['host'], $credentials['port'])
        ]);
    }

    public static function getJsonService(array $credentials): string
    {
        return JsonService::class;
    }

    protected function createJsonDriver(array $credentials): SystemAuthAbstract
    {
        if (!isset($credentials['host'], $credentials['port'])) {
            throw new MissingSystemAuthAttributesException(
                'Missing one of required attributes `host`, `port`.',
                422
            );
        }

        return App::make(JsonService::class, [
            'attributes' => $credentials,
            'sftp' => new SFTP($credentials['host'], $credentials['port'])
        ]);
    }

    public static function getTxtService(array $credentials): string
    {
        return TxtService::class;
    }

    protected function createTxtDriver(array $credentials): SystemAuthAbstract
    {
        if (!isset($credentials['host'], $credentials['port'])) {
            throw new MissingSystemAuthAttributesException(
                'Missing one of required attributes `host`, `port`.',
                422
            );
        }

        return App::make(TxtService::class, [
            'attributes' => $credentials,
            'sftp' => new SFTP($credentials['host'], $credentials['port'])
        ]);
    }

    public static function getXmlService(array $credentials): string
    {
        return XmlService::class;
    }

    protected function createXmlDriver(array $credentials): SystemAuthAbstract
    {
        if (!isset($credentials['host'], $credentials['port'])) {
            throw new MissingSystemAuthAttributesException(
                'Missing one of required attributes `host`, `port`.',
                422
            );
        }

        return App::make(XmlService::class, [
            'attributes' => $credentials,
            'sftp' => new SFTP($credentials['host'], $credentials['port'])
        ]);
    }

    public static function getSyteLineService(array $credentials): string
    {
        return SyteLineService::class;
    }

    protected function createSyteLineDriver(array $credentials): SystemAuthAbstract
    {
        if (!isset($credentials['host'], $credentials['port'])) {
            throw new MissingSystemAuthAttributesException(
                'Missing one of required attributes `host`, `port`.',
                422
            );
        }

        return App::make(SyteLineService::class, [
            'attributes' => $credentials,
            'sftp' => new SFTP($credentials['host'], $credentials['port'])
        ]);
    }

    public static function getPrimaService(array $credentials): string
    {
        return PrimaService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     */
    protected function createPrimaDriver(array $credentials): SystemAuthAbstract
    {
        return App::make(PrimaService::class, ['attributes' => $credentials]);
    }

    protected function createS3Driver(array $credentials): SystemAuthAbstract
    {
        $s3Client = new S3Client([
            'credentials' => [
                'key' => $credentials['key'],
                'secret' => $credentials['secret']
            ],
            'region' => 'eu-west-1',
            'version' => 'latest',
        ]);

        return App::make(S3Service::class, ['attributes' => $credentials, 's3Client' => $s3Client]);
    }

    public static function getS3Service(array $credentials): string
    {
        return S3Service::class;
    }

    protected function createBIDriver(array $credentials): SystemAuthAbstract
    {
        $s3Client = new S3Client([
            'credentials' => [
                'key' => config('systems.bi.key'),
                'secret' => config('systems.bi.secret')
            ],
            'region' => 'eu-west-1',
            'version' => 'latest',
        ]);

        return App::make(BIService::class, ['attributes' => $credentials, 's3Client' => $s3Client]);
    }

    public static function getBIService(array $credentials): string
    {
        return BIService::class;
    }

    public static function getFarfetchService(array $credentials): string
    {
        return FarfetchService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $credentials
     *
     * @return SystemAuthAbstract
     * @throws SoapFault
     * @throws MissingSystemAuthAttributesException
     */
    protected function createFarfetchDriver(array $credentials): SystemAuthAbstract
    {
        if (!isset($credentials['sales_url'])) {
            throw new MissingSystemAuthAttributesException(
                'Missing required attribute `sales_url`.',
                422
            );
        }

        $soapClient = new SoapClient($credentials['sales_url'], SoapHelper::getSoapParameters());

        return App::make(FarfetchService::class, ['attributes' => $credentials, 'soapClient' => $soapClient]);
    }

    /**
     * Get the default driver name.
     *
     * @throws InvalidArgumentException
     */
    public function getDefaultDriver()
    {
        throw new InvalidArgumentException('No system driver was specified.');
    }
}
