<?php
/**
 * APITokenApi
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

namespace OpenAPI\Client\Api;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use OpenAPI\Client\ApiException;
use OpenAPI\Client\Configuration;
use OpenAPI\Client\HeaderSelector;
use OpenAPI\Client\ObjectSerializer;

/**
 * APITokenApi Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class APITokenApi
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var HeaderSelector
     */
    protected $headerSelector;

    /**
     * @var int Host index
     */
    protected $hostIndex;

    /** @var string[] $contentTypes **/
    public const contentTypes = [
        'oauthToken' => [
            'application/x-www-form-urlencoded',
        ],
        'token' => [
            'application/json',
        ],
    ];

    /**
     * @param ClientInterface $client
     * @param Configuration   $config
     * @param HeaderSelector  $selector
     * @param int             $hostIndex (Optional) host index to select the list of hosts if defined in the OpenAPI spec
     */
    public function __construct(
        ClientInterface $client = null,
        Configuration $config = null,
        HeaderSelector $selector = null,
        $hostIndex = 0
    ) {
        $this->client = $client ?: new Client();
        $this->config = $config ?: new Configuration();
        $this->headerSelector = $selector ?: new HeaderSelector();
        $this->hostIndex = $hostIndex;
    }

    /**
     * Set the host index
     *
     * @param int $hostIndex Host index (required)
     */
    public function setHostIndex($hostIndex): void
    {
        $this->hostIndex = $hostIndex;
    }

    /**
     * Get the host index
     *
     * @return int Host index
     */
    public function getHostIndex()
    {
        return $this->hostIndex;
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Operation oauthToken
     *
     * @param  string $grant_type grant_type (required)
     * @param  string $client_id _Can be sent on the body OR on the Basic Auth_ (optional)
     * @param  string $client_secret _Can be sent on the body OR on the Basic Auth_ (optional)
     * @param  string $scope scope (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['oauthToken'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\AccessTokenResponse
     */
    public function oauthToken($grant_type, $client_id = null, $client_secret = null, $scope = null, string $contentType = self::contentTypes['oauthToken'][0])
    {
        list($response) = $this->oauthTokenWithHttpInfo($grant_type, $client_id, $client_secret, $scope, $contentType);
        return $response;
    }

    /**
     * Operation oauthTokenWithHttpInfo
     *
     * @param  string $grant_type (required)
     * @param  string $client_id _Can be sent on the body OR on the Basic Auth_ (optional)
     * @param  string $client_secret _Can be sent on the body OR on the Basic Auth_ (optional)
     * @param  string $scope (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['oauthToken'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\AccessTokenResponse, HTTP status code, HTTP response headers (array of strings)
     */
    public function oauthTokenWithHttpInfo($grant_type, $client_id = null, $client_secret = null, $scope = null, string $contentType = self::contentTypes['oauthToken'][0])
    {
        $request = $this->oauthTokenRequest($grant_type, $client_id, $client_secret, $scope, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            switch($statusCode) {
                case 200:
                    if ('\OpenAPI\Client\Model\AccessTokenResponse' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\OpenAPI\Client\Model\AccessTokenResponse' !== 'string') {
                            try {
                                $content = json_decode($content, false, 512, JSON_THROW_ON_ERROR);
                            } catch (\JsonException $exception) {
                                throw new ApiException(
                                    sprintf(
                                        'Error JSON decoding server response (%s)',
                                        $request->getUri()
                                    ),
                                    $statusCode,
                                    $response->getHeaders(),
                                    $content
                                );
                            }
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\OpenAPI\Client\Model\AccessTokenResponse', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
            }

            $returnType = '\OpenAPI\Client\Model\AccessTokenResponse';
            if ($returnType === '\SplFileObject') {
                $content = $response->getBody(); //stream goes to serializer
            } else {
                $content = (string) $response->getBody();
                if ($returnType !== 'string') {
                    try {
                        $content = json_decode($content, false, 512, JSON_THROW_ON_ERROR);
                    } catch (\JsonException $exception) {
                        throw new ApiException(
                            sprintf(
                                'Error JSON decoding server response (%s)',
                                $request->getUri()
                            ),
                            $statusCode,
                            $response->getHeaders(),
                            $content
                        );
                    }
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\AccessTokenResponse',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation oauthTokenAsync
     *
     * @param  string $grant_type (required)
     * @param  string $client_id _Can be sent on the body OR on the Basic Auth_ (optional)
     * @param  string $client_secret _Can be sent on the body OR on the Basic Auth_ (optional)
     * @param  string $scope (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['oauthToken'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function oauthTokenAsync($grant_type, $client_id = null, $client_secret = null, $scope = null, string $contentType = self::contentTypes['oauthToken'][0])
    {
        return $this->oauthTokenAsyncWithHttpInfo($grant_type, $client_id, $client_secret, $scope, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation oauthTokenAsyncWithHttpInfo
     *
     * @param  string $grant_type (required)
     * @param  string $client_id _Can be sent on the body OR on the Basic Auth_ (optional)
     * @param  string $client_secret _Can be sent on the body OR on the Basic Auth_ (optional)
     * @param  string $scope (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['oauthToken'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function oauthTokenAsyncWithHttpInfo($grant_type, $client_id = null, $client_secret = null, $scope = null, string $contentType = self::contentTypes['oauthToken'][0])
    {
        $returnType = '\OpenAPI\Client\Model\AccessTokenResponse';
        $request = $this->oauthTokenRequest($grant_type, $client_id, $client_secret, $scope, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'oauthToken'
     *
     * @param  string $grant_type (required)
     * @param  string $client_id _Can be sent on the body OR on the Basic Auth_ (optional)
     * @param  string $client_secret _Can be sent on the body OR on the Basic Auth_ (optional)
     * @param  string $scope (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['oauthToken'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function oauthTokenRequest($grant_type, $client_id = null, $client_secret = null, $scope = null, string $contentType = self::contentTypes['oauthToken'][0])
    {

        // verify the required parameter 'grant_type' is set
        if ($grant_type === null || (is_array($grant_type) && count($grant_type) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $grant_type when calling oauthToken'
            );
        }





        $resourcePath = '/v1/oauth2/token';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;




        // form params
        if ($grant_type !== null) {
            $formParams['grant_type'] = ObjectSerializer::toFormValue($grant_type);
        }
        // form params
        if ($client_id !== null) {
            $formParams['client_id'] = ObjectSerializer::toFormValue($client_id);
        }
        // form params
        if ($client_secret !== null) {
            $formParams['client_secret'] = ObjectSerializer::toFormValue($client_secret);
        }
        // form params
        if ($scope !== null) {
            $formParams['scope'] = ObjectSerializer::toFormValue($scope);
        }

        $headers = $this->headerSelector->selectHeaders(
            ['application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires HTTP basic authentication
        if (!empty($this->config->getUsername()) || !(empty($this->config->getPassword()))) {
            $headers['Authorization'] = 'Basic ' . base64_encode($this->config->getUsername() . ":" . $this->config->getPassword());
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'POST',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation token
     *
     * @param  \OpenAPI\Client\Model\TokenRequestDto $token_request_dto token_request_dto (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['token'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\AccessTokenResponse
     */
    public function token($token_request_dto, string $contentType = self::contentTypes['token'][0])
    {
        list($response) = $this->tokenWithHttpInfo($token_request_dto, $contentType);
        return $response;
    }

    /**
     * Operation tokenWithHttpInfo
     *
     * @param  \OpenAPI\Client\Model\TokenRequestDto $token_request_dto (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['token'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\AccessTokenResponse, HTTP status code, HTTP response headers (array of strings)
     */
    public function tokenWithHttpInfo($token_request_dto, string $contentType = self::contentTypes['token'][0])
    {
        $request = $this->tokenRequest($token_request_dto, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            switch($statusCode) {
                case 200:
                    if ('\OpenAPI\Client\Model\AccessTokenResponse' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\OpenAPI\Client\Model\AccessTokenResponse' !== 'string') {
                            try {
                                $content = json_decode($content, false, 512, JSON_THROW_ON_ERROR);
                            } catch (\JsonException $exception) {
                                throw new ApiException(
                                    sprintf(
                                        'Error JSON decoding server response (%s)',
                                        $request->getUri()
                                    ),
                                    $statusCode,
                                    $response->getHeaders(),
                                    $content
                                );
                            }
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\OpenAPI\Client\Model\AccessTokenResponse', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
            }

            $returnType = '\OpenAPI\Client\Model\AccessTokenResponse';
            if ($returnType === '\SplFileObject') {
                $content = $response->getBody(); //stream goes to serializer
            } else {
                $content = (string) $response->getBody();
                if ($returnType !== 'string') {
                    try {
                        $content = json_decode($content, false, 512, JSON_THROW_ON_ERROR);
                    } catch (\JsonException $exception) {
                        throw new ApiException(
                            sprintf(
                                'Error JSON decoding server response (%s)',
                                $request->getUri()
                            ),
                            $statusCode,
                            $response->getHeaders(),
                            $content
                        );
                    }
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\AccessTokenResponse',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation tokenAsync
     *
     * @param  \OpenAPI\Client\Model\TokenRequestDto $token_request_dto (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['token'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function tokenAsync($token_request_dto, string $contentType = self::contentTypes['token'][0])
    {
        return $this->tokenAsyncWithHttpInfo($token_request_dto, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation tokenAsyncWithHttpInfo
     *
     * @param  \OpenAPI\Client\Model\TokenRequestDto $token_request_dto (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['token'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function tokenAsyncWithHttpInfo($token_request_dto, string $contentType = self::contentTypes['token'][0])
    {
        $returnType = '\OpenAPI\Client\Model\AccessTokenResponse';
        $request = $this->tokenRequest($token_request_dto, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'token'
     *
     * @param  \OpenAPI\Client\Model\TokenRequestDto $token_request_dto (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['token'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function tokenRequest($token_request_dto, string $contentType = self::contentTypes['token'][0])
    {

        // verify the required parameter 'token_request_dto' is set
        if ($token_request_dto === null || (is_array($token_request_dto) && count($token_request_dto) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $token_request_dto when calling token'
            );
        }


        $resourcePath = '/v1/token';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;





        $headers = $this->headerSelector->selectHeaders(
            ['*/*', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (isset($token_request_dto)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($token_request_dto));
            } else {
                $httpBody = $token_request_dto;
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }


        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'POST',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Create http client option
     *
     * @throws \RuntimeException on file opening failure
     * @return array of http client options
     */
    protected function createHttpClientOption()
    {
        $options = [];
        if ($this->config->getDebug()) {
            $options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
            if (!$options[RequestOptions::DEBUG]) {
                throw new \RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
            }
        }

        return $options;
    }
}
