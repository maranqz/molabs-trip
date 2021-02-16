# TripBundle\Api\AccountsApiInterface

All URIs are relative to *http://localhost*

Method | HTTP request | Description
------------- | ------------- | -------------
[**createAccount**](AccountsApiInterface.md#createAccount) | **POST** /trip/accounts/ | Create account
[**deleteAccount**](AccountsApiInterface.md#deleteAccount) | **DELETE** /trip/accounts/{accountId} | Delete account
[**getAccount**](AccountsApiInterface.md#getAccount) | **GET** /trip/accounts/{accountId} | Get account information
[**updateAccount**](AccountsApiInterface.md#updateAccount) | **PATCH** /trip/accounts/{accountId} | Update account information


## Service Declaration
```yaml
# src/Acme/MyBundle/Resources/services.yml
services:
    # ...
    acme.my_bundle.api.accounts:
        class: Acme\MyBundle\Api\AccountsApi
        tags:
            - { name: "trip.api", api: "accounts" }
    # ...
```

## **createAccount**
> TripBundle\Model\Account createAccount($accountCreate)

Create account

### Example Implementation
```php
<?php
// src/Acme/MyBundle/Api/AccountsApiInterface.php

namespace Acme\MyBundle\Api;

use TripBundle\Api\AccountsApiInterface;

class AccountsApi implements AccountsApiInterface
{

    // ...

    /**
     * Implementation of AccountsApiInterface#createAccount
     */
    public function createAccount(AccountCreate $accountCreate)
    {
        // Implement the operation ...
    }

    // ...
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **accountCreate** | [**TripBundle\Model\AccountCreate**](../Model/AccountCreate.md)| Data of new account |

### Return type

[**TripBundle\Model\Account**](../Model/Account.md)

### Authorization

[BasicAuth](../../README.md#BasicAuth)

### HTTP request headers

 - **Content-Type**: application/json, application/x-www-form-urlencoded
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

## **deleteAccount**
> TripBundle\Model\DefaultResponse deleteAccount($accountId)

Delete account

### Example Implementation
```php
<?php
// src/Acme/MyBundle/Api/AccountsApiInterface.php

namespace Acme\MyBundle\Api;

use TripBundle\Api\AccountsApiInterface;

class AccountsApi implements AccountsApiInterface
{

    // ...

    /**
     * Implementation of AccountsApiInterface#deleteAccount
     */
    public function deleteAccount($accountId)
    {
        // Implement the operation ...
    }

    // ...
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **accountId** | **int**|  |

### Return type

[**TripBundle\Model\DefaultResponse**](../Model/DefaultResponse.md)

### Authorization

[BasicAuth](../../README.md#BasicAuth)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

## **getAccount**
> TripBundle\Model\Account getAccount($accountId)

Get account information

### Example Implementation
```php
<?php
// src/Acme/MyBundle/Api/AccountsApiInterface.php

namespace Acme\MyBundle\Api;

use TripBundle\Api\AccountsApiInterface;

class AccountsApi implements AccountsApiInterface
{

    // ...

    /**
     * Implementation of AccountsApiInterface#getAccount
     */
    public function getAccount($accountId)
    {
        // Implement the operation ...
    }

    // ...
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **accountId** | **int**|  |

### Return type

[**TripBundle\Model\Account**](../Model/Account.md)

### Authorization

[BasicAuth](../../README.md#BasicAuth)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

## **updateAccount**
> TripBundle\Model\Account updateAccount($accountId, $accountUpdate)

Update account information

### Example Implementation
```php
<?php
// src/Acme/MyBundle/Api/AccountsApiInterface.php

namespace Acme\MyBundle\Api;

use TripBundle\Api\AccountsApiInterface;

class AccountsApi implements AccountsApiInterface
{

    // ...

    /**
     * Implementation of AccountsApiInterface#updateAccount
     */
    public function updateAccount($accountId, AccountUpdate $accountUpdate)
    {
        // Implement the operation ...
    }

    // ...
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **accountId** | **int**|  |
 **accountUpdate** | [**TripBundle\Model\AccountUpdate**](../Model/AccountUpdate.md)| Updatable data of account |

### Return type

[**TripBundle\Model\Account**](../Model/Account.md)

### Authorization

[BasicAuth](../../README.md#BasicAuth)

### HTTP request headers

 - **Content-Type**: application/json, application/x-www-form-urlencoded
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

