<?php
/**
 * CustomerPaIdentifierRequest
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
 * CustomerPaIdentifierRequest Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class CustomerPaIdentifierRequest implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'CustomerPaIdentifierRequest';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'addressing_identifier' => 'string',
        'addressing_qualifier' => 'string',
        'identifier_type' => 'string',
        'start_date' => 'string',
        'end_date' => 'string',
        'comment' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'addressing_identifier' => null,
        'addressing_qualifier' => null,
        'identifier_type' => null,
        'start_date' => null,
        'end_date' => null,
        'comment' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'addressing_identifier' => false,
        'addressing_qualifier' => false,
        'identifier_type' => false,
        'start_date' => false,
        'end_date' => false,
        'comment' => false
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
        'addressing_identifier' => 'addressingIdentifier',
        'addressing_qualifier' => 'addressingQualifier',
        'identifier_type' => 'identifierType',
        'start_date' => 'startDate',
        'end_date' => 'endDate',
        'comment' => 'comment'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'addressing_identifier' => 'setAddressingIdentifier',
        'addressing_qualifier' => 'setAddressingQualifier',
        'identifier_type' => 'setIdentifierType',
        'start_date' => 'setStartDate',
        'end_date' => 'setEndDate',
        'comment' => 'setComment'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'addressing_identifier' => 'getAddressingIdentifier',
        'addressing_qualifier' => 'getAddressingQualifier',
        'identifier_type' => 'getIdentifierType',
        'start_date' => 'getStartDate',
        'end_date' => 'getEndDate',
        'comment' => 'getComment'
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

    public const IDENTIFIER_TYPE_SEND = 'SEND';
    public const IDENTIFIER_TYPE_RECEIVE = 'RECEIVE';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getIdentifierTypeAllowableValues()
    {
        return [
            self::IDENTIFIER_TYPE_SEND,
            self::IDENTIFIER_TYPE_RECEIVE,
        ];
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
        $this->setIfExists('addressing_identifier', $data ?? [], null);
        $this->setIfExists('addressing_qualifier', $data ?? [], null);
        $this->setIfExists('identifier_type', $data ?? [], null);
        $this->setIfExists('start_date', $data ?? [], null);
        $this->setIfExists('end_date', $data ?? [], null);
        $this->setIfExists('comment', $data ?? [], null);
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

        if ($this->container['addressing_identifier'] === null) {
            $invalidProperties[] = "'addressing_identifier' can't be null";
        }
        if ($this->container['addressing_qualifier'] === null) {
            $invalidProperties[] = "'addressing_qualifier' can't be null";
        }
        if ($this->container['identifier_type'] === null) {
            $invalidProperties[] = "'identifier_type' can't be null";
        }
        $allowedValues = $this->getIdentifierTypeAllowableValues();
        if (!is_null($this->container['identifier_type']) && !in_array($this->container['identifier_type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'identifier_type', must be one of '%s'",
                $this->container['identifier_type'],
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['start_date'] === null) {
            $invalidProperties[] = "'start_date' can't be null";
        }
        if (!preg_match("/^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-([0-9]{4})$/", $this->container['start_date'])) {
            $invalidProperties[] = "invalid value for 'start_date', must be conform to the pattern /^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-([0-9]{4})$/.";
        }

        if (!is_null($this->container['end_date']) && !preg_match("/^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-([0-9]{4})$/", $this->container['end_date'])) {
            $invalidProperties[] = "invalid value for 'end_date', must be conform to the pattern /^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-([0-9]{4})$/.";
        }

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
     * Gets addressing_identifier
     *
     * @return string
     */
    public function getAddressingIdentifier()
    {
        return $this->container['addressing_identifier'];
    }

    /**
     * Sets addressing_identifier
     *
     * @param string $addressing_identifier addressing_identifier
     *
     * @return self
     */
    public function setAddressingIdentifier($addressing_identifier)
    {
        if (is_null($addressing_identifier)) {
            throw new \InvalidArgumentException('non-nullable addressing_identifier cannot be null');
        }
        $this->container['addressing_identifier'] = $addressing_identifier;

        return $this;
    }

    /**
     * Gets addressing_qualifier
     *
     * @return string
     */
    public function getAddressingQualifier()
    {
        return $this->container['addressing_qualifier'];
    }

    /**
     * Sets addressing_qualifier
     *
     * @param string $addressing_qualifier addressing_qualifier
     *
     * @return self
     */
    public function setAddressingQualifier($addressing_qualifier)
    {
        if (is_null($addressing_qualifier)) {
            throw new \InvalidArgumentException('non-nullable addressing_qualifier cannot be null');
        }
        $this->container['addressing_qualifier'] = $addressing_qualifier;

        return $this;
    }

    /**
     * Gets identifier_type
     *
     * @return string
     */
    public function getIdentifierType()
    {
        return $this->container['identifier_type'];
    }

    /**
     * Sets identifier_type
     *
     * @param string $identifier_type identifier_type
     *
     * @return self
     */
    public function setIdentifierType($identifier_type)
    {
        if (is_null($identifier_type)) {
            throw new \InvalidArgumentException('non-nullable identifier_type cannot be null');
        }
        $allowedValues = $this->getIdentifierTypeAllowableValues();
        if (!in_array($identifier_type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'identifier_type', must be one of '%s'",
                    $identifier_type,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['identifier_type'] = $identifier_type;

        return $this;
    }

    /**
     * Gets start_date
     *
     * @return string
     */
    public function getStartDate()
    {
        return $this->container['start_date'];
    }

    /**
     * Sets start_date
     *
     * @param string $start_date Start date in DD-MM-YYYY format, min 10 days in the future
     *
     * @return self
     */
    public function setStartDate($start_date)
    {
        if (is_null($start_date)) {
            throw new \InvalidArgumentException('non-nullable start_date cannot be null');
        }

        if ((!preg_match("/^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-([0-9]{4})$/", ObjectSerializer::toString($start_date)))) {
            throw new \InvalidArgumentException("invalid value for \$start_date when calling CustomerPaIdentifierRequest., must conform to the pattern /^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-([0-9]{4})$/.");
        }

        $this->container['start_date'] = $start_date;

        return $this;
    }

    /**
     * Gets end_date
     *
     * @return string|null
     */
    public function getEndDate()
    {
        return $this->container['end_date'];
    }

    /**
     * Sets end_date
     *
     * @param string|null $end_date Optional end date in DD-MM-YYYY format
     *
     * @return self
     */
    public function setEndDate($end_date)
    {
        if (is_null($end_date)) {
            throw new \InvalidArgumentException('non-nullable end_date cannot be null');
        }

        if ((!preg_match("/^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-([0-9]{4})$/", ObjectSerializer::toString($end_date)))) {
            throw new \InvalidArgumentException("invalid value for \$end_date when calling CustomerPaIdentifierRequest., must conform to the pattern /^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-([0-9]{4})$/.");
        }

        $this->container['end_date'] = $end_date;

        return $this;
    }

    /**
     * Gets comment
     *
     * @return string|null
     */
    public function getComment()
    {
        return $this->container['comment'];
    }

    /**
     * Sets comment
     *
     * @param string|null $comment comment
     *
     * @return self
     */
    public function setComment($comment)
    {
        if (is_null($comment)) {
            throw new \InvalidArgumentException('non-nullable comment cannot be null');
        }
        $this->container['comment'] = $comment;

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


