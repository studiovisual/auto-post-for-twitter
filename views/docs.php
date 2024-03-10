<?php
/**
 * Docs
 * Docs of plugin
 * php version 8.1
 *
 * @category File
 * @package  Docs_Auto_Post_For_Twitter
 * @author   Studio Visual <atendimento@studiovisual.com.br>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.studiovisual.com.br
 */

 //phpcs:disable

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<div class="wrap sv-plugin docs">
    <h1><?php echo esc_html_e('How to get Api Twitter access', 'sv-twitter'); ?></h1>

    <h2><?php echo esc_html_e('Step 1: Sign up to get a developer account', 'sv-twitter'); ?></h2>
    <p>
        <?php echo esc_html_e('The developer accounts signup is easy and quickly!', 'sv-twitter'); ?>
        <a href="https://developer.twitter.com/en/portal/products/basic" target="_blank">Api Twitter</a>
    </p>

    <h2><?php echo esc_html_e('Step 2: Save your app key and tokens and keep them safe', 'sv-twitter'); ?></h2>
    <p><?php echo esc_html_e('Once you have access and have created a project and application, you can find or generate the following credentials within your developer application:', 'sv-twitter'); ?></p>

    <ul>
        <li><?php echo esc_html_e('API Key and Secret: Essentially your application\'s username and password. You will use this data to authenticate requests that require OAuth 1.0a User Context or to generate other tokens such as user access tokens or application access tokens.', 'sv-twitter'); ?></li>
        <li><?php echo esc_html_e('Access Token and Secret: Access Tokens generally represent the user on whose behalf you are making the request. Tokens that you can generate through the developer portal represent the user who owns the app. You will use this data to authenticate requests that require OAuth 1.0a User Context. If you want to make requests on behalf of another user, you\'ll need to use OAuth\'s two factor\'s authentication flow to get them to authorize you.', 'sv-twitter'); ?></li>
        <li><?php echo esc_html_e('Client ID and Client Secret: These credentials are used to obtain an Access Token from the user with OAuth 2.0 authentication. Similar to OAuth 1.0a, user Access Tokens are used to authenticate requests that provide private user account information or perform actions on behalf of another account, but are verbose scoped for greater control over the access the client application has to the user.', 'sv-twitter'); ?></li>
        <li><?php echo esc_html_e('App only Access Token: You will use this token when making requests to endpoints that respond with publicly available information on Twitter.', 'sv-twitter'); ?></li>
    </ul>

    <p><?php echo esc_html_e('Since these keys and tokens do not expire unless regenerated, we suggest that you save them in a safe place, such as a password manager, once you receive your credentials.', 'sv-twitter'); ?></p>

    <h2><?php echo esc_html_e('Step 3: Copy your generated keys', 'sv-twitter'); ?></h2>

    <p>
        <?php echo esc_html_e('Now you just have to insert your keys on the corresponding fields on the ', 'sv-twitter'); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=' . $settingsPage)); ?>" title="<?php echo esc_html_e('Settings', 'sv-twitter'); ?>"><?php echo esc_html_e('plugin settings', 'sv-twitter'); ?></a>
        <?php echo esc_html_e('and make your customizations.', 'sv-twitter'); ?>
    </p>
</div>
