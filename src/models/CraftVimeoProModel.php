<?php
/**
 * Craft Vimeo Pro plugin for Craft CMS 3.x
 *
 * Save video data as field from Vimeo Pro API
 *
 * @link      https://no-plans.com/
 * @copyright Copyright (c) 2020 No Plans
 */

namespace noplans\craftvimeopro\models;

use noplans\craftvimeopro\CraftVimeoPro;

use Craft;
use craft\base\Model;

/**
 * @author    No Plans
 * @package   CraftVimeoPro
 * @since     1.0.0
 */
class CraftvimeoproModel extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $vimeoAuth;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['vimeoAuth', 'string']
        ];
    }
}
