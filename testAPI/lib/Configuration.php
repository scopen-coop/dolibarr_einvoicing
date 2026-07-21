<?php
/**
 * Configuration
 * PHP version 7.4
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * HUBTIMIZE PA (Customer management) OpenAPI definition
 *
 * _Par Esalink (Plateforme Agréée)_  Each endpoint must be called with an access token (Bearer). This token is retrieved by a call to a token URL.  In order to avoid any firewall block, please add to you requests header the following value :   > For __TEST__ environment  >>- Key = `hubtimize-api-key`  >>- Value = `a3e49892-260f-4a3f-b497-3c9b68ee85d1`    > For __PROD__ environment  >>- Key = `hubtimize-api-key`  >>- Value = _to be requested before go live_  --- ## Changelog   >- `v1.2.7` July 2026   >>- __Change__ POST `/v1/oauth2/token` => __Breaking change__ in order to be compliant with oauth2 and RFC 6749   >>>- Actual way of working is __disabled__ (user query Parameters)  >>>- Two ways of using this endpoint  >>>>- Using `application/x-www-form-urlencoded` with values __client_id__, __client_secret__ and __grant_type=client_credentials__  >>>>- HTTP Basic : using header `Authorization : Basic` with encoded credentials __Authorization : Basic base64(urlencode(client_id):urlencode(client_secret))__ and using `application/x-www-form-urlencoded` with value __grant_type=client_credentials__  >- `v1.2.6` June 2026   >>- __Change__ PUT `/v1/customerPA/{customerID}` => email to receive documents to sign can be changed too (\"customerEmail\": \"xxx\")  >>- __Add__ POST `/v1/customerPA/{customerId}/resend-kyc-onboarding-email` => Resend onboarding email   >>- __Add__ new identifiers status   >>>- __UPDATE__ : Identifier date change requested  >>>- __WAITINGACTIVATE__ : identifier request at the customer creation, waiting the customer activation to be created  >>>- __ERROR__ : Error happening during the automatic setup, require human attention, managed by Esalink  >- `v1.2.5` April 2026   >>- __Add__ `/v1/oauth2/token` => standard oauth2 token api  >>- __Change__ management of customer users  >>>- `POST /v1/customerPA/{customerID}/user`  >>>- `POST /v1/customerPA/{customerID}/user/search`  >>>- `GET /v1/customerPA/{customerID}/user/{userId}`  >>>- `PUT /v1/customerPA/{customerID}/user/{userId}`  >>- __Add__ management of customer identifiers  >>>- `POST /v1/customerPA/{customerID}/identifier`  >>>- `POST /v1/customerPA/{customerID}/identifier/search`  >>>- `GET /v1/customerPA/{customerID}/identifier/{identifierId}`  >>>- `PUT /v1/customerPA/{customerID}/identifier/{identifierId}`  >>>- `DELETE /v1/customerPA/{customerID}/identifier/{identifierId}` >- `v1.2.4` March 2026  >>- __Add__ optional field `dateStart` on `POST /v1/customerPA`  >>- Field `electronicAddresses ` is now __optional__ on `POST /v1/customerPA`  >>- __Update__ management of customer APIs  >>>- `POST /v1/customerPA`  >>>- `PUT /v1/customerPA/{customerID}`  >>>- `GET /v1/customerPA/{customerID} `  >>>- `POST /v1/search`   --- ## CUSTOMER Api   The __CustomerPA Service__ API allows you to:  >- Manage your customers  >- Manage your customer's users  A __customer__ is defined by   >- an EDI Entity (for technical purpose), contains  >>- A name (unique)  >>- A parent entity  >>- An email (required, to receive on boarding email)  >>- A language (optional)  >>- A logo (optional, by default the one from the parent will be used)  >>- Identifiers list (optional, 0225 for France eInvoicing, 0088, 0002, 0208 for Belgium, etc.)  >- an eInvoice Entity (for legal purpose), contains  >>- An EDI entity  >>- A legal entity name  >>- Postal address  >>- Legal identifier (SIREN, Company code, etc.)  >>- VAT codes (optional)  >>- Plateforme agrée service (send/receive eInvoiceFR)   __Customer management__   >- `POST /v1/customerPA` : Create a customer  >- `POST /v1/customerPA/search` : Search a customer  >- `GET /v1/customerPA/{customerId}` : Get a customer  >- `PUT /v1/customerPA/{customerId}` : Update a customer    A customer status can be  >- ACTIVE : Validated by esalink and ready to be used (at least for sending, for receiving, need to be sure the related receiving identifier is \"Active\")  >- INACTIVE : Disabled customer  >- WAITING_VALIDATION : Customer waiting to be validated by Esalink  >- REJECTED : Customer rejected by Esalink   Create a customer without any identifier will send __an onboarding email for identity and company verification (KYC/KYB)__   Create a customer with identifier will :  >- __Create identifier request__ for each identifier request (based on send/receive option and start date selected)  >- Send __an onboarding email for identity and company verification (KYC/KYB) and, if receiving is activated, register lines on directory__     __Customer's API user management__  >- `POST /v1/customerPA/{customerID}/user` : Create a customer's API user  >- `GET /v1/customerPA/{customerID}/user` : List all customer's API user  >- `GET /v1/customerPA/{customerID}/user/{userId}` : Get a specific customer's API user  >- `PUT /v1/customerPA/{customerID}/user/{userId}` : Update a API customer  >- `POST /v1/customerPA/{customerID}/user/{userId}/clientSecret` : generate a new client secret for this API customer   When creating a customer's API user, __a clientId and a clientSecret__ are generated (they still can be used as username/password on the tokan API).    A customer's API user status can be  >- enabled : enable = true   >- disabled : enable = false   __Customer's identifiers management__ (Identifier can be an identifier on administration french directory, an address on Peppol Network, etc.)  >- `POST /v1/customerPA/{customerID}/identifier` : Request a new identifier for the customer  >- `POST /v1/customerPA/{customerID}/identifier/search` : Search all identifiers for this customer  >- `GET /v1/customerPA/{customerID}/identifier` : Get a specific identifier detail  >- `PUT /v1/customerPA/{customerID}/identifier` : Update an identifier (only on Status NEW, REFUSED, DONE)  >- `DELETE /v1/customerPA/{customerID}/identifier` : Remove an identifier (only for status NEW, REFUSED, DONE)    A customer identifier can be  >- NEW : New identifier request _(can be updated or deleted)_  >- DELETE : Deleted identifiers  >- IN_PROGRESS : Identifier setup currently in progress by Esalink _(identifier can't be updated or deleted)_  >- UPDATE : Identifier date change requested  >- WAITINGACTIVATE : identifier request at the customer creation, waiting the customer activation to be created  >- WAITINGPEPPOL : Peppol realease ask to previous access point, waiting return _(identifier can't be updated or deleted)_  >- WAITINGOPTIN : Docusign PDF (OPTIN) sent for new identifier, waiting customer signature _(identifier can't be updated or deleted)_  >- REFUSED : Identifier request refused by Esalink (reason in comment)  >- ERROR : Error happening during the automatic setup, require human attention, managed by Esalink  >- _DONE_ This status is not returned but a calculated status is sent instead, based on activation date  >>- Active : Identifier active on requested scope (Peppol only for Sending, AIFE Directory + Peppol for France receiving, etc.)  >>- Inactive : Identifier reach it's end date  >>- Upcoming : Setup ready, It'll be activated at the requested date    All request will be validated __once a day__, if a \"register document\" signed by customer is needed, __PDF pre-filled document will be sent to the customer__   ---    # API technical documentation
 *
 * The version of the OpenAPI document: v1.2.7.2
 * Generated by: https://openapi-generator.tech
 * Generator version: 7.7.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace OpenAPI\Client;

/**
 * Configuration Class Doc Comment
 * PHP version 7.4
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class Configuration
{
    public const BOOLEAN_FORMAT_INT = 'int';
    public const BOOLEAN_FORMAT_STRING = 'string';

    /**
     * @var Configuration
     */
    private static $defaultConfiguration;

    /**
     * Associate array to store API key(s)
     *
     * @var string[]
     */
    protected $apiKeys = [];

    /**
     * Associate array to store API prefix (e.g. Bearer)
     *
     * @var string[]
     */
    protected $apiKeyPrefixes = [];

    /**
     * Access token for OAuth/Bearer authentication
     *
     * @var string
     */
    protected $accessToken = '';

    /**
     * Boolean format for query string
     *
     * @var string
     */
    protected $booleanFormatForQueryString = self::BOOLEAN_FORMAT_INT;

    /**
     * Username for HTTP basic authentication
     *
     * @var string
     */
    protected $username = '';

    /**
     * Password for HTTP basic authentication
     *
     * @var string
     */
    protected $password = '';

    /**
     * The host
     *
     * @var string
     */
    protected $host = 'https://ppd.hubtimize.fr/api/orchestrator';

    /**
     * User agent of the HTTP request, set to "OpenAPI-Generator/{version}/PHP" by default
     *
     * @var string
     */
    protected $userAgent = 'OpenAPI-Generator/1.0.0/PHP';

    /**
     * Debug switch (default set to false)
     *
     * @var bool
     */
    protected $debug = false;

    /**
     * Debug file location (log to STDOUT by default)
     *
     * @var string
     */
    protected $debugFile = 'php://output';

    /**
     * Debug file location (log to STDOUT by default)
     *
     * @var string
     */
    protected $tempFolderPath;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tempFolderPath = sys_get_temp_dir();
    }

    /**
     * Sets API key
     *
     * @param string $apiKeyIdentifier API key identifier (authentication scheme)
     * @param string $key              API key or token
     *
     * @return $this
     */
    public function setApiKey($apiKeyIdentifier, $key)
    {
        $this->apiKeys[$apiKeyIdentifier] = $key;
        return $this;
    }

    /**
     * Gets API key
     *
     * @param string $apiKeyIdentifier API key identifier (authentication scheme)
     *
     * @return null|string API key or token
     */
    public function getApiKey($apiKeyIdentifier)
    {
        return isset($this->apiKeys[$apiKeyIdentifier]) ? $this->apiKeys[$apiKeyIdentifier] : null;
    }

    /**
     * Sets the prefix for API key (e.g. Bearer)
     *
     * @param string $apiKeyIdentifier API key identifier (authentication scheme)
     * @param string $prefix           API key prefix, e.g. Bearer
     *
     * @return $this
     */
    public function setApiKeyPrefix($apiKeyIdentifier, $prefix)
    {
        $this->apiKeyPrefixes[$apiKeyIdentifier] = $prefix;
        return $this;
    }

    /**
     * Gets API key prefix
     *
     * @param string $apiKeyIdentifier API key identifier (authentication scheme)
     *
     * @return null|string
     */
    public function getApiKeyPrefix($apiKeyIdentifier)
    {
        return isset($this->apiKeyPrefixes[$apiKeyIdentifier]) ? $this->apiKeyPrefixes[$apiKeyIdentifier] : null;
    }

    /**
     * Sets the access token for OAuth
     *
     * @param string $accessToken Token for OAuth
     *
     * @return $this
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * Gets the access token for OAuth
     *
     * @return string Access token for OAuth
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Sets boolean format for query string.
     *
     * @param string $booleanFormat Boolean format for query string
     *
     * @return $this
     */
    public function setBooleanFormatForQueryString(string $booleanFormat)
    {
        $this->booleanFormatForQueryString = $booleanFormat;

        return $this;
    }

    /**
     * Gets boolean format for query string.
     *
     * @return string Boolean format for query string
     */
    public function getBooleanFormatForQueryString(): string
    {
        return $this->booleanFormatForQueryString;
    }

    /**
     * Sets the username for HTTP basic authentication
     *
     * @param string $username Username for HTTP basic authentication
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Gets the username for HTTP basic authentication
     *
     * @return string Username for HTTP basic authentication
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the password for HTTP basic authentication
     *
     * @param string $password Password for HTTP basic authentication
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Gets the password for HTTP basic authentication
     *
     * @return string Password for HTTP basic authentication
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the host
     *
     * @param string $host Host
     *
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * Gets the host
     *
     * @return string Host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Sets the user agent of the api client
     *
     * @param string $userAgent the user agent of the api client
     *
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        if (!is_string($userAgent)) {
            throw new \InvalidArgumentException('User-agent must be a string.');
        }

        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * Gets the user agent of the api client
     *
     * @return string user agent
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Sets debug flag
     *
     * @param bool $debug Debug flag
     *
     * @return $this
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * Gets the debug flag
     *
     * @return bool
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * Sets the debug file
     *
     * @param string $debugFile Debug file
     *
     * @return $this
     */
    public function setDebugFile($debugFile)
    {
        $this->debugFile = $debugFile;
        return $this;
    }

    /**
     * Gets the debug file
     *
     * @return string
     */
    public function getDebugFile()
    {
        return $this->debugFile;
    }

    /**
     * Sets the temp folder path
     *
     * @param string $tempFolderPath Temp folder path
     *
     * @return $this
     */
    public function setTempFolderPath($tempFolderPath)
    {
        $this->tempFolderPath = $tempFolderPath;
        return $this;
    }

    /**
     * Gets the temp folder path
     *
     * @return string Temp folder path
     */
    public function getTempFolderPath()
    {
        return $this->tempFolderPath;
    }

    /**
     * Gets the default configuration instance
     *
     * @return Configuration
     */
    public static function getDefaultConfiguration()
    {
        if (self::$defaultConfiguration === null) {
            self::$defaultConfiguration = new Configuration();
        }

        return self::$defaultConfiguration;
    }

    /**
     * Sets the default configuration instance
     *
     * @param Configuration $config An instance of the Configuration Object
     *
     * @return void
     */
    public static function setDefaultConfiguration(Configuration $config)
    {
        self::$defaultConfiguration = $config;
    }

    /**
     * Gets the essential information for debugging
     *
     * @return string The report for debugging
     */
    public static function toDebugReport()
    {
        $report  = 'PHP SDK (OpenAPI\Client) Debug Report:' . PHP_EOL;
        $report .= '    OS: ' . php_uname() . PHP_EOL;
        $report .= '    PHP Version: ' . PHP_VERSION . PHP_EOL;
        $report .= '    The version of the OpenAPI document: v1.2.7.2' . PHP_EOL;
        $report .= '    Temp Folder Path: ' . self::getDefaultConfiguration()->getTempFolderPath() . PHP_EOL;

        return $report;
    }

    /**
     * Get API key (with prefix if set)
     *
     * @param  string $apiKeyIdentifier name of apikey
     *
     * @return null|string API key with the prefix
     */
    public function getApiKeyWithPrefix($apiKeyIdentifier)
    {
        $prefix = $this->getApiKeyPrefix($apiKeyIdentifier);
        $apiKey = $this->getApiKey($apiKeyIdentifier);

        if ($apiKey === null) {
            return null;
        }

        if ($prefix === null) {
            $keyWithPrefix = $apiKey;
        } else {
            $keyWithPrefix = $prefix . ' ' . $apiKey;
        }

        return $keyWithPrefix;
    }

    /**
     * Returns an array of host settings
     *
     * @return array an array of host settings
     */
    public function getHostSettings()
    {
        return [
            [
                "url" => "https://ppd.hubtimize.fr/api/orchestrator",
                "description" => "Test environment",
            ],
            [
                "url" => "https://hubtimize.fr/api/orchestrator",
                "description" => "Production environment (not ready)",
            ]
        ];
    }

    /**
    * Returns URL based on host settings, index and variables
    *
    * @param array      $hostSettings array of host settings, generated from getHostSettings() or equivalent from the API clients
    * @param int        $hostIndex    index of the host settings
    * @param array|null $variables    hash of variable and the corresponding value (optional)
    * @return string URL based on host settings
    */
    public static function getHostString(array $hostSettings, $hostIndex, array $variables = null)
    {
        if (null === $variables) {
            $variables = [];
        }

        // check array index out of bound
        if ($hostIndex < 0 || $hostIndex >= count($hostSettings)) {
            throw new \InvalidArgumentException("Invalid index $hostIndex when selecting the host. Must be less than ".count($hostSettings));
        }

        $host = $hostSettings[$hostIndex];
        $url = $host["url"];

        // go through variable and assign a value
        foreach ($host["variables"] ?? [] as $name => $variable) {
            if (array_key_exists($name, $variables)) { // check to see if it's in the variables provided by the user
                if (!isset($variable['enum_values']) || in_array($variables[$name], $variable["enum_values"], true)) { // check to see if the value is in the enum
                    $url = str_replace("{".$name."}", $variables[$name], $url);
                } else {
                    throw new \InvalidArgumentException("The variable `$name` in the host URL has invalid value ".$variables[$name].". Must be ".join(',', $variable["enum_values"]).".");
                }
            } else {
                // use default value
                $url = str_replace("{".$name."}", $variable["default_value"], $url);
            }
        }

        return $url;
    }

    /**
     * Returns URL based on the index and variables
     *
     * @param int        $index     index of the host settings
     * @param array|null $variables hash of variable and the corresponding value (optional)
     * @return string URL based on host settings
     */
    public function getHostFromSettings($index, $variables = null)
    {
        return self::getHostString($this->getHostSettings(), $index, $variables);
    }
}
