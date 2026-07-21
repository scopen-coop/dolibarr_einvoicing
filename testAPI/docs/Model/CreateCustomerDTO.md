# # CreateCustomerDTO

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**customer_name** | **string** |  |
**customer_legal_id** | **string** |  |
**electronic_addresses** | [**\OpenAPI\Client\Model\ElectronicAddressDTO[]**](ElectronicAddressDTO.md) |  | [optional]
**customer_vat** | **string** |  | [optional]
**customer_email** | **string** |  |
**address_line1** | **string** |  |
**address_line2** | **string** |  | [optional]
**address_line3** | **string** |  | [optional]
**postal_code** | **string** |  |
**city** | **string** |  |
**country** | **string** |  | [default to 'FR']
**language** | **string** |  | [optional] [default to 'fr_FR']
**logo** | **string** | Customer logo encoded in Base64 with metadata | [optional]
**activation_pa** | **string** | activation PA flag (Y/N) | [optional] [default to 'Y']
**reception_pa** | **string** | Reception PA flag (Y/N) | [optional] [default to 'Y']
**api_user** | **string** | Api user flag (Y/N) | [optional] [default to 'Y']
**start_date** | **string** | Start date in DD-MM-YYYY format (minimum 10 days in the future). Required when electronicAddresses is provided | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
