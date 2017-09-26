<?php
/**
 * Formstack plugin for Craft CMS
 *
 * Formstack Controller
 *
 * @author    TrendyMinds
 * @copyright Copyright (c) 2017 TrendyMinds
 * @link      https://trendyminds.com
 * @package   Formstack
 * @since     1.0.0
 */

namespace Craft;

class Formstack_GetFormController extends BaseController
{

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     * @access protected
     */
    protected $allowAnonymous = false;

    public function actionReturnForm($data)
    {
        $form = craft()->formstack->getFormById($data);
        $this->returnJson($form);
    }
}