# TripBundle\Api\TripsApiInterface

All URIs are relative to *http://localhost*

Method | HTTP request | Description
------------- | ------------- | -------------
[**createTrip**](TripsApiInterface.md#createTrip) | **POST** /trips/ | Create trip
[**deleteTrip**](TripsApiInterface.md#deleteTrip) | **DELETE** /trips/{tripId} | Delete trip
[**getTrip**](TripsApiInterface.md#getTrip) | **GET** /trips/{tripId} | Get trip information
[**updateTrip**](TripsApiInterface.md#updateTrip) | **PATCH** /trips/{tripId} | Update trip information


## Service Declaration
```yaml
# src/Acme/MyBundle/Resources/services.yml
services:
    # ...
    acme.my_bundle.api.trips:
        class: Acme\MyBundle\Api\TripsApi
        tags:
            - { name: "trip.api", api: "trips" }
    # ...
```

## **createTrip**
> TripBundle\Model\Trip createTrip($trip)

Create trip

### Example Implementation
```php
<?php
// src/Acme/MyBundle/Api/TripsApiInterface.php

namespace Acme\MyBundle\Api;

use TripBundle\Api\TripsApiInterface;

class TripsApi implements TripsApiInterface
{

    // ...

    /**
     * Implementation of TripsApiInterface#createTrip
     */
    public function createTrip(Trip $trip)
    {
        // Implement the operation ...
    }

    // ...
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **trip** | [**TripBundle\Model\Trip**](../Model/Trip.md)| Data of new trip |

### Return type

[**TripBundle\Model\Trip**](../Model/Trip.md)

### Authorization

[BearerAuth](../../README.md#BearerAuth)

### HTTP request headers

 - **Content-Type**: application/json, application/x-www-form-urlencoded
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

## **deleteTrip**
> TripBundle\Model\DefaultResponse deleteTrip($tripId)

Delete trip

### Example Implementation
```php
<?php
// src/Acme/MyBundle/Api/TripsApiInterface.php

namespace Acme\MyBundle\Api;

use TripBundle\Api\TripsApiInterface;

class TripsApi implements TripsApiInterface
{

    // ...

    /**
     * Implementation of TripsApiInterface#deleteTrip
     */
    public function deleteTrip($tripId)
    {
        // Implement the operation ...
    }

    // ...
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **tripId** | **int**|  |

### Return type

[**TripBundle\Model\DefaultResponse**](../Model/DefaultResponse.md)

### Authorization

[BearerAuth](../../README.md#BearerAuth)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

## **getTrip**
> TripBundle\Model\Trip getTrip($tripId)

Get trip information

### Example Implementation
```php
<?php
// src/Acme/MyBundle/Api/TripsApiInterface.php

namespace Acme\MyBundle\Api;

use TripBundle\Api\TripsApiInterface;

class TripsApi implements TripsApiInterface
{

    // ...

    /**
     * Implementation of TripsApiInterface#getTrip
     */
    public function getTrip($tripId)
    {
        // Implement the operation ...
    }

    // ...
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **tripId** | **int**|  |

### Return type

[**TripBundle\Model\Trip**](../Model/Trip.md)

### Authorization

[BearerAuth](../../README.md#BearerAuth)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

## **updateTrip**
> TripBundle\Model\Trip updateTrip($tripId, $trip)

Update trip information

### Example Implementation
```php
<?php
// src/Acme/MyBundle/Api/TripsApiInterface.php

namespace Acme\MyBundle\Api;

use TripBundle\Api\TripsApiInterface;

class TripsApi implements TripsApiInterface
{

    // ...

    /**
     * Implementation of TripsApiInterface#updateTrip
     */
    public function updateTrip($tripId, Trip $trip)
    {
        // Implement the operation ...
    }

    // ...
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **tripId** | **int**|  |
 **trip** | [**TripBundle\Model\Trip**](../Model/Trip.md)| Updatable data of trip |

### Return type

[**TripBundle\Model\Trip**](../Model/Trip.md)

### Authorization

[BearerAuth](../../README.md#BearerAuth)

### HTTP request headers

 - **Content-Type**: application/json, application/x-www-form-urlencoded
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

