<?php

/**
 * TripsController
 * PHP version 7.1.3
 *
 * @category Class
 * @package  TripBundle\Controller
 * @author   OpenAPI Generator team
 * @link     https://github.com/openapitools/openapi-generator
 */

/**
 * Trips
 *
 * No description provided (generated by Openapi Generator https://github.com/openapitools/openapi-generator)
 *
 * The version of the OpenAPI document: 1.0.0
 *
 * Generated by: https://github.com/openapitools/openapi-generator.git
 *
 */

/**
 * NOTE: This class is auto generated by the openapi generator program.
 * https://github.com/openapitools/openapi-generator
 * Do not edit the class manually.
 */

namespace TripBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use \Exception;
use JMS\Serializer\Exception\RuntimeException as SerializerRuntimeException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints as Assert;
use TripBundle\Api\TripsApiInterface;
use TripBundle\Entity\Country;
use TripBundle\Entity\Trip as Entity;
use TripBundle\Model\Filter;

/**
 * TripsController Class Doc Comment
 *
 * @category Class
 * @package  TripBundle\Controller
 * @author   OpenAPI Generator team
 * @link     https://github.com/openapitools/openapi-generator
 */
class TripsController extends Controller
{
    private Security $security;
    private ObjectRepository $countryRepository;

    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->countryRepository = $em->getRepository(Country::class);
    }

    /**
     * Operation createTrip
     *
     * Create trip
     *
     * @param Request $request The Symfony request to handle.
     * @return Response The Symfony response.
     */
    public function createTripAction(Request $request)
    {
        // Make sure that the client is providing something that we can consume
        $consumes = ['application/json', 'application/x-www-form-urlencoded'];
        if (!static::isContentTypeAllowed($request, $consumes)) {
            // We can't consume the content that the client is sending us
            return new Response('', 415);
        }

        // Figure out what data format to return to the client
        $produces = ['application/json'];
        // Figure out what the client accepts
        $clientAccepts = $request->headers->has('Accept') ? $request->headers->get('Accept') : '*/*';
        $responseFormat = $this->getOutputFormat($clientAccepts, $produces);
        if ($responseFormat === null) {
            return new Response('', 406);
        }

        // Handle authentication
        // Authentication 'BasicAuth' required
        // HTTP basic authentication required
        $securityBasicAuth = $request->headers->get('authorization');

        // Read out all input parameter values into variables
        $tripCreate = $request->getContent();

        // Use the default value if no value was provided

        // Deserialize the input values that needs it
        try {
            $inputFormat = $request->getMimeType($request->getContentType());
            $tripCreate = $this->deserialize($tripCreate, 'TripBundle\Model\TripCreate', $inputFormat);
            $tripCreate->setCreatedBy($this->security->getUser());
        } catch (SerializerRuntimeException $exception) {
            return $this->createBadRequestResponse($exception->getMessage());
        }

        // Validate the input values
        $asserts = [];
        $asserts[] = new Assert\NotNull();
        $asserts[] = new Assert\Type("TripBundle\Model\TripCreate");
        $asserts[] = new Assert\Valid();
        $response = $this->validate($tripCreate, $asserts);
        if ($response instanceof Response) {
            return $response;
        }


        try {
            $handler = $this->getApiHandler();

            // Set authentication method 'BasicAuth'
            $handler->setBasicAuth($securityBasicAuth);

            // Make the call to the business logic
            $responseCode = 200;
            $responseHeaders = [];
            $result = $handler->createTrip($tripCreate, $responseCode, $responseHeaders);

            // Find default response message
            $message = 'unexpected error';

            // Find a more specific message, if available
            switch ($responseCode) {
                case 200:
                    $message = 'ok';
                    break;
                case 0:
                    $message = 'unexpected error';
                    break;
            }

            return new Response(
                $result !== null ? $this->serialize($result, $responseFormat) : '',
                $responseCode,
                array_merge(
                    $responseHeaders,
                    [
                        'Content-Type' => $responseFormat,
                        'X-OpenAPI-Message' => $message
                    ]
                )
            );
        } catch (Exception $fallthrough) {
            return $this->createErrorResponse(new HttpException(500, 'An unsuspected error occurred.', $fallthrough));
        }
    }

    /**
     * Operation deleteTrip
     *
     * Delete trip
     *
     * @param Request $request The Symfony request to handle.
     * @return Response The Symfony response.
     *
     * @IsGranted(subject="trip")
     * @ParamConverter("trip", options={"id" = "tripId"}, class=Entity::class)
     */
    public function deleteTripAction(Request $request, $tripId)
    {
        // Figure out what data format to return to the client
        $produces = ['application/json'];
        // Figure out what the client accepts
        $clientAccepts = $request->headers->has('Accept') ? $request->headers->get('Accept') : '*/*';
        $responseFormat = $this->getOutputFormat($clientAccepts, $produces);
        if ($responseFormat === null) {
            return new Response('', 406);
        }

        // Handle authentication
        // Authentication 'BasicAuth' required
        // HTTP basic authentication required
        $securityBasicAuth = $request->headers->get('authorization');

        // Read out all input parameter values into variables

        // Use the default value if no value was provided

        // Deserialize the input values that needs it
        try {
            $tripId = $this->deserialize($tripId, 'int', 'string');
        } catch (SerializerRuntimeException $exception) {
            return $this->createBadRequestResponse($exception->getMessage());
        }

        // Validate the input values
        $asserts = [];
        $asserts[] = new Assert\NotNull();
        $asserts[] = new Assert\Type("int");
        $response = $this->validate($tripId, $asserts);
        if ($response instanceof Response) {
            return $response;
        }


        try {
            $handler = $this->getApiHandler();

            // Set authentication method 'BasicAuth'
            $handler->setBasicAuth($securityBasicAuth);

            // Make the call to the business logic
            $responseCode = 200;
            $responseHeaders = [];
            $result = $handler->deleteTrip($tripId, $responseCode, $responseHeaders);

            // Find default response message
            $message = 'unexpected error';

            // Find a more specific message, if available
            switch ($responseCode) {
                case 200:
                    $message = 'ok';
                    break;
                case 0:
                    $message = 'unexpected error';
                    break;
            }

            return new Response(
                $result !== null ? $this->serialize($result, $responseFormat) : '',
                $responseCode,
                array_merge(
                    $responseHeaders,
                    [
                        'Content-Type' => $responseFormat,
                        'X-OpenAPI-Message' => $message
                    ]
                )
            );
        } catch (Exception $fallthrough) {
            return $this->createErrorResponse(new HttpException(500, 'An unsuspected error occurred.', $fallthrough));
        }
    }

    /**
     * Operation getTrip
     *
     * Get trip information
     *
     * @param Request $request The Symfony request to handle.
     * @return Response The Symfony response.
     *
     * @IsGranted(subject="trip")
     * @ParamConverter("trip", options={"id" = "tripId"}, class=Entity::class)
     */
    public function getTripAction(Request $request, $tripId)
    {
        // Figure out what data format to return to the client
        $produces = ['application/json'];
        // Figure out what the client accepts
        $clientAccepts = $request->headers->has('Accept') ? $request->headers->get('Accept') : '*/*';
        $responseFormat = $this->getOutputFormat($clientAccepts, $produces);
        if ($responseFormat === null) {
            return new Response('', 406);
        }

        // Handle authentication
        // Authentication 'BasicAuth' required
        // HTTP basic authentication required
        $securityBasicAuth = $request->headers->get('authorization');

        // Read out all input parameter values into variables

        // Use the default value if no value was provided

        // Deserialize the input values that needs it
        try {
            $tripId = $this->deserialize($tripId, 'int', 'string');
        } catch (SerializerRuntimeException $exception) {
            return $this->createBadRequestResponse($exception->getMessage());
        }

        // Validate the input values
        $asserts = [];
        $asserts[] = new Assert\NotNull();
        $asserts[] = new Assert\Type("int");
        $response = $this->validate($tripId, $asserts);
        if ($response instanceof Response) {
            return $response;
        }


        try {
            $handler = $this->getApiHandler();

            // Set authentication method 'BasicAuth'
            $handler->setBasicAuth($securityBasicAuth);

            // Make the call to the business logic
            $responseCode = 200;
            $responseHeaders = [];
            $result = $handler->getTrip($tripId, $responseCode, $responseHeaders);

            // Find default response message
            $message = 'unexpected error';

            // Find a more specific message, if available
            switch ($responseCode) {
                case 200:
                    $message = 'ok';
                    break;
                case 0:
                    $message = 'unexpected error';
                    break;
            }

            return new Response(
                $result !== null ? $this->serialize($result, $responseFormat) : '',
                $responseCode,
                array_merge(
                    $responseHeaders,
                    [
                        'Content-Type' => $responseFormat,
                        'X-OpenAPI-Message' => $message
                    ]
                )
            );
        } catch (Exception $fallthrough) {
            return $this->createErrorResponse(new HttpException(500, 'An unsuspected error occurred.', $fallthrough));
        }
    }

    /**
     * Operation getTrips
     *
     * Get trips
     *
     * @param Request $request The Symfony request to handle.
     * @return Response The Symfony response.
     */
    public function getTripsAction(Request $request)
    {
        // Figure out what data format to return to the client
        $produces = ['application/json'];
        // Figure out what the client accepts
        $clientAccepts = $request->headers->has('Accept')?$request->headers->get('Accept'):'*/*';
        $responseFormat = $this->getOutputFormat($clientAccepts, $produces);
        if ($responseFormat === null) {
            return new Response('', 406);
        }

        // Handle authentication
        // Authentication 'BasicAuth' required
        // HTTP basic authentication required
        $securityBasicAuth = $request->headers->get('authorization');

        // Read out all input parameter values into variables
        $filter = $request->query->get('filter');

        // Use the default value if no value was provided

        // Deserialize the input values that needs it
        try {
            $filter = $this->deserialize(json_encode([
                'started_at' => $request->query->get('started_at'),
                'finished_at' => $request->query->get('finished_at'),
                'country' => $request->query->get('country'),
            ]), Filter::class, 'application/json');
        } catch (SerializerRuntimeException $exception) {
            return $this->createBadRequestResponse($exception->getMessage());
        }

        // Validate the input values
        $asserts = [];
        $asserts[] = new Assert\Type(Filter::class);
        $asserts[] = new Assert\Valid();
        $response = $this->validate($filter, $asserts);
        if ($response instanceof Response) {
            return $response;
        }


        try {
            $handler = $this->getApiHandler();

            // Set authentication method 'BasicAuth'
            $handler->setBasicAuth($securityBasicAuth);

            // Make the call to the business logic
            $responseCode = 200;
            $responseHeaders = [];
            $result = $handler->getTrips($filter, $responseCode, $responseHeaders);

            // Find default response message
            $message = 'unexpected error';

            // Find a more specific message, if available
            switch ($responseCode) {
                case 200:
                    $message = 'ok';
                    break;
                case 0:
                    $message = 'unexpected error';
                    break;
            }

            return new Response(
                $result !== null ?$this->serialize($result, $responseFormat):'',
                $responseCode,
                array_merge(
                    $responseHeaders,
                    [
                        'Content-Type' => $responseFormat,
                        'X-OpenAPI-Message' => $message
                    ]
                )
            );
        } catch (Exception $fallthrough) {
            return $this->createErrorResponse(new HttpException(500, 'An unsuspected error occurred.', $fallthrough));
        }
    }

    /**
     * Operation updateTrip
     *
     * Update trip information
     *
     * @param Request $request The Symfony request to handle.
     * @return Response The Symfony response.
     *
     * @IsGranted(subject="trip")
     * @ParamConverter("trip", options={"id" = "tripId"}, class=Entity::class)
     */
    public function updateTripAction(Request $request, $tripId, Entity $trip)
    {
        // Make sure that the client is providing something that we can consume
        $consumes = ['application/json', 'application/x-www-form-urlencoded'];
        if (!static::isContentTypeAllowed($request, $consumes)) {
            // We can't consume the content that the client is sending us
            return new Response('', 415);
        }

        // Figure out what data format to return to the client
        $produces = ['application/json'];
        // Figure out what the client accepts
        $clientAccepts = $request->headers->has('Accept') ? $request->headers->get('Accept') : '*/*';
        $responseFormat = $this->getOutputFormat($clientAccepts, $produces);
        if ($responseFormat === null) {
            return new Response('', 406);
        }

        // Handle authentication
        // Authentication 'BasicAuth' required
        // HTTP basic authentication required
        $securityBasicAuth = $request->headers->get('authorization');

        // Read out all input parameter values into variables
        $tripUpdate = $request->getContent();

        // Use the default value if no value was provided

        // Deserialize the input values that needs it
        try {
            $tripId = $this->deserialize($tripId, 'int', 'string');
            $inputFormat = $request->getMimeType($request->getContentType());
            $tripUpdate = $this->deserialize($tripUpdate, 'TripBundle\Model\TripUpdate', $inputFormat);
            $tripUpdate->setId($tripId);
            $tripUpdate->setCreatedBy($this->security->getUser());
            if (empty($tripUpdate->getStartedAt())) {
                $tripUpdate->setStartedAt($trip->getStartedAt());
            }
            if (empty($tripUpdate->getFinishedAt())) {
                $tripUpdate->setFinishedAt($trip->getFinishedAt());
            }
        } catch (SerializerRuntimeException $exception) {
            return $this->createBadRequestResponse($exception->getMessage());
        }

        // Validate the input values
        $asserts = [];
        $asserts[] = new Assert\NotNull();
        $asserts[] = new Assert\Type("int");
        $response = $this->validate($tripId, $asserts);
        if ($response instanceof Response) {
            return $response;
        }
        $asserts = [];
        $asserts[] = new Assert\NotNull();
        $asserts[] = new Assert\Type("TripBundle\Model\TripUpdate");
        $asserts[] = new Assert\Valid();
        $response = $this->validate($tripUpdate, $asserts);
        if ($response instanceof Response) {
            return $response;
        }


        try {
            $handler = $this->getApiHandler();

            // Set authentication method 'BasicAuth'
            $handler->setBasicAuth($securityBasicAuth);

            // Make the call to the business logic
            $responseCode = 200;
            $responseHeaders = [];
            $result = $handler->updateTrip($tripId, $tripUpdate, $responseCode, $responseHeaders);

            // Find default response message
            $message = 'unexpected error';

            // Find a more specific message, if available
            switch ($responseCode) {
                case 200:
                    $message = 'ok';
                    break;
                case 0:
                    $message = 'unexpected error';
                    break;
            }

            return new Response(
                $result !== null ? $this->serialize($result, $responseFormat) : '',
                $responseCode,
                array_merge(
                    $responseHeaders,
                    [
                        'Content-Type' => $responseFormat,
                        'X-OpenAPI-Message' => $message
                    ]
                )
            );
        } catch (Exception $fallthrough) {
            return $this->createErrorResponse(new HttpException(500, 'An unsuspected error occurred.', $fallthrough));
        }
    }

    /**
     * Returns the handler for this API controller.
     * @return TripsApiInterface
     */
    public function getApiHandler()
    {
        return $this->apiServer->getApiHandler('trips');
    }
}
