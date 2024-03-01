<?php
/**
 * Class Api Auto Twitter
 * Api twitter plugin
 * php version 8.1
 *
 * @category Class
 * @package  Autotwitter_ApiTwitter
 * @author   Studio Visual <atendimento@studiovisual.com.br>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.studiovisual.com.br
 */

namespace StudioVisual\Twitter\Controllers;

use StudioVisual\Twitter\Autotwitter_App;
use StudioVisual\Twitter\Models\Autotwitter_Logs;
use StudioVisual\Twitter\Controllers\Autotwitter_Admin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Api Auto Twitter
 *
 * @category Class
 * @package  Autotwitter_ApiTwitter
 * @author   Studio Visual <atendimento@studiovisual.com.br>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.studiovisual.com.br
 */
class Autotwitter_ApiTwitter
{

    protected $logs;
    protected $options;

    /**
     * Construct
     */
    public function __construct()
    {
        // Instance dependences
        $this->logs = new Autotwitter_Logs();
    }

    /**
     * Publish posts on twitter
     *
     * @param int    $post_id post_id
     * @param string $message message
     *
     * @return boolean|array
     */
    public function autotwitter_createTweet(int $post_id, string $message) { //phpcs:ignore
        $url    = 'https://api.twitter.com/2/tweets';
        $method = 'POST';

        $request_args = array(
        'headers' => array(
                'Authorization' => $this->autotwitter_generateOAuthSignature($method, $url), //phpcs:ignore
        'Content-Type'  => 'application/json',
        ),
        'body'    => '{"text":"' . $message . '"}',
        );

        $response = wp_remote_post($url, $request_args);

        if (is_wp_error($response) ) {
            $error_message = $response->get_error_message();

            // Add Fail log
            $this->logs->autotwitter_add($post_id, __('falhou', 'sv-twitter'), json_encode($error_message)); //phpcs:ignore

            return false;
        } else {
            $response_code = wp_remote_retrieve_response_code($response);
            $response_body = wp_remote_retrieve_body($response);

            // Return message and code
            return array(
            'code' => $response_code,
            'body' => $response_body,
            );
        }
    }

    /**
     * Generate oauth_signature
     *
     * @param string $method method
     * @param string $url    url
     * @param array  $params params
     *
     * @return string
     */
    private function autotwitter_generateOAuthSignature($method, $url, $params = []) { //phpcs:ignore
        // Generate the OAuth nonce and timestamp
        $nonce     = $this->autotwitter_generateNonce();
        $timestamp = time();

        // Prepare the base string
        $encodedMethod = rawurlencode($method);
        $encodedUrl    = rawurlencode($url);

        // Combine the OAuth parameters with the request parameters
        $allParams = array_merge(
            $params,
            array(
            'oauth_consumer_key'     => Autotwitter_Admin::autotwitter_getSettings()['consumerKey'], //phpcs:ignore
            'oauth_nonce'            => $nonce,
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp'        => $timestamp,
            'oauth_token'            => Autotwitter_Admin::autotwitter_getSettings()['tokenKey'], //phpcs:ignore
            'oauth_version'          => '1.0',
            )
        );

        ksort($allParams);

        $encodedParams = array();
        foreach ( $allParams as $key => $value ) {
            $encodedParams[] = rawurlencode($key) . '=' . rawurlencode($value);
        }

        $encodedParamsString = rawurlencode(implode('&', $encodedParams));
        $baseString          = $encodedMethod . '&' . $encodedUrl . '&' . $encodedParamsString; //phpcs:ignore

        // Generate the signing key
        $encodedConsumerSecret = rawurlencode(Autotwitter_Admin::autotwitter_getSettings()['consumerSecret']); //phpcs:ignore
        $encodedTokenSecret    = rawurlencode(Autotwitter_Admin::autotwitter_getSettings()['tokenSecret']); //phpcs:ignore
        $signingKey            = $encodedConsumerSecret . '&' . $encodedTokenSecret;

        // Calculate the HMAC-SHA1 signature
        $signature        = base64_encode(hash_hmac('sha1', $baseString, $signingKey, true)); //phpcs:ignore
        $encodedSignature = rawurlencode($signature);

        return $this->autotwitter_getAuthorization(
            $method,
            $url,
            Autotwitter_Admin::autotwitter_getSettings()['consumerKey'],
            $nonce,
            $encodedSignature,
            $timestamp,
            Autotwitter_Admin::autotwitter_getSettings()['tokenKey']
        );
    }

    /**
     * Generate Nonce
     *
     * @return string
     */
    private function autotwitter_generateNonce() : string { //phpcs:ignore
        return md5(uniqid(rand(), true));
    }

    /**
     * Create headers to autorization
     *
     * @param string $method      method
     * @param string $url         url
     * @param string $consumerKey consumerKey
     * @param string $nonce       nonce
     * @param string $signature   signature
     * @param string $timestamp   timestamp
     * @param string $tokenKey    tokenkey
     *
     * @return string
     */
    private function autotwitter_getAuthorization(string $method, string $url, string $consumerKey, string $nonce, string $signature, string $timestamp, string $tokenKey): string { //phpcs:ignore
        return 'OAuth oauth_consumer_key="'.$consumerKey.'", oauth_nonce="'.$nonce.'", oauth_signature="'.$signature.'", oauth_signature_method="HMAC-SHA1", oauth_timestamp="'.$timestamp.'", oauth_token="'.$tokenKey.'", oauth_version="1.0"'; //phpcs:ignore
    }
}
