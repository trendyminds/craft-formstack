<?php
/**
 * Formstack plugin for Craft CMS
 *
 * Formstack Variable
 *
 * @author    TrendyMinds
 * @copyright Copyright (c) 2017 TrendyMinds
 * @link      https://trendyminds.com
 * @package   Formstack
 * @since     1.0.0
 */

namespace Craft;

class FormstackVariable
{
    /**
     * Whatever you want to output to a Twig template can go into a Variable method. You can have as many variable
     * functions as you want.  From any Twig template, call it like this:
     *
     *  {{ craft()->formstack->getFormById(FormID) }}
     *
     * @param mixed $options
     */
    public function getForms($options = array())
    {
        return craft()->formstack->getForms($options);
    }
  
    public function getFormById($options = array())
    {
        return craft()->formstack->getFormById($options);
    }
  
    public function getWholeFormById($options = array())
    {
        return craft()->formstack->getWholeFormById($options);
    }
}