<?php
namespace Macareux\CloudflareCache\Cache;

use Package;
use Concrete\Core\Http\Client\Client;
use Concrete\Core\Http\Request;
use Zend\Http\Headers;

/**
 * Class CloudflareCache
 * @package Macareux\CloudflareCache\Cache\CloudflareCache
 */

class CloudflareCache
{
    /** @var string Cloudflare API version for future use */
    protected static $api_version = '4';

    /**
     * Create cache clear request for Cloudflare
     * 
     * @param array $paths Paths for invalidation request
     * @return Result
     */
    public function createPurgeRequest($paths = [])
    {
        $pkg = Package::getByHandle('cdn_cache_purge_cloudflare');
        $authKey = $pkg->getFileConfig()->get('cloudflare.auth_key');
        $authEmail = $pkg->getFileConfig()->get('cloudflare.auth_email');
        $zoneId = $pkg->getFileConfig()->get('cloudflare.zone_id');
        if ($zoneId && $authKey && $authEmail) {
            // Create a new HTTP client
            $client = new Client();
            $client->setUri("https://api.cloudflare.com/client/v4/zones/$zoneId/purge_cache");
            $client->setMethod(Request::METHOD_DELETE);
            
            // Set headers
            $headers = new Headers();
            $headers->addHeaders([
                'Authorization' => 'Bearer ' . $authKey,
                'X-Auth-Email' => $authEmail,
                'Content-Type' => 'application/json',
            ]);
            $client->setHeaders($headers);
            
            // Set the request body
            $data = json_encode(['purge_everything' => true]);
            $client->setRawBody($data);
            
            // Send the request and get the response
            \Log::debug(t('Cloudflare cache purge request: ') . $client->getRequest()->toString());
            $response = $client->send();
            
            // Check the response
            if ($response->isSuccess()) {
                \Log::notice(t('Cloudflare cache purge Success: ') . $response->getBody());
            } else {
                \Log::error(t('Cloudflare cache purge FAILED (Check the error message and/or set log level to debug to troubleshoot): ') . $response->getBody());
            }

            return $result;
        }
    }
}
