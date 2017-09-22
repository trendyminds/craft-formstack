<?php
/**
 * Formstack plugin for Craft CMS
 *
 * Formstack FieldType
 *
 * @author    TrendyMinds
 * @copyright Copyright (c) 2017 TrendyMinds
 * @link      https://trendyminds.com
 * @package   Formstack
 * @since     1.0.0
 */

namespace Craft;

class Formstack_FormstackFieldType extends BaseFieldType
{
    protected $value = array();

    /**
     * Returns the name of the fieldtype.
     *
     * @return mixed
     */
    public function getName()
    {
        return Craft::t('Formstack Form');
    }

    /**
     * Returns the content attribute config.
     *
     * @return mixed
     */
    public function defineContentAttribute()
    {
        return AttributeType::Mixed;
    }

    /**
     * Returns the field's input HTML.
     *
     * @param string $name
     * @param mixed  $value
     * @return mixed
     */
    public function getInputHtml($name, $value)
    {

        // If value is null, we set it to an array to prevent template errors.
        if (!$value) {
            $value = array();
        }

        // Get the Form list.
        $forms = craft()->formstack->getForms();

        // Add each form into an array to be used as options.
        foreach ($forms as $form) {
            $options[] = array(
                'label' => $form->name,
                'value' => $form->id
              );
        }
        
        // Only load assets in admin.
        if (craft()->request->isCpRequest()) {
            craft()->templates->includeCssResource('formstack/css/Formstack_FormstackFieldType.css');
        }

        // Get the final namespaced ID from Craft.
        $namespacedId = craft()->templates->namespaceInputId($name);

        // Set variables to pass to template.
        $variables = array(
            'name' => $name,
            'values' => $value,
            'options' => $options,
            'namespacedId' => $namespacedId,
        );

        // Return the template with the variables.
        return craft()->templates->render('formstack/fields/Formstack_FormstackFieldType.twig', $variables);
    }

    /**
     * Returns the input value as it should be saved to the database.
     *
     * @param mixed $value
     * @return mixed
     */
    public function prepValueFromPost($value)
    {
        return json_encode($value);
    }

    /**
     * Prepares the field's value for use.
     *
     * @param mixed $value
     * @return mixed
     */
    public function prepValue($value)
    {
        return $value;
    }
}