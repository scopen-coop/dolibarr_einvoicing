# OpenAPI\Client\APITokenApi

All URIs are relative to https://ppd.hubtimize.fr/api/orchestrator, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**oauthToken()**](APITokenApi.md#oauthToken) | **POST** /v1/oauth2/token |  |
| [**token()**](APITokenApi.md#token) | **POST** /v1/token |  |


## `oauthToken()`

```php
oauthToken($grant_type, $client_id, $client_secret, $scope): \OpenAPI\Client\Model\AccessTokenResponse
```



### Example

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

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **grant_type** | **string**|  | |
| **client_id** | **string**| _Can be sent on the body OR on the Basic Auth_ | [optional] |
| **client_secret** | **string**| _Can be sent on the body OR on the Basic Auth_ | [optional] |
| **scope** | **string**|  | [optional] |

### Return type

[**\OpenAPI\Client\Model\AccessTokenResponse**](../Model/AccessTokenResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth)

### HTTP request headers

- **Content-Type**: `application/x-www-form-urlencoded`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `token()`

```php
token($token_request_dto): \OpenAPI\Client\Model\AccessTokenResponse
```



### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\APITokenApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$token_request_dto = new \OpenAPI\Client\Model\TokenRequestDto(); // \OpenAPI\Client\Model\TokenRequestDto

try {
    $result = $apiInstance->token($token_request_dto);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling APITokenApi->token: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **token_request_dto** | [**\OpenAPI\Client\Model\TokenRequestDto**](../Model/TokenRequestDto.md)|  | |

### Return type

[**\OpenAPI\Client\Model\AccessTokenResponse**](../Model/AccessTokenResponse.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `*/*`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
