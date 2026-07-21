# OpenAPI\Client\CustomerPAUserApi

All URIs are relative to https://ppd.hubtimize.fr/api/orchestrator, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**createUserOd()**](CustomerPAUserApi.md#createUserOd) | **POST** /v1/customerPA/{customerId}/user | Create a new user for a specific customer. |
| [**getUserOd()**](CustomerPAUserApi.md#getUserOd) | **GET** /v1/customerPA/{customerId}/user/{userId} | Retrieve an existing user for a specific customer. |
| [**regenerateClientCredentials()**](CustomerPAUserApi.md#regenerateClientCredentials) | **POST** /v1/customerPA/{customerId}/user/{userId}/clientSecret | Regenerate client secret for specified user. |
| [**searchUsersOd()**](CustomerPAUserApi.md#searchUsersOd) | **GET** /v1/customerPA/{customerId}/user | List all users for a specific customer. |
| [**updateUserOd()**](CustomerPAUserApi.md#updateUserOd) | **PUT** /v1/customerPA/{customerId}/user/{userId} | Update an existing user for a specific customer. |


## `createUserOd()`

```php
createUserOd($customer_id, $od_user_create_dto): \OpenAPI\Client\Model\EDIUserCreationResponseDTO
```

Create a new user for a specific customer.

Creates a new user associated with a given customer

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CustomerPAUserApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$customer_id = 13123155; // int | Unique identifier of the customer.
$od_user_create_dto = new \OpenAPI\Client\Model\ODUserCreateDTO(); // \OpenAPI\Client\Model\ODUserCreateDTO

try {
    $result = $apiInstance->createUserOd($customer_id, $od_user_create_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CustomerPAUserApi->createUserOd: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **customer_id** | **int**| Unique identifier of the customer. | |
| **od_user_create_dto** | [**\OpenAPI\Client\Model\ODUserCreateDTO**](../Model/ODUserCreateDTO.md)|  | |

### Return type

[**\OpenAPI\Client\Model\EDIUserCreationResponseDTO**](../Model/EDIUserCreationResponseDTO.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getUserOd()`

```php
getUserOd($customer_id, $user_id): \OpenAPI\Client\Model\ODUserDetailsDTO
```

Retrieve an existing user for a specific customer.

Fetches detailed information about a specific user associated with a given customer.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CustomerPAUserApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$customer_id = 13123155; // int | Unique identifier of the customer.
$user_id = 550e8400-e29b-41d4-a716-446655440000; // string | Unique identifier of the user to retrieve.

try {
    $result = $apiInstance->getUserOd($customer_id, $user_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CustomerPAUserApi->getUserOd: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **customer_id** | **int**| Unique identifier of the customer. | |
| **user_id** | **string**| Unique identifier of the user to retrieve. | |

### Return type

[**\OpenAPI\Client\Model\ODUserDetailsDTO**](../Model/ODUserDetailsDTO.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `regenerateClientCredentials()`

```php
regenerateClientCredentials($customer_id, $user_id): \OpenAPI\Client\Model\EDIUserCreationResponseDTO
```

Regenerate client secret for specified user.

Regenerates a new client secret for the user with given ID. Obeys existing client secret patterns.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CustomerPAUserApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$customer_id = 13123155; // int | Unique identifier of the customer.
$user_id = 550e8400-e29b-41d4-a716-446655440000; // string | Unique identifier of the user to generate client secret.

try {
    $result = $apiInstance->regenerateClientCredentials($customer_id, $user_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CustomerPAUserApi->regenerateClientCredentials: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **customer_id** | **int**| Unique identifier of the customer. | |
| **user_id** | **string**| Unique identifier of the user to generate client secret. | |

### Return type

[**\OpenAPI\Client\Model\EDIUserCreationResponseDTO**](../Model/EDIUserCreationResponseDTO.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchUsersOd()`

```php
searchUsersOd($customer_id): \OpenAPI\Client\Model\ODUserDetailsDTO[]
```

List all users for a specific customer.

Retrieves a list of users associated with the specified customer.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CustomerPAUserApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$customer_id = 13123155; // int | Unique identifier of the customer.

try {
    $result = $apiInstance->searchUsersOd($customer_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CustomerPAUserApi->searchUsersOd: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **customer_id** | **int**| Unique identifier of the customer. | |

### Return type

[**\OpenAPI\Client\Model\ODUserDetailsDTO[]**](../Model/ODUserDetailsDTO.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `updateUserOd()`

```php
updateUserOd($customer_id, $user_id, $edi_user_od_update_dto): object
```

Update an existing user for a specific customer.

Updates the information of an existing user associated with a given customer.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CustomerPAUserApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$customer_id = 13123155; // int | Unique identifier of the customer.
$user_id = 550e8400-e29b-41d4-a716-446655440000; // string | Unique identifier of the user to be updated.
$edi_user_od_update_dto = new \OpenAPI\Client\Model\EDIUserOdUpdateDTO(); // \OpenAPI\Client\Model\EDIUserOdUpdateDTO

try {
    $result = $apiInstance->updateUserOd($customer_id, $user_id, $edi_user_od_update_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CustomerPAUserApi->updateUserOd: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **customer_id** | **int**| Unique identifier of the customer. | |
| **user_id** | **string**| Unique identifier of the user to be updated. | |
| **edi_user_od_update_dto** | [**\OpenAPI\Client\Model\EDIUserOdUpdateDTO**](../Model/EDIUserOdUpdateDTO.md)|  | |

### Return type

**object**

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `*/*`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
