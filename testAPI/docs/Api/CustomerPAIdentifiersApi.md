# OpenAPI\Client\CustomerPAIdentifiersApi

All URIs are relative to https://ppd.hubtimize.fr/api/orchestrator, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**createCustomerPaIdentifier()**](CustomerPAIdentifiersApi.md#createCustomerPaIdentifier) | **POST** /v1/customerPA/{customerId}/identifier | Create a new Customer PA Identifier |
| [**deleteIdentifier()**](CustomerPAIdentifiersApi.md#deleteIdentifier) | **DELETE** /v1/customerPA/{customerId}/identifier | Delete a Customer PA Identifier |
| [**getAllIdentifiersForCustomer()**](CustomerPAIdentifiersApi.md#getAllIdentifiersForCustomer) | **POST** /v1/customerPA/{customerId}/identifier/search | Search identifiers for a customer |
| [**getCustomerPaIdentifier()**](CustomerPAIdentifiersApi.md#getCustomerPaIdentifier) | **GET** /v1/customerPA/{customerId}/identifier | Get a specific Customer PA Identifier |
| [**updateIdentifier()**](CustomerPAIdentifiersApi.md#updateIdentifier) | **PUT** /v1/customerPA/{customerId}/identifier | Update a Customer PA Identifier |


## `createCustomerPaIdentifier()`

```php
createCustomerPaIdentifier($customer_id, $customer_pa_identifier_request): \OpenAPI\Client\Model\CustomerPaIdentifierResponse
```

Create a new Customer PA Identifier

Creates a new identifier for the given customer. The request body must contain all required information. Returns the created identifier object.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CustomerPAIdentifiersApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$customer_id = 56; // int
$customer_pa_identifier_request = new \OpenAPI\Client\Model\CustomerPaIdentifierRequest(); // \OpenAPI\Client\Model\CustomerPaIdentifierRequest

try {
    $result = $apiInstance->createCustomerPaIdentifier($customer_id, $customer_pa_identifier_request);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CustomerPAIdentifiersApi->createCustomerPaIdentifier: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **customer_id** | **int**|  | |
| **customer_pa_identifier_request** | [**\OpenAPI\Client\Model\CustomerPaIdentifierRequest**](../Model/CustomerPaIdentifierRequest.md)|  | |

### Return type

[**\OpenAPI\Client\Model\CustomerPaIdentifierResponse**](../Model/CustomerPaIdentifierResponse.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `deleteIdentifier()`

```php
deleteIdentifier($customer_id, $identifier, $qualifier): object
```

Delete a Customer PA Identifier

Deletes a specific identifier for the given customer. Rules: - Only allowed if identifier status is NEW, REFUSED, or DONE. - Only allowed if startDate > today + 5 days. - The status will be updated to DELETE instead of hard deletion.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CustomerPAIdentifiersApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$customer_id = 56; // int
$identifier = 'identifier_example'; // string
$qualifier = 'qualifier_example'; // string

try {
    $result = $apiInstance->deleteIdentifier($customer_id, $identifier, $qualifier);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CustomerPAIdentifiersApi->deleteIdentifier: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **customer_id** | **int**|  | |
| **identifier** | **string**|  | |
| **qualifier** | **string**|  | |

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

## `getAllIdentifiersForCustomer()`

```php
getAllIdentifiersForCustomer($customer_id, $offset, $limit): \OpenAPI\Client\Model\CustomerIdentifiersPageResponseDTO
```

Search identifiers for a customer

Retrieves a paginated list of identifiers for a customer.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CustomerPAIdentifiersApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$customer_id = 56; // int
$offset = 0; // int
$limit = 25; // int

try {
    $result = $apiInstance->getAllIdentifiersForCustomer($customer_id, $offset, $limit);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CustomerPAIdentifiersApi->getAllIdentifiersForCustomer: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **customer_id** | **int**|  | |
| **offset** | **int**|  | [optional] [default to 0] |
| **limit** | **int**|  | [optional] [default to 25] |

### Return type

[**\OpenAPI\Client\Model\CustomerIdentifiersPageResponseDTO**](../Model/CustomerIdentifiersPageResponseDTO.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getCustomerPaIdentifier()`

```php
getCustomerPaIdentifier($customer_id, $identifier, $qualifier): \OpenAPI\Client\Model\CustomerPaIdentifierResponse
```

Get a specific Customer PA Identifier

Retrieves a specific identifier for the given customer.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CustomerPAIdentifiersApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$customer_id = 56; // int
$identifier = 'identifier_example'; // string
$qualifier = 'qualifier_example'; // string

try {
    $result = $apiInstance->getCustomerPaIdentifier($customer_id, $identifier, $qualifier);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CustomerPAIdentifiersApi->getCustomerPaIdentifier: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **customer_id** | **int**|  | |
| **identifier** | **string**|  | |
| **qualifier** | **string**|  | |

### Return type

[**\OpenAPI\Client\Model\CustomerPaIdentifierResponse**](../Model/CustomerPaIdentifierResponse.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `updateIdentifier()`

```php
updateIdentifier($customer_id, $identifier, $qualifier, $update_customer_pa_identifier_request): \OpenAPI\Client\Model\CustomerPaIdentifierResponse
```

Update a Customer PA Identifier

Updates an existing identifier. Rules: only NEW/REFUSED/DONE allowed, startDate cannot change if active, startDate <= endDate. Status will be reset to NEW.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CustomerPAIdentifiersApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$customer_id = 56; // int
$identifier = 'identifier_example'; // string
$qualifier = 'qualifier_example'; // string
$update_customer_pa_identifier_request = new \OpenAPI\Client\Model\UpdateCustomerPaIdentifierRequest(); // \OpenAPI\Client\Model\UpdateCustomerPaIdentifierRequest

try {
    $result = $apiInstance->updateIdentifier($customer_id, $identifier, $qualifier, $update_customer_pa_identifier_request);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CustomerPAIdentifiersApi->updateIdentifier: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **customer_id** | **int**|  | |
| **identifier** | **string**|  | |
| **qualifier** | **string**|  | |
| **update_customer_pa_identifier_request** | [**\OpenAPI\Client\Model\UpdateCustomerPaIdentifierRequest**](../Model/UpdateCustomerPaIdentifierRequest.md)|  | |

### Return type

[**\OpenAPI\Client\Model\CustomerPaIdentifierResponse**](../Model/CustomerPaIdentifierResponse.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
