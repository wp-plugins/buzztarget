<?php

namespace BuzzTargetLive;

$vars = array();

if (isset($_POST['save_api_settings']))
{
    $postKeys = array('api_key', 'api_secret_key', 'api_user', 'api_user_token');
    $postFilters = 'wp_strip_all_tags';
    list($apiKey, $apiSecretKey, $apiUser, $apiUserToken) = $this->request->getPostValues($postKeys, $postFilters);

    $apiOptions = array(
        'api_key' => $apiKey,
        'api_secret_key' => $apiSecretKey,
        'api_user' => $apiUser,
        'api_user_token' => $apiUserToken,
    );

    if (update_option('buzztarget_api_options', $apiOptions))
        $vars['save_api_settings_result'] = $this->text->__('API_SETTINGS_PAGE_CONTENT_SAVE_CHANGES_SUCCESS');
    else
        $vars['save_api_settings_result'] = $this->text->__('API_SETTINGS_PAGE_CONTENT_SAVE_CHANGES_FAILURE');
}

$_vars = array(
    'api_options' => get_option('buzztarget_api_options'),
    'api_settings_page_heading' => $this->text->__('API_SETTINGS_PAGE_CONTENT_HEADING'),
    'api_settings_page_desc' => $this->text->__('API_SETTINGS_PAGE_CONTENT_DESC'),
    'api_settings_page_sub_heading' => $this->text->__('API_SETTINGS_PAGE_CONTENT_SUB_HEADING'),
    'api_settings_page_api_key' => $this->text->__('API_SETTINGS_PAGE_CONTENT_API_KEY'),
    'api_settings_page_api_secret_key' => $this->text->__('API_SETTINGS_PAGE_CONTENT_API_SECRET_KEY'),
    'api_settings_page_api_user' => $this->text->__('API_SETTINGS_PAGE_CONTENT_API_USER'),
    'api_settings_page_api_user_token' => $this->text->__('API_SETTINGS_PAGE_CONTENT_API_USER_TOKEN'),
    'api_settings_page_save_changes' => $this->text->__('API_SETTINGS_PAGE_CONTENT_SAVE_CHANGES'),
);

$vars = array_merge($_vars, $vars);

echo $this->twig->render('@admin/api-settings.twig', $vars);