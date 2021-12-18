<?php
/**
 * Craft Vimeo Pro plugin for Craft CMS 3.x
 *
 * Save video data as field from Vimeo Pro API
 *
 * @link      https://no-plans.com/
 * @copyright Copyright (c) 2020 No Plans
 */

namespace noplans\craftvimeopro\records;

use noplans\craftvimeopro\CraftVimeoPro;

use Craft;
use craft\db\ActiveRecord;

/**
 * @author    No Plans
 * @package   CraftVimeoPro
 * @since     1.0.0
 */
class CraftVimeoProRecord extends ActiveRecord
{
    // Public Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%craftvimeopro_craftvimeoprorecord}}';
    }
}
