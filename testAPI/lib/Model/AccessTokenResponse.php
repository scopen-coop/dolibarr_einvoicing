<?php
/**
 * AccessTokenResponse
 *
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

namespace OpenAPI\Client\Model;

use \ArrayAccess;
use \OpenAPI\Client\ObjectSerializer;

/**
 * AccessTokenResponse Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class AccessTokenResponse implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'AccessTokenResponse';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'access_token' => 'string',
        'expires_in' => 'int',
        'refresh_expires_in' => 'int',
        'refresh_token' => 'string',
        'token_type' => 'string',
        'id_token' => 'string',
        'not_before_policy' => 'int',
        'session_state' => 'string',
        'scope' => 'string',
        'error' => 'string',
        'error_description' => 'string',
        'error_uri' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'access_token' => null,
        'expires_in' => 'int64',
        'refresh_expires_in' => 'int64',
        'refresh_token' => null,
        'token_type' => null,
        'id_token' => null,
        'not_before_policy' => 'int32',
        'session_state' => null,
        'scope' => null,
        'error' => null,
        'error_description' => null,
        'error_uri' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'access_token' => false,
        'expires_in' => false,
        'refresh_expires_in' => false,
        'refresh_token' => false,
        'token_type' => false,
        'id_token' => false,
        'not_before_policy' => false,
        'session_state' => false,
        'scope' => false,
        'error' => false,
        'error_description' => false,
        'error_uri' => false
    ];

    /**
      * If a nullable field gets set to null, insert it here
      *
      * @var boolean[]
      */
    protected array $openAPINullablesSetToNull = [];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of nullable properties
     *
     * @return array
     */
    protected static function openAPINullables(): array
    {
        return self::$openAPINullables;
    }

    /**
     * Array of nullable field names deliberately set to null
     *
     * @return boolean[]
     */
    private function getOpenAPINullablesSetToNull(): array
    {
        return $this->openAPINullablesSetToNull;
    }

    /**
     * Setter - Array of nullable field names deliberately set to null
     *
     * @param boolean[] $openAPINullablesSetToNull
     */
    private function setOpenAPINullablesSetToNull(array $openAPINullablesSetToNull): void
    {
        $this->openAPINullablesSetToNull = $openAPINullablesSetToNull;
    }

    /**
     * Checks if a property is nullable
     *
     * @param string $property
     * @return bool
     */
    public static function isNullable(string $property): bool
    {
        return self::openAPINullables()[$property] ?? false;
    }

    /**
     * Checks if a nullable property is set to null.
     *
     * @param string $property
     * @return bool
     */
    public function isNullableSetToNull(string $property): bool
    {
        return in_array($property, $this->getOpenAPINullablesSetToNull(), true);
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'access_token' => 'access_token',
        'expires_in' => 'expires_in',
        'refresh_expires_in' => 'refresh_expires_in',
        'refresh_token' => 'refresh_token',
        'token_type' => 'token_type',
        'id_token' => 'id_token',
        'not_before_policy' => 'not-before-policy',
        'session_state' => 'session_state',
        'scope' => 'scope',
        'error' => 'error',
        'error_description' => 'error_description',
        'error_uri' => 'error_uri'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'access_token' => 'setAccessToken',
        'expires_in' => 'setExpiresIn',
        'refresh_expires_in' => 'setRefreshExpiresIn',
        'refresh_token' => 'setRefreshToken',
        'token_type' => 'setTokenType',
        'id_token' => 'setIdToken',
        'not_before_policy' => 'setNotBeforePolicy',
        'session_state' => 'setSessionState',
        'scope' => 'setScope',
        'error' => 'setError',
        'error_description' => 'setErrorDescription',
        'error_uri' => 'setErrorUri'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'access_token' => 'getAccessToken',
        'expires_in' => 'getExpiresIn',
        'refresh_expires_in' => 'getRefreshExpiresIn',
        'refresh_token' => 'getRefreshToken',
        'token_type' => 'getTokenType',
        'id_token' => 'getIdToken',
        'not_before_policy' => 'getNotBeforePolicy',
        'session_state' => 'getSessionState',
        'scope' => 'getScope',
        'error' => 'getError',
        'error_description' => 'getErrorDescription',
        'error_uri' => 'getErrorUri'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$openAPIModelName;
    }


    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->setIfExists('access_token', $data ?? [], null);
        $this->setIfExists('expires_in', $data ?? [], null);
        $this->setIfExists('refresh_expires_in', $data ?? [], null);
        $this->setIfExists('refresh_token', $data ?? [], null);
        $this->setIfExists('token_type', $data ?? [], null);
        $this->setIfExists('id_token', $data ?? [], null);
        $this->setIfExists('not_before_policy', $data ?? [], null);
        $this->setIfExists('session_state', $data ?? [], null);
        $this->setIfExists('scope', $data ?? [], null);
        $this->setIfExists('error', $data ?? [], null);
        $this->setIfExists('error_description', $data ?? [], null);
        $this->setIfExists('error_uri', $data ?? [], null);
    }

    /**
    * Sets $this->container[$variableName] to the given data or to the given default Value; if $variableName
    * is nullable and its value is set to null in the $fields array, then mark it as "set to null" in the
    * $this->openAPINullablesSetToNull array
    *
    * @param string $variableName
    * @param array  $fields
    * @param mixed  $defaultValue
    */
    private function setIfExists(string $variableName, array $fields, $defaultValue): void
    {
        if (self::isNullable($variableName) && array_key_exists($variableName, $fields) && is_null($fields[$variableName])) {
            $this->openAPINullablesSetToNull[] = $variableName;
        }

        $this->container[$variableName] = $fields[$variableName] ?? $defaultValue;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets access_token
     *
     * @return string|null
     */
    public function getAccessToken()
    {
        return $this->container['access_token'];
    }

    /**
     * Sets access_token
     *
     * @param string|null $access_token access_token
     *
     * @return self
     */
    public function setAccessToken($access_token)
    {
        if (is_null($access_token)) {
            throw new \InvalidArgumentException('non-nullable access_token cannot be null');
        }
        $this->container['access_token'] = $access_token;

        return $this;
    }

    /**
     * Gets expires_in
     *
     * @return int|null
     */
    public function getExpiresIn()
    {
        return $this->container['expires_in'];
    }

    /**
     * Sets expires_in
     *
     * @param int|null $expires_in expires_in
     *
     * @return self
     */
    public function setExpiresIn($expires_in)
    {
        if (is_null($expires_in)) {
            throw new \InvalidArgumentException('non-nullable expires_in cannot be null');
        }
        $this->container['expires_in'] = $expires_in;

        return $this;
    }

    /**
     * Gets refresh_expires_in
     *
     * @return int|null
     */
    public function getRefreshExpiresIn()
    {
        return $this->container['refresh_expires_in'];
    }

    /**
     * Sets refresh_expires_in
     *
     * @param int|null $refresh_expires_in refresh_expires_in
     *
     * @return self
     */
    public function setRefreshExpiresIn($refresh_expires_in)
    {
        if (is_null($refresh_expires_in)) {
            throw new \InvalidArgumentException('non-nullable refresh_expires_in cannot be null');
        }
        $this->container['refresh_expires_in'] = $refresh_expires_in;

        return $this;
    }

    /**
     * Gets refresh_token
     *
     * @return string|null
     */
    public function getRefreshToken()
    {
        return $this->container['refresh_token'];
    }

    /**
     * Sets refresh_token
     *
     * @param string|null $refresh_token refresh_token
     *
     * @return self
     */
    public function setRefreshToken($refresh_token)
    {
        if (is_null($refresh_token)) {
            throw new \InvalidArgumentException('non-nullable refresh_token cannot be null');
        }
        $this->container['refresh_token'] = $refresh_token;

        return $this;
    }

    /**
     * Gets token_type
     *
     * @return string|null
     */
    public function getTokenType()
    {
        return $this->container['token_type'];
    }

    /**
     * Sets token_type
     *
     * @param string|null $token_type token_type
     *
     * @return self
     */
    public function setTokenType($token_type)
    {
        if (is_null($token_type)) {
            throw new \InvalidArgumentException('non-nullable token_type cannot be null');
        }
        $this->container['token_type'] = $token_type;

        return $this;
    }

    /**
     * Gets id_token
     *
     * @return string|null
     */
    public function getIdToken()
    {
        return $this->container['id_token'];
    }

    /**
     * Sets id_token
     *
     * @param string|null $id_token id_token
     *
     * @return self
     */
    public function setIdToken($id_token)
    {
        if (is_null($id_token)) {
            throw new \InvalidArgumentException('non-nullable id_token cannot be null');
        }
        $this->container['id_token'] = $id_token;

        return $this;
    }

    /**
     * Gets not_before_policy
     *
     * @return int|null
     */
    public function getNotBeforePolicy()
    {
        return $this->container['not_before_policy'];
    }

    /**
     * Sets not_before_policy
     *
     * @param int|null $not_before_policy not_before_policy
     *
     * @return self
     */
    public function setNotBeforePolicy($not_before_policy)
    {
        if (is_null($not_before_policy)) {
            throw new \InvalidArgumentException('non-nullable not_before_policy cannot be null');
        }
        $this->container['not_before_policy'] = $not_before_policy;

        return $this;
    }

    /**
     * Gets session_state
     *
     * @return string|null
     */
    public function getSessionState()
    {
        return $this->container['session_state'];
    }

    /**
     * Sets session_state
     *
     * @param string|null $session_state session_state
     *
     * @return self
     */
    public function setSessionState($session_state)
    {
        if (is_null($session_state)) {
            throw new \InvalidArgumentException('non-nullable session_state cannot be null');
        }
        $this->container['session_state'] = $session_state;

        return $this;
    }

    /**
     * Gets scope
     *
     * @return string|null
     */
    public function getScope()
    {
        return $this->container['scope'];
    }

    /**
     * Sets scope
     *
     * @param string|null $scope scope
     *
     * @return self
     */
    public function setScope($scope)
    {
        if (is_null($scope)) {
            throw new \InvalidArgumentException('non-nullable scope cannot be null');
        }
        $this->container['scope'] = $scope;

        return $this;
    }

    /**
     * Gets error
     *
     * @return string|null
     */
    public function getError()
    {
        return $this->container['error'];
    }

    /**
     * Sets error
     *
     * @param string|null $error error
     *
     * @return self
     */
    public function setError($error)
    {
        if (is_null($error)) {
            throw new \InvalidArgumentException('non-nullable error cannot be null');
        }
        $this->container['error'] = $error;

        return $this;
    }

    /**
     * Gets error_description
     *
     * @return string|null
     */
    public function getErrorDescription()
    {
        return $this->container['error_description'];
    }

    /**
     * Sets error_description
     *
     * @param string|null $error_description error_description
     *
     * @return self
     */
    public function setErrorDescription($error_description)
    {
        if (is_null($error_description)) {
            throw new \InvalidArgumentException('non-nullable error_description cannot be null');
        }
        $this->container['error_description'] = $error_description;

        return $this;
    }

    /**
     * Gets error_uri
     *
     * @return string|null
     */
    public function getErrorUri()
    {
        return $this->container['error_uri'];
    }

    /**
     * Sets error_uri
     *
     * @param string|null $error_uri error_uri
     *
     * @return self
     */
    public function setErrorUri($error_uri)
    {
        if (is_null($error_uri)) {
            throw new \InvalidArgumentException('non-nullable error_uri cannot be null');
        }
        $this->container['error_uri'] = $error_uri;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed|null
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }

    /**
     * Sets value based on offset.
     *
     * @param int|null $offset Offset
     * @param mixed    $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }

    /**
     * Serializes the object to a value that can be serialized natively by json_encode().
     * @link https://www.php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed Returns data which can be serialized by json_encode(), which is a value
     * of any type other than a resource.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
       return ObjectSerializer::sanitizeForSerialization($this);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            ObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }

    /**
     * Gets a header-safe presentation of the object
     *
     * @return string
     */
    public function toHeaderValue()
    {
        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}


