<?php
/**
 * Formstack plugin for Craft CMS
 *
 * Formstack Service
 *
 * @author    TrendyMinds
 * @copyright Copyright (c) 2017 TrendyMinds
 * @link      https://trendyminds.com
 * @package   Formstack
 * @since     1.0.0
 */

namespace Craft;

class FormstackService extends BaseApplicationComponent
{
    protected $oauthToken;

    /**
     * This function can literally be anything you want, and you can have as many service functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     craft()->formstack->exampleService()
     */
    public function __construct()
    {
        $settings = craft()->plugins->getPlugin('formstack')->getSettings();

        $this->oauthToken = $settings->oauthToken;
    }

    /**
     * Get all forms from Formstack.
     *
     * @return array Array of Formstack Forms
     */
    public function getForms($options = array())
    {
        $fsFormUrl = 'https://www.formstack.com/api/v2/form.json?oauth_token=' . $this->oauthToken;

        try {
            $fsFormResults = @file_get_contents($fsFormUrl);

            if ($fsFormResults === false) {
                return "Unable to get forms at this time.";
            } else {
                $fsFormObj = json_decode($fsFormResults);
    
                return $fsFormObj->forms;
            }    
        } catch(\Exception $e) {
            return;
        }
    }
    
    /** 
     * Extract just the Form from Formstack API data.
     *
     * @param $data: Formstack `html` string
     * @param $startEl: Element to start from
     * @param $endEl: Element to end on
     * @return string
     */
    private function extractForm($data, $startEl, $endEl)
    {
        // Get the Start and End string number.
        $start = strpos($data, $startEl);
        $end = strpos($data, $endEl);

        // Return just the Form element.
        $form = substr($data, $start, ($end - $start) + strlen($endEl));

        return $form;
    }

    /** 
     * Get a specific form from Formstack that
     * includes just the html Form element and contents.
     *
     * @param $options: Formstack Form ID
     * @return string
     */
    public function getFormById($options)
    {
        // Grab the settings that have been set in the Plugin CP.
        $settings = craft()->plugins->getPlugin('formstack')->getSettings();
        
        // Set and Get the endpoint by passing the ID and the oauth token.
        $fsUrl = 'https://www.formstack.com/api/v2/form/' . $options . '?oauth_token=' . $settings->oauthToken;
        $fsResult = file_get_contents($fsUrl);
        $fsDecoded = json_decode($fsResult);
        $fsHtml = $fsDecoded->html;

        // Specify the Start and End elements.
        $formStartEl = '<form';
        $formEndEl = '</form>';

        // Return extracted Form.
        $final = $this->extractForm($fsHtml, $formStartEl, $formEndEl);

        return $final;
    }
    
    /** 
     * Get a specific form from Formstack which
     * returns the entire Formstack HTML embed.
     *
     * @param $options: Formstack Form ID
     * @return string
     */
    public function getWholeFormById($options)
    {
        $settings = craft()->plugins->getPlugin('formstack')->getSettings();

        $fsUrl = 'https://www.formstack.com/api/v2/form/' . $options . '?oauth_token=' . $settings->oauthToken;
        $fsResult = file_get_contents($fsUrl);
        $fsDecoded = json_decode($fsResult);

        return $fsDecoded->html;
    }
}