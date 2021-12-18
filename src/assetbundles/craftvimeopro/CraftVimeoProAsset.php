<?php
/**
 * Craft Vimeo Pro plugin for Craft CMS 3.x
 *
 * Save video data as field from Vimeo Pro API
 *
 * @link      https://no-plans.com/
 * @copyright Copyright (c) 2020 No Plans
 */

namespace noplans\craftvimeopro\assetbundles\craftvimeopro;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    No Plans
 * @package   CraftVimeoPro
 * @since     1.0.0
 */
class CraftVimeoProAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@noplans/craftvimeopro/assetbundles/craftvimeopro/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/CraftVimeoPro.js',
        ];

        $this->css = [
            'css/CraftVimeoPro.css',
        ];

        parent::init();
    }
}
