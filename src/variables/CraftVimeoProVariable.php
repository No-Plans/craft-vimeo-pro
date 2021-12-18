<?php
/**
 * Craft Vimeo Pro plugin for Craft CMS 3.x
 *
 * Save video data as field from Vimeo Pro API
 *
 * @link      https://no-plans.com/
 * @copyright Copyright (c) 2020 No Plans
 */

namespace noplans\craftvimeopro\variables;

use noplans\craftvimeopro\CraftVimeoPro;

use Craft;

/**
 * Craft Vimeo Pro Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.craftVimeoPro }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    No Plans
 * @package   CraftVimeoPro
 * @since     1.0.0
 */

class CraftVimeoProVariable
{
    // Private Methods
    // =========================================================================


    // Public Methods
    // =========================================================================

    public function videoObj($data)
    {
        return json_decode($data);
    }

}
