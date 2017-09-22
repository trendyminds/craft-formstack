<?php
/**
 * Formstack plugin for Craft CMS
 *
 * Formstack Submit Controller
 *
 * @author    TrendyMinds
 * @copyright Copyright (c) 2017 TrendyMinds
 * @link      https://trendyminds.com
 * @package   Formstack
 * @since     1.0.0
 */

namespace Craft;

class Formstack_SubmitFormController extends BaseController
{

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     * @access protected
     */
    protected $allowAnonymous = true;

    public function actionSendForm()
    {
        // Only allow post requests through.
        $this->requirePostRequest();

        // Grab the settings that have been set in the Plugin CP.
        $settings = craft()->plugins->getPlugin('formstack')->getSettings();

        // Set the data array.
        $postItems = array();

        // Loop through each field from the POST.
        foreach ($_POST as $key => $value) {
            /* 
             * Check if it's a multipart field, 
             * i.e. First Name and Last name as 
             * separate fields but combines to Name on post.
             */
            if (strpos($key, '-') != 0) {
                // Grab the location of hyphen in the field name.
                $fieldName = substr($key, 0, strpos($key, '-'));
                /* 
                 * Transform the field name to have an underscore
                 * between `field` and the field ID since
                 * Formstack JSON submission requires that format.
                 */
                $fieldName = 'field_'.substr($fieldName, 5);
                // Grab the name after the hypen.
                $fieldSubName = substr($key, strpos($key, '-') + 1);
                // Add data to the array transforming the hypen name.
                $postItems[$fieldName . '[' . $fieldSubName . ']'] = $value;
            } else {
                // Same as above but for regular fields.
                $fieldName = 'field_'.substr($key, 5);
                $postItems[$fieldName] = $value;
            }
        }

        // File Uploads
        foreach ($_FILES as $key => $value) {
            // Make sure there's a file.
            if (!empty($_FILES[$key]['tmp_name'])) {
                // Get the Field ID set by Formstack.
                $field = key((array)$_FILES);
                // See above for explanation.
                $field = 'field_'.substr($field, 5);
                // Get the path.
                $path = $_FILES[$key]['tmp_name'];
                // Get the name.
                $name = $_FILES[$key]['name'];
                // Get the file.
                $data = file_get_contents($path);
                // Base64 encode the data to send to Formstack.
                $data = $name . ';' . base64_encode($data);
                // Add the file to the main data array.
                $postItems[$field] = $data;
            }
        }

        // Get Form ID.
        $formId = $_POST['form'];

        // Build POST url.
        $postUrl = 'https://www.formstack.com/api/v2/form/' . $formId . '/submission.json';

        // Set up the connection.
        $curlConn = curl_init();
        $hedr = array();
        $hedr[] = 'Accept: multipart/form-data';
        $hedr[] = 'Content-Type: multipart/form-data';
        $hedr[] = 'Authorization: Bearer ' . $settings->oauthToken;
        
        curl_setopt($curlConn, CURLOPT_HTTPHEADER, $hedr);
        curl_setopt($curlConn, CURLOPT_URL, $postUrl);
        curl_setopt($curlConn, CURLOPT_POST, count($_POST));
        curl_setopt($curlConn, CURLOPT_POSTFIELDS, $postItems);

        // Execute it.
        $results = curl_exec($curlConn);
        // Close it.
        curl_close($curlConn);
        // Return the response.
        $this->returnJson($results);
    }
}