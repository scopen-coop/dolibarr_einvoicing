# OpenAPI\Client\CustomerPAApi

All URIs are relative to https://ppd.hubtimize.fr/api/orchestrator, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**createCustomer()**](CustomerPAApi.md#createCustomer) | **POST** /v1/customerPA | Create a new customer. |
| [**getAllCustomers()**](CustomerPAApi.md#getAllCustomers) | **POST** /v1/customerPA/search | Get all visible customers |
| [**getCustomer()**](CustomerPAApi.md#getCustomer) | **GET** /v1/customerPA/{customerId} | Get customer by ID |
| [**resendKycOnboardingEmail()**](CustomerPAApi.md#resendKycOnboardingEmail) | **POST** /v1/customerPA/{customerId}/resend-kyc-onboarding-email | Resend KYC onboarding email |
| [**updateCustomer()**](CustomerPAApi.md#updateCustomer) | **PUT** /v1/customerPA/{customerId} | Update customer information |


## `createCustomer()`

```php
createCustomer($create_customer_dto): string
```

Create a new customer.

Creates a new customer in the system using the provided details. The request body must contain all required information to create the customer. Returns the created customer object.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CustomerPAApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$create_customer_dto = new \OpenAPI\Client\Model\CreateCustomerDTO(); // \OpenAPI\Client\Model\CreateCustomerDTO

try {
    $result = $apiInstance->createCustomer($create_customer_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CustomerPAApi->createCustomer: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **create_customer_dto** | [**\OpenAPI\Client\Model\CreateCustomerDTO**](../Model/CreateCustomerDTO.md)|  | |

### Return type

**string**

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getAllCustomers()`

```php
getAllCustomers($limit, $offset): \OpenAPI\Client\Model\CustomerPageResponseDTO[]
```

Get all visible customers

Retrieves a list of all visible customers.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CustomerPAApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$limit = 25; // int
$offset = 0; // int

try {
    $result = $apiInstance->getAllCustomers($limit, $offset);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CustomerPAApi->getAllCustomers: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **limit** | **int**|  | [optional] [default to 25] |
| **offset** | **int**|  | [optional] [default to 0] |

### Return type

[**\OpenAPI\Client\Model\CustomerPageResponseDTO[]**](../Model/CustomerPageResponseDTO.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getCustomer()`

```php
getCustomer($customer_id): \OpenAPI\Client\Model\CustomerResponseDTO
```

Get customer by ID

Retrieves a customer using its unique identifier.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CustomerPAApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$customer_id = 12345; // int | Unique identifier of the customer.

try {
    $result = $apiInstance->getCustomer($customer_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CustomerPAApi->getCustomer: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **customer_id** | **int**| Unique identifier of the customer. | |

### Return type

[**\OpenAPI\Client\Model\CustomerResponseDTO**](../Model/CustomerResponseDTO.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `resendKycOnboardingEmail()`

```php
resendKycOnboardingEmail($customer_id): object
```

Resend KYC onboarding email

Resends the KYC onboarding email for the specified EDI entity.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CustomerPAApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$customer_id = 56; // int

try {
    $result = $apiInstance->resendKycOnboardingEmail($customer_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CustomerPAApi->resendKycOnboardingEmail: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **customer_id** | **int**|  | |

### Return type

**object**

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `*/*`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `updateCustomer()`

```php
updateCustomer($customer_id, $update_customer_dto): \OpenAPI\Client\Model\CustomerResponseDTO
```

Update customer information

Updates an existing customer using the provided data.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CustomerPAApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$customer_id = 12345; // int | Unique identifier of the customer.
$update_customer_dto = new \OpenAPI\Client\Model\UpdateCustomerDTO(); // \OpenAPI\Client\Model\UpdateCustomerDTO

try {
    $result = $apiInstance->updateCustomer($customer_id, $update_customer_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CustomerPAApi->updateCustomer: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **customer_id** | **int**| Unique identifier of the customer. | |
| **update_customer_dto** | [**\OpenAPI\Client\Model\UpdateCustomerDTO**](../Model/UpdateCustomerDTO.md)|  | |

### Return type

[**\OpenAPI\Client\Model\CustomerResponseDTO**](../Model/CustomerResponseDTO.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
