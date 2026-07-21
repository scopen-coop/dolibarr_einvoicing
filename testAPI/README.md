# OpenAPIClient-php


_Par Esalink (Plateforme Agréée)_

Each endpoint must be called with an access token (Bearer). This token is retrieved by a call to a token URL.

In order to avoid any firewall block, please add to you requests header the following value :

 > For __TEST__ environment
 >>- Key = `hubtimize-api-key`
 >>- Value = `a3e49892-260f-4a3f-b497-3c9b68ee85d1`
 
 > For __PROD__ environment
 >>- Key = `hubtimize-api-key`
 >>- Value = _to be requested before go live_

---
## Changelog

 >- `v1.2.7` July 2026 
 >>- __Change__ POST `/v1/oauth2/token` => __Breaking change__ in order to be compliant with oauth2 and RFC 6749 
 >>>- Actual way of working is __disabled__ (user query Parameters)
 >>>- Two ways of using this endpoint
 >>>>- Using `application/x-www-form-urlencoded` with values __client_id__, __client_secret__ and __grant_type=client_credentials__
 >>>>- HTTP Basic : using header `Authorization : Basic` with encoded credentials __Authorization : Basic base64(urlencode(client_id):urlencode(client_secret))__ and using `application/x-www-form-urlencoded` with value __grant_type=client_credentials__
 >- `v1.2.6` June 2026 
 >>- __Change__ PUT `/v1/customerPA/{customerID}` => email to receive documents to sign can be changed too (\"customerEmail\": \"xxx\")
 >>- __Add__ POST `/v1/customerPA/{customerId}/resend-kyc-onboarding-email` => Resend onboarding email 
 >>- __Add__ new identifiers status 
 >>>- __UPDATE__ : Identifier date change requested
 >>>- __WAITINGACTIVATE__ : identifier request at the customer creation, waiting the customer activation to be created
 >>>- __ERROR__ : Error happening during the automatic setup, require human attention, managed by Esalink
 >- `v1.2.5` April 2026 
 >>- __Add__ `/v1/oauth2/token` => standard oauth2 token api
 >>- __Change__ management of customer users
 >>>- `POST /v1/customerPA/{customerID}/user`
 >>>- `POST /v1/customerPA/{customerID}/user/search`
 >>>- `GET /v1/customerPA/{customerID}/user/{userId}`
 >>>- `PUT /v1/customerPA/{customerID}/user/{userId}`
 >>- __Add__ management of customer identifiers
 >>>- `POST /v1/customerPA/{customerID}/identifier`
 >>>- `POST /v1/customerPA/{customerID}/identifier/search`
 >>>- `GET /v1/customerPA/{customerID}/identifier/{identifierId}`
 >>>- `PUT /v1/customerPA/{customerID}/identifier/{identifierId}`
 >>>- `DELETE /v1/customerPA/{customerID}/identifier/{identifierId}`
>- `v1.2.4` March 2026
 >>- __Add__ optional field `dateStart` on `POST /v1/customerPA`
 >>- Field `electronicAddresses ` is now __optional__ on `POST /v1/customerPA`
 >>- __Update__ management of customer APIs
 >>>- `POST /v1/customerPA`
 >>>- `PUT /v1/customerPA/{customerID}`
 >>>- `GET /v1/customerPA/{customerID} `
 >>>- `POST /v1/search`
 
---
## CUSTOMER Api

 The __CustomerPA Service__ API allows you to:
 >- Manage your customers
 >- Manage your customer's users

A __customer__ is defined by

 >- an EDI Entity (for technical purpose), contains
 >>- A name (unique)
 >>- A parent entity
 >>- An email (required, to receive on boarding email)
 >>- A language (optional)
 >>- A logo (optional, by default the one from the parent will be used)
 >>- Identifiers list (optional, 0225 for France eInvoicing, 0088, 0002, 0208 for Belgium, etc.)
 >- an eInvoice Entity (for legal purpose), contains
 >>- An EDI entity
 >>- A legal entity name
 >>- Postal address
 >>- Legal identifier (SIREN, Company code, etc.)
 >>- VAT codes (optional)
 >>- Plateforme agrée service (send/receive eInvoiceFR)

 __Customer management__

 >- `POST /v1/customerPA` : Create a customer
 >- `POST /v1/customerPA/search` : Search a customer
 >- `GET /v1/customerPA/{customerId}` : Get a customer
 >- `PUT /v1/customerPA/{customerId}` : Update a customer
 
 A customer status can be
 >- ACTIVE : Validated by esalink and ready to be used (at least for sending, for receiving, need to be sure the related receiving identifier is \"Active\")
 >- INACTIVE : Disabled customer
 >- WAITING_VALIDATION : Customer waiting to be validated by Esalink
 >- REJECTED : Customer rejected by Esalink

 Create a customer without any identifier will send __an onboarding email for identity and company verification (KYC/KYB)__

 Create a customer with identifier will :
 >- __Create identifier request__ for each identifier request (based on send/receive option and start date selected)
 >- Send __an onboarding email for identity and company verification (KYC/KYB) and, if receiving is activated, register lines on directory__

 
 __Customer's API user management__
 >- `POST /v1/customerPA/{customerID}/user` : Create a customer's API user
 >- `GET /v1/customerPA/{customerID}/user` : List all customer's API user
 >- `GET /v1/customerPA/{customerID}/user/{userId}` : Get a specific customer's API user
 >- `PUT /v1/customerPA/{customerID}/user/{userId}` : Update a API customer
 >- `POST /v1/customerPA/{customerID}/user/{userId}/clientSecret` : generate a new client secret for this API customer

 When creating a customer's API user, __a clientId and a clientSecret__ are generated (they still can be used as username/password on the tokan API).


 A customer's API user status can be
 >- enabled : enable = true 
 >- disabled : enable = false

 __Customer's identifiers management__ (Identifier can be an identifier on administration french directory, an address on Peppol Network, etc.)
 >- `POST /v1/customerPA/{customerID}/identifier` : Request a new identifier for the customer
 >- `POST /v1/customerPA/{customerID}/identifier/search` : Search all identifiers for this customer
 >- `GET /v1/customerPA/{customerID}/identifier` : Get a specific identifier detail
 >- `PUT /v1/customerPA/{customerID}/identifier` : Update an identifier (only on Status NEW, REFUSED, DONE)
 >- `DELETE /v1/customerPA/{customerID}/identifier` : Remove an identifier (only for status NEW, REFUSED, DONE)
 
 A customer identifier can be
 >- NEW : New identifier request _(can be updated or deleted)_
 >- DELETE : Deleted identifiers
 >- IN_PROGRESS : Identifier setup currently in progress by Esalink _(identifier can't be updated or deleted)_
 >- UPDATE : Identifier date change requested
 >- WAITINGACTIVATE : identifier request at the customer creation, waiting the customer activation to be created
 >- WAITINGPEPPOL : Peppol realease ask to previous access point, waiting return _(identifier can't be updated or deleted)_
 >- WAITINGOPTIN : Docusign PDF (OPTIN) sent for new identifier, waiting customer signature _(identifier can't be updated or deleted)_
 >- REFUSED : Identifier request refused by Esalink (reason in comment)
 >- ERROR : Error happening during the automatic setup, require human attention, managed by Esalink
 >- _DONE_ This status is not returned but a calculated status is sent instead, based on activation date
 >>- Active : Identifier active on requested scope (Peppol only for Sending, AIFE Directory + Peppol for France receiving, etc.)
 >>- Inactive : Identifier reach it's end date
 >>- Upcoming : Setup ready, It'll be activated at the requested date
 
 All request will be validated __once a day__, if a \"register document\" signed by customer is needed, __PDF pre-filled document will be sent to the customer__

 ---
 
 # API technical documentation



## Installation & Usage

### Requirements

PHP 7.4 and later.
Should also work with PHP 8.0.

### Composer

To install the bindings via [Composer](https://getcomposer.org/), add the following to `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/GIT_USER_ID/GIT_REPO_ID.git"
    }
  ],
  "require": {
    "GIT_USER_ID/GIT_REPO_ID": "*@dev"
  }
}
```

Then run `composer install`

### Manual Installation

Download the files and include `autoload.php`:

```php
<?php
require_once('/path/to/OpenAPIClient-php/vendor/autoload.php');
```

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



// Configure HTTP basic authorization: basicAuth
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');


$apiInstance = new OpenAPI\Client\Api\APITokenApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$grant_type = 'grant_type_example'; // string
$client_id = 'client_id_example'; // string | _Can be sent on the body OR on the Basic Auth_
$client_secret = 'client_secret_example'; // string | _Can be sent on the body OR on the Basic Auth_
$scope = 'scope_example'; // string

try {
    $result = $apiInstance->oauthToken($grant_type, $client_id, $client_secret, $scope);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling APITokenApi->oauthToken: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://ppd.hubtimize.fr/api/orchestrator*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*APITokenApi* | [**oauthToken**](docs/Api/APITokenApi.md#oauthtoken) | **POST** /v1/oauth2/token | 
*APITokenApi* | [**token**](docs/Api/APITokenApi.md#token) | **POST** /v1/token | 
*CustomerPAApi* | [**createCustomer**](docs/Api/CustomerPAApi.md#createcustomer) | **POST** /v1/customerPA | Create a new customer.
*CustomerPAApi* | [**getAllCustomers**](docs/Api/CustomerPAApi.md#getallcustomers) | **POST** /v1/customerPA/search | Get all visible customers
*CustomerPAApi* | [**getCustomer**](docs/Api/CustomerPAApi.md#getcustomer) | **GET** /v1/customerPA/{customerId} | Get customer by ID
*CustomerPAApi* | [**resendKycOnboardingEmail**](docs/Api/CustomerPAApi.md#resendkyconboardingemail) | **POST** /v1/customerPA/{customerId}/resend-kyc-onboarding-email | Resend KYC onboarding email
*CustomerPAApi* | [**updateCustomer**](docs/Api/CustomerPAApi.md#updatecustomer) | **PUT** /v1/customerPA/{customerId} | Update customer information
*CustomerPAIdentifiersApi* | [**createCustomerPaIdentifier**](docs/Api/CustomerPAIdentifiersApi.md#createcustomerpaidentifier) | **POST** /v1/customerPA/{customerId}/identifier | Create a new Customer PA Identifier
*CustomerPAIdentifiersApi* | [**deleteIdentifier**](docs/Api/CustomerPAIdentifiersApi.md#deleteidentifier) | **DELETE** /v1/customerPA/{customerId}/identifier | Delete a Customer PA Identifier
*CustomerPAIdentifiersApi* | [**getAllIdentifiersForCustomer**](docs/Api/CustomerPAIdentifiersApi.md#getallidentifiersforcustomer) | **POST** /v1/customerPA/{customerId}/identifier/search | Search identifiers for a customer
*CustomerPAIdentifiersApi* | [**getCustomerPaIdentifier**](docs/Api/CustomerPAIdentifiersApi.md#getcustomerpaidentifier) | **GET** /v1/customerPA/{customerId}/identifier | Get a specific Customer PA Identifier
*CustomerPAIdentifiersApi* | [**updateIdentifier**](docs/Api/CustomerPAIdentifiersApi.md#updateidentifier) | **PUT** /v1/customerPA/{customerId}/identifier | Update a Customer PA Identifier
*CustomerPAUserApi* | [**createUserOd**](docs/Api/CustomerPAUserApi.md#createuserod) | **POST** /v1/customerPA/{customerId}/user | Create a new user for a specific customer.
*CustomerPAUserApi* | [**getUserOd**](docs/Api/CustomerPAUserApi.md#getuserod) | **GET** /v1/customerPA/{customerId}/user/{userId} | Retrieve an existing user for a specific customer.
*CustomerPAUserApi* | [**regenerateClientCredentials**](docs/Api/CustomerPAUserApi.md#regenerateclientcredentials) | **POST** /v1/customerPA/{customerId}/user/{userId}/clientSecret | Regenerate client secret for specified user.
*CustomerPAUserApi* | [**searchUsersOd**](docs/Api/CustomerPAUserApi.md#searchusersod) | **GET** /v1/customerPA/{customerId}/user | List all users for a specific customer.
*CustomerPAUserApi* | [**updateUserOd**](docs/Api/CustomerPAUserApi.md#updateuserod) | **PUT** /v1/customerPA/{customerId}/user/{userId} | Update an existing user for a specific customer.
*SupervisorApi* | [**getHealth**](docs/Api/SupervisorApi.md#gethealth) | **GET** /v1/healthcheck | Check whether the API service is up and running.

## Models

- [AccessTokenResponse](docs/Model/AccessTokenResponse.md)
- [CreateCustomerDTO](docs/Model/CreateCustomerDTO.md)
- [CustomerIdentifiersPageResponseDTO](docs/Model/CustomerIdentifiersPageResponseDTO.md)
- [CustomerPaIdentifierRequest](docs/Model/CustomerPaIdentifierRequest.md)
- [CustomerPaIdentifierResponse](docs/Model/CustomerPaIdentifierResponse.md)
- [CustomerPageResponseDTO](docs/Model/CustomerPageResponseDTO.md)
- [CustomerResponseDTO](docs/Model/CustomerResponseDTO.md)
- [EDIUserCreationResponseDTO](docs/Model/EDIUserCreationResponseDTO.md)
- [EDIUserOdUpdateDTO](docs/Model/EDIUserOdUpdateDTO.md)
- [ElectronicAddressDTO](docs/Model/ElectronicAddressDTO.md)
- [ErrorMessage](docs/Model/ErrorMessage.md)
- [ODUserCreateDTO](docs/Model/ODUserCreateDTO.md)
- [ODUserDetailsDTO](docs/Model/ODUserDetailsDTO.md)
- [TokenRequestDto](docs/Model/TokenRequestDto.md)
- [UpdateCustomerDTO](docs/Model/UpdateCustomerDTO.md)
- [UpdateCustomerPaIdentifierRequest](docs/Model/UpdateCustomerPaIdentifierRequest.md)

## Authorization

Authentication schemes defined for the API:
### basicAuth

- **Type**: HTTP basic authentication

## Tests

To run the tests, use:

```bash
composer install
vendor/bin/phpunit
```

## Author



## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `v1.2.7.2`
    - Generator version: `7.7.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
