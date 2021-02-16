# TripBundle\Api\CountriesApiInterface

All URIs are relative to *http://localhost*

Method | HTTP request | Description
------------- | ------------- | -------------
[**getCountries**](CountriesApiInterface.md#getCountries) | **GET** /trip/countries/ | Get list of countries


## Service Declaration
```yaml
# src/Acme/MyBundle/Resources/services.yml
services:
    # ...
    acme.my_bundle.api.countries:
        class: Acme\MyBundle\Api\CountriesApi
        tags:
            - { name: "trip.api", api: "countries" }
    # ...
```

## **getCountries**
> TripBundle\Model\Country getCountries()

Get list of countries

### Example Implementation
```php
<?php
// src/Acme/MyBundle/Api/CountriesApiInterface.php

namespace Acme\MyBundle\Api;

use TripBundle\Api\CountriesApiInterface;

class CountriesApi implements CountriesApiInterface
{

    // ...

    /**
     * Implementation of CountriesApiInterface#getCountries
     */
    public function getCountries()
    {
        // Implement the operation ...
    }

    // ...
}
```

### Parameters
This endpoint does not need any parameter.

### Return type

[**TripBundle\Model\Country**](../Model/Country.md)

### Authorization

[BasicAuth](../../README.md#BasicAuth)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

