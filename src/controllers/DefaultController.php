<?php
/**
 * Craft Vimeo Pro plugin for Craft CMS 3.x
 *
 * Save video data as field from Vimeo Pro API
 *
 * @link      https://no-plans.com/
 * @copyright Copyright (c) 2020 No Plans
 */

namespace noplans\craftvimeopro\controllers;

use noplans\craftvimeopro\CraftVimeoPro;

use Craft;
use craft\web\Controller;

/**
 * @author    No Plans
 * @package   CraftVimeoPro
 * @since     1.0.0
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected array|int|bool $allowAnonymous = true;

    // Public Methods
    // =========================================================================

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $result = 'Welcome to the DefaultController actionIndex() method';

        return $result;
    }

    /**
     * @return mixed
     */
    public function actionDoSomething()
    {
        $result = 'Welcome to the DefaultController actionDoSomething() method';

        return $result;
    }
}
