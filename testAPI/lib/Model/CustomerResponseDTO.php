<?php
/**
 * CustomerResponseDTO
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
 * CustomerResponseDTO Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class CustomerResponseDTO implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'CustomerResponseDTO';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'customer_id' => 'int',
        'activation_status' => 'string',
        'state_reason' => 'string',
        'customer_name' => 'string',
        'customer_legal_id' => 'string',
        'electronic_addresses' => '\OpenAPI\Client\Model\ElectronicAddressDTO[]',
        'customer_vat' => 'string',
        'customer_email' => 'string',
        'address_line1' => 'string',
        'address_line2' => 'string',
        'address_line3' => 'string',
        'postal_code' => 'string',
        'city' => 'string',
        'country' => 'string',
        'language' => 'string',
        'logo' => 'string',
        'activation_pa' => 'string',
        'reception_pa' => 'string',
        'start_date' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'customer_id' => 'int64',
        'activation_status' => null,
        'state_reason' => null,
        'customer_name' => null,
        'customer_legal_id' => null,
        'electronic_addresses' => null,
        'customer_vat' => null,
        'customer_email' => null,
        'address_line1' => null,
        'address_line2' => null,
        'address_line3' => null,
        'postal_code' => null,
        'city' => null,
        'country' => null,
        'language' => null,
        'logo' => null,
        'activation_pa' => null,
        'reception_pa' => null,
        'start_date' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'customer_id' => false,
        'activation_status' => false,
        'state_reason' => false,
        'customer_name' => false,
        'customer_legal_id' => false,
        'electronic_addresses' => false,
        'customer_vat' => false,
        'customer_email' => false,
        'address_line1' => false,
        'address_line2' => false,
        'address_line3' => false,
        'postal_code' => false,
        'city' => false,
        'country' => false,
        'language' => false,
        'logo' => false,
        'activation_pa' => false,
        'reception_pa' => false,
        'start_date' => false
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
        'customer_id' => 'customerId',
        'activation_status' => 'activationStatus',
        'state_reason' => 'stateReason',
        'customer_name' => 'customerName',
        'customer_legal_id' => 'customerLegalId',
        'electronic_addresses' => 'electronicAddresses',
        'customer_vat' => 'customerVat',
        'customer_email' => 'customerEmail',
        'address_line1' => 'addressLine1',
        'address_line2' => 'addressLine2',
        'address_line3' => 'addressLine3',
        'postal_code' => 'postalCode',
        'city' => 'city',
        'country' => 'country',
        'language' => 'language',
        'logo' => 'logo',
        'activation_pa' => 'activationPa',
        'reception_pa' => 'receptionPa',
        'start_date' => 'startDate'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'customer_id' => 'setCustomerId',
        'activation_status' => 'setActivationStatus',
        'state_reason' => 'setStateReason',
        'customer_name' => 'setCustomerName',
        'customer_legal_id' => 'setCustomerLegalId',
        'electronic_addresses' => 'setElectronicAddresses',
        'customer_vat' => 'setCustomerVat',
        'customer_email' => 'setCustomerEmail',
        'address_line1' => 'setAddressLine1',
        'address_line2' => 'setAddressLine2',
        'address_line3' => 'setAddressLine3',
        'postal_code' => 'setPostalCode',
        'city' => 'setCity',
        'country' => 'setCountry',
        'language' => 'setLanguage',
        'logo' => 'setLogo',
        'activation_pa' => 'setActivationPa',
        'reception_pa' => 'setReceptionPa',
        'start_date' => 'setStartDate'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'customer_id' => 'getCustomerId',
        'activation_status' => 'getActivationStatus',
        'state_reason' => 'getStateReason',
        'customer_name' => 'getCustomerName',
        'customer_legal_id' => 'getCustomerLegalId',
        'electronic_addresses' => 'getElectronicAddresses',
        'customer_vat' => 'getCustomerVat',
        'customer_email' => 'getCustomerEmail',
        'address_line1' => 'getAddressLine1',
        'address_line2' => 'getAddressLine2',
        'address_line3' => 'getAddressLine3',
        'postal_code' => 'getPostalCode',
        'city' => 'getCity',
        'country' => 'getCountry',
        'language' => 'getLanguage',
        'logo' => 'getLogo',
        'activation_pa' => 'getActivationPa',
        'reception_pa' => 'getReceptionPa',
        'start_date' => 'getStartDate'
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

    public const ACTIVATION_STATUS_ACTIVE = 'ACTIVE';
    public const ACTIVATION_STATUS_INACTIVE = 'INACTIVE';
    public const ACTIVATION_STATUS_WAITING_VALIDATION = 'WAITING_VALIDATION';
    public const ACTIVATION_STATUS_REJECTED = 'REJECTED';
    public const ACTIVATION_PA_Y = 'Y';
    public const ACTIVATION_PA_N = 'N';
    public const RECEPTION_PA_Y = 'Y';
    public const RECEPTION_PA_N = 'N';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getActivationStatusAllowableValues()
    {
        return [
            self::ACTIVATION_STATUS_ACTIVE,
            self::ACTIVATION_STATUS_INACTIVE,
            self::ACTIVATION_STATUS_WAITING_VALIDATION,
            self::ACTIVATION_STATUS_REJECTED,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getActivationPaAllowableValues()
    {
        return [
            self::ACTIVATION_PA_Y,
            self::ACTIVATION_PA_N,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getReceptionPaAllowableValues()
    {
        return [
            self::RECEPTION_PA_Y,
            self::RECEPTION_PA_N,
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
        $this->setIfExists('customer_id', $data ?? [], null);
        $this->setIfExists('activation_status', $data ?? [], null);
        $this->setIfExists('state_reason', $data ?? [], null);
        $this->setIfExists('customer_name', $data ?? [], null);
        $this->setIfExists('customer_legal_id', $data ?? [], null);
        $this->setIfExists('electronic_addresses', $data ?? [], null);
        $this->setIfExists('customer_vat', $data ?? [], null);
        $this->setIfExists('customer_email', $data ?? [], null);
        $this->setIfExists('address_line1', $data ?? [], null);
        $this->setIfExists('address_line2', $data ?? [], null);
        $this->setIfExists('address_line3', $data ?? [], null);
        $this->setIfExists('postal_code', $data ?? [], null);
        $this->setIfExists('city', $data ?? [], null);
        $this->setIfExists('country', $data ?? [], null);
        $this->setIfExists('language', $data ?? [], null);
        $this->setIfExists('logo', $data ?? [], null);
        $this->setIfExists('activation_pa', $data ?? [], null);
        $this->setIfExists('reception_pa', $data ?? [], null);
        $this->setIfExists('start_date', $data ?? [], null);
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

        $allowedValues = $this->getActivationStatusAllowableValues();
        if (!is_null($this->container['activation_status']) && !in_array($this->container['activation_status'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'activation_status', must be one of '%s'",
                $this->container['activation_status'],
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getActivationPaAllowableValues();
        if (!is_null($this->container['activation_pa']) && !in_array($this->container['activation_pa'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'activation_pa', must be one of '%s'",
                $this->container['activation_pa'],
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getReceptionPaAllowableValues();
        if (!is_null($this->container['reception_pa']) && !in_array($this->container['reception_pa'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'reception_pa', must be one of '%s'",
                $this->container['reception_pa'],
                implode("', '", $allowedValues)
            );
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
     * Gets customer_id
     *
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->container['customer_id'];
    }

    /**
     * Sets customer_id
     *
     * @param int|null $customer_id customer_id
     *
     * @return self
     */
    public function setCustomerId($customer_id)
    {
        if (is_null($customer_id)) {
            throw new \InvalidArgumentException('non-nullable customer_id cannot be null');
        }
        $this->container['customer_id'] = $customer_id;

        return $this;
    }

    /**
     * Gets activation_status
     *
     * @return string|null
     */
    public function getActivationStatus()
    {
        return $this->container['activation_status'];
    }

    /**
     * Sets activation_status
     *
     * @param string|null $activation_status activation_status
     *
     * @return self
     */
    public function setActivationStatus($activation_status)
    {
        if (is_null($activation_status)) {
            throw new \InvalidArgumentException('non-nullable activation_status cannot be null');
        }
        $allowedValues = $this->getActivationStatusAllowableValues();
        if (!in_array($activation_status, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'activation_status', must be one of '%s'",
                    $activation_status,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['activation_status'] = $activation_status;

        return $this;
    }

    /**
     * Gets state_reason
     *
     * @return string|null
     */
    public function getStateReason()
    {
        return $this->container['state_reason'];
    }

    /**
     * Sets state_reason
     *
     * @param string|null $state_reason state_reason
     *
     * @return self
     */
    public function setStateReason($state_reason)
    {
        if (is_null($state_reason)) {
            throw new \InvalidArgumentException('non-nullable state_reason cannot be null');
        }
        $this->container['state_reason'] = $state_reason;

        return $this;
    }

    /**
     * Gets customer_name
     *
     * @return string|null
     */
    public function getCustomerName()
    {
        return $this->container['customer_name'];
    }

    /**
     * Sets customer_name
     *
     * @param string|null $customer_name customer_name
     *
     * @return self
     */
    public function setCustomerName($customer_name)
    {
        if (is_null($customer_name)) {
            throw new \InvalidArgumentException('non-nullable customer_name cannot be null');
        }
        $this->container['customer_name'] = $customer_name;

        return $this;
    }

    /**
     * Gets customer_legal_id
     *
     * @return string|null
     */
    public function getCustomerLegalId()
    {
        return $this->container['customer_legal_id'];
    }

    /**
     * Sets customer_legal_id
     *
     * @param string|null $customer_legal_id customer_legal_id
     *
     * @return self
     */
    public function setCustomerLegalId($customer_legal_id)
    {
        if (is_null($customer_legal_id)) {
            throw new \InvalidArgumentException('non-nullable customer_legal_id cannot be null');
        }
        $this->container['customer_legal_id'] = $customer_legal_id;

        return $this;
    }

    /**
     * Gets electronic_addresses
     *
     * @return \OpenAPI\Client\Model\ElectronicAddressDTO[]|null
     */
    public function getElectronicAddresses()
    {
        return $this->container['electronic_addresses'];
    }

    /**
     * Sets electronic_addresses
     *
     * @param \OpenAPI\Client\Model\ElectronicAddressDTO[]|null $electronic_addresses electronic_addresses
     *
     * @return self
     */
    public function setElectronicAddresses($electronic_addresses)
    {
        if (is_null($electronic_addresses)) {
            throw new \InvalidArgumentException('non-nullable electronic_addresses cannot be null');
        }
        $this->container['electronic_addresses'] = $electronic_addresses;

        return $this;
    }

    /**
     * Gets customer_vat
     *
     * @return string|null
     */
    public function getCustomerVat()
    {
        return $this->container['customer_vat'];
    }

    /**
     * Sets customer_vat
     *
     * @param string|null $customer_vat customer_vat
     *
     * @return self
     */
    public function setCustomerVat($customer_vat)
    {
        if (is_null($customer_vat)) {
            throw new \InvalidArgumentException('non-nullable customer_vat cannot be null');
        }
        $this->container['customer_vat'] = $customer_vat;

        return $this;
    }

    /**
     * Gets customer_email
     *
     * @return string|null
     */
    public function getCustomerEmail()
    {
        return $this->container['customer_email'];
    }

    /**
     * Sets customer_email
     *
     * @param string|null $customer_email customer_email
     *
     * @return self
     */
    public function setCustomerEmail($customer_email)
    {
        if (is_null($customer_email)) {
            throw new \InvalidArgumentException('non-nullable customer_email cannot be null');
        }
        $this->container['customer_email'] = $customer_email;

        return $this;
    }

    /**
     * Gets address_line1
     *
     * @return string|null
     */
    public function getAddressLine1()
    {
        return $this->container['address_line1'];
    }

    /**
     * Sets address_line1
     *
     * @param string|null $address_line1 address_line1
     *
     * @return self
     */
    public function setAddressLine1($address_line1)
    {
        if (is_null($address_line1)) {
            throw new \InvalidArgumentException('non-nullable address_line1 cannot be null');
        }
        $this->container['address_line1'] = $address_line1;

        return $this;
    }

    /**
     * Gets address_line2
     *
     * @return string|null
     */
    public function getAddressLine2()
    {
        return $this->container['address_line2'];
    }

    /**
     * Sets address_line2
     *
     * @param string|null $address_line2 address_line2
     *
     * @return self
     */
    public function setAddressLine2($address_line2)
    {
        if (is_null($address_line2)) {
            throw new \InvalidArgumentException('non-nullable address_line2 cannot be null');
        }
        $this->container['address_line2'] = $address_line2;

        return $this;
    }

    /**
     * Gets address_line3
     *
     * @return string|null
     */
    public function getAddressLine3()
    {
        return $this->container['address_line3'];
    }

    /**
     * Sets address_line3
     *
     * @param string|null $address_line3 address_line3
     *
     * @return self
     */
    public function setAddressLine3($address_line3)
    {
        if (is_null($address_line3)) {
            throw new \InvalidArgumentException('non-nullable address_line3 cannot be null');
        }
        $this->container['address_line3'] = $address_line3;

        return $this;
    }

    /**
     * Gets postal_code
     *
     * @return string|null
     */
    public function getPostalCode()
    {
        return $this->container['postal_code'];
    }

    /**
     * Sets postal_code
     *
     * @param string|null $postal_code postal_code
     *
     * @return self
     */
    public function setPostalCode($postal_code)
    {
        if (is_null($postal_code)) {
            throw new \InvalidArgumentException('non-nullable postal_code cannot be null');
        }
        $this->container['postal_code'] = $postal_code;

        return $this;
    }

    /**
     * Gets city
     *
     * @return string|null
     */
    public function getCity()
    {
        return $this->container['city'];
    }

    /**
     * Sets city
     *
     * @param string|null $city city
     *
     * @return self
     */
    public function setCity($city)
    {
        if (is_null($city)) {
            throw new \InvalidArgumentException('non-nullable city cannot be null');
        }
        $this->container['city'] = $city;

        return $this;
    }

    /**
     * Gets country
     *
     * @return string|null
     */
    public function getCountry()
    {
        return $this->container['country'];
    }

    /**
     * Sets country
     *
     * @param string|null $country country
     *
     * @return self
     */
    public function setCountry($country)
    {
        if (is_null($country)) {
            throw new \InvalidArgumentException('non-nullable country cannot be null');
        }
        $this->container['country'] = $country;

        return $this;
    }

    /**
     * Gets language
     *
     * @return string|null
     */
    public function getLanguage()
    {
        return $this->container['language'];
    }

    /**
     * Sets language
     *
     * @param string|null $language language
     *
     * @return self
     */
    public function setLanguage($language)
    {
        if (is_null($language)) {
            throw new \InvalidArgumentException('non-nullable language cannot be null');
        }
        $this->container['language'] = $language;

        return $this;
    }

    /**
     * Gets logo
     *
     * @return string|null
     */
    public function getLogo()
    {
        return $this->container['logo'];
    }

    /**
     * Sets logo
     *
     * @param string|null $logo logo
     *
     * @return self
     */
    public function setLogo($logo)
    {
        if (is_null($logo)) {
            throw new \InvalidArgumentException('non-nullable logo cannot be null');
        }
        $this->container['logo'] = $logo;

        return $this;
    }

    /**
     * Gets activation_pa
     *
     * @return string|null
     */
    public function getActivationPa()
    {
        return $this->container['activation_pa'];
    }

    /**
     * Sets activation_pa
     *
     * @param string|null $activation_pa activation_pa
     *
     * @return self
     */
    public function setActivationPa($activation_pa)
    {
        if (is_null($activation_pa)) {
            throw new \InvalidArgumentException('non-nullable activation_pa cannot be null');
        }
        $allowedValues = $this->getActivationPaAllowableValues();
        if (!in_array($activation_pa, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'activation_pa', must be one of '%s'",
                    $activation_pa,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['activation_pa'] = $activation_pa;

        return $this;
    }

    /**
     * Gets reception_pa
     *
     * @return string|null
     */
    public function getReceptionPa()
    {
        return $this->container['reception_pa'];
    }

    /**
     * Sets reception_pa
     *
     * @param string|null $reception_pa reception_pa
     *
     * @return self
     */
    public function setReceptionPa($reception_pa)
    {
        if (is_null($reception_pa)) {
            throw new \InvalidArgumentException('non-nullable reception_pa cannot be null');
        }
        $allowedValues = $this->getReceptionPaAllowableValues();
        if (!in_array($reception_pa, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'reception_pa', must be one of '%s'",
                    $reception_pa,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['reception_pa'] = $reception_pa;

        return $this;
    }

    /**
     * Gets start_date
     *
     * @return string|null
     */
    public function getStartDate()
    {
        return $this->container['start_date'];
    }

    /**
     * Sets start_date
     *
     * @param string|null $start_date start_date
     *
     * @return self
     */
    public function setStartDate($start_date)
    {
        if (is_null($start_date)) {
            throw new \InvalidArgumentException('non-nullable start_date cannot be null');
        }
        $this->container['start_date'] = $start_date;

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


