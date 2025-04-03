<?php

namespace MauticPlugin\ExampleBundle\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Mautic\CacheBundle\Cache\CacheProvider;
use Mautic\LeadBundle\Model\LeadModel;

class DemoService
{
    private $cacheProvider;
    private $httpClient;
    private $leadModel;

    public function __construct(LeadModel $leadModel, CacheProvider $cacheProvider, Client $httpClient)
    {
        $this->leadModel     = $leadModel;
        $this->cacheProvider = $cacheProvider;
        $this->httpClient    = $httpClient;
    }

    public function getMauticContactCount()
    {
        $cacheKey  = 'awesome_mautic_query_count';
        $cacheItem = $this->cacheProvider->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $leadModel     = $this->leadModel;
        $results       = $leadModel->getRepository()->count([]);

        // Cache the results indefinitely unless .
        $cacheItem->set($results);
        $cacheItem->expiresAfter(315360000); // 10 years unless actioned in UI btn.
        $cacheItem->tag(['contact_count']);
        $this->cacheProvider->save($cacheItem);

        return $results;
    }

    public function callExternalService()
    {
        $url     = 'https://dog.ceo/api/breeds/list/all';
        $results = []; // Initialize results array

        try {
            // Send the request using Guzzle
            $response = $this->httpClient->request('GET', $url);

            // Check the response status code
            if (200 !== $response->getStatusCode()) {
                throw new \Exception('HTTP Error: Received response code '.$response->getStatusCode());
            }

            // Get the response body and decode it
            $body = json_decode($response->getBody(), true);

            // Process the body as needed
            if (isset($body['message'])) {
                $results = $body['message']; // Assuming 'all' contains the facts
            } else {
                throw new \Exception('Unexpected response structure');
            }
        } catch (RequestException $e) {
            // Handle Guzzle request exceptions
            error_log('Request Exception: '.$e->getMessage());
            $results = []; // Return an empty array or handle as needed
        } catch (\Exception $e) {
            // Handle other exceptions
            error_log('General Exception: '.$e->getMessage());
            $results = []; // Return an empty array or handle as needed
        }

        return $results; // Return the results
    }
}
