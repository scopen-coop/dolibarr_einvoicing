# OpenAPI\Client\SupervisorApi

All URIs are relative to https://ppd.hubtimize.fr/api/orchestrator, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getHealth()**](SupervisorApi.md#getHealth) | **GET** /v1/healthcheck | Check whether the API service is up and running. |


## `getHealth()`

```php
getHealth($request_id)
```

Check whether the API service is up and running.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\SupervisorApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$request_id = 'request_id_example'; // string | Header parameter used to correlate logs from several components

try {
    $apiInstance->getHealth($request_id);
} catch (Exception $e) {
    echo 'Exception when calling SupervisorApi->getHealth: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **request_id** | **string**| Header parameter used to correlate logs from several components | [optional] |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
