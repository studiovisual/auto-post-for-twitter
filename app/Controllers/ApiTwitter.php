<?php

namespace StudioVisual\Twitter\Controllers;

use StudioVisual\Twitter\Autotwitter_App;
use StudioVisual\Twitter\Models\Autotwitter_Logs;
use StudioVisual\Twitter\Controllers\Autotwitter_Admin;

class Autotwitter_ApiTwitter {
    protected $logs;
    protected $options;

    public function __construct() {
        // Instance dependences
        $this->logs    = new Autotwitter_Logs;
    }

    /**
    * Publish posts on twitter
    * @param int $post_id
    * @param string $message
    */
    public function autotwitter_createTweet(int $post_id, string $message) {
        $url    = 'https://api.twitter.com/2/tweets';
        $method = 'POST';

        $request_args = array(
            'headers'     => array(
                'Authorization' => $this->autotwitter_generateOAuthSignature($method, $url),
                'Content-Type'  => 'application/json'
            ),
            'body'        => '{"text":"' . $message . '"}',
        );

        $response = wp_remote_post($url, $request_args);

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();

            // Add Fail log
            $this->logs->autotwitter_add($post_id, __('falhou', 'sv-twitter'), json_encode($error_message));

            return false;
        } else {
            $response_code = wp_remote_retrieve_response_code( $response );
            $response_body = wp_remote_retrieve_body( $response );

            // Return message and code
            return [
               'code' => $response_code,
               'body' => $response_body
            ];
        }
    }

    /**
    * Generate oauth_signature
    * @param string $method
    * @param string $url
    * @return string
    */
    private function autotwitter_generateOAuthSignature($method, $url, $params = []) {
        // Generate the OAuth nonce and timestamp
        $nonce = $this->autotwitter_generateNonce();
        $timestamp = time();

        // Prepare the base string
        $encodedMethod = rawurlencode($method);
        $encodedUrl = rawurlencode($url);

        // Combine the OAuth parameters with the request parameters
        $allParams = array_merge($params, array(
            'oauth_consumer_key'     => Autotwitter_Admin::autotwitter_getSettings()['consumerKey'],
            'oauth_nonce'            => $nonce,
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp'        => $timestamp,
            'oauth_token'            => Autotwitter_Admin::autotwitter_getSettings()['tokenKey'],
            'oauth_version'          => '1.0',
        ));

        ksort($allParams);

        $encodedParams = array();
        foreach ($allParams as $key => $value) {
            $encodedParams[] = rawurlencode($key) . '=' . rawurlencode($value);
        }

        $encodedParamsString = rawurlencode(implode('&', $encodedParams));
        $baseString          = $encodedMethod . '&' . $encodedUrl . '&' . $encodedParamsString;

        // Generate the signing key
        $encodedConsumerSecret = rawurlencode(Autotwitter_Admin::autotwitter_getSettings()['consumerSecret']);
        $encodedTokenSecret    = rawurlencode(Autotwitter_Admin::autotwitter_getSettings()['tokenSecret']);
        $signingKey            = $encodedConsumerSecret . '&' . $encodedTokenSecret;

        // Calculate the HMAC-SHA1 signature
        $signature        = base64_encode(hash_hmac('sha1', $baseString, $signingKey, true));
        $encodedSignature = rawurlencode($signature);

        return $this->autotwitter_getAuthorization($method, $url, Autotwitter_Admin::autotwitter_getSettings()['consumerKey'], $nonce, $encodedSignature, $timestamp, Autotwitter_Admin::autotwitter_getSettings()['tokenKey']);
    }

    /**
    * Generate Nonce
    * @return string
    */
    private function autotwitter_generateNonce() {
        return md5(uniqid(rand(), true));
    }

    /**
    * Create headers to autorization
    * @param string $method
    * @param string $url
    * @param string $consumerKey
    * @param string $nonce
    * @param string $signature
    * @param string $timestamp
    * @param string $tokenKey
    * @return string
    */
    private function autotwitter_getAuthorization(string $method, string $url, string $consumerKey, string $nonce, string $signature, string $timestamp, string $tokenKey): string {
        return 'OAuth oauth_consumer_key="'.$consumerKey.'", oauth_nonce="'.$nonce.'", oauth_signature="'.$signature.'", oauth_signature_method="HMAC-SHA1", oauth_timestamp="'.$timestamp.'", oauth_token="'.$tokenKey.'", oauth_version="1.0"';
    }
}
