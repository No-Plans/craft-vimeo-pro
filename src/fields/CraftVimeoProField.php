<?php
/**
 * Craft Vimeo Pro plugin for Craft CMS 3.x
 *
 * Save video data as field from Vimeo Pro API
 *
 * @link      https://no-plans.com/
 * @copyright Copyright (c) 2020 No Plans
 */

namespace noplans\craftvimeopro\fields;

use noplans\craftvimeopro\CraftVimeoPro;
use noplans\craftvimeopro\assetbundles\craftvimeoprofieldfield\CraftVimeoProFieldFieldAsset;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use yii\db\Schema;
use craft\helpers\Json;

/**
 * @author    No Plans
 * @package   CraftVimeoPro
 * @since     1.0.0
 */
class CraftVimeoProField extends Field
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $videoData;

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('craft-vimeo-pro', 'Vimeo Video');
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            ['videoData', 'string']
        ]);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue(mixed $value, ?\craft\base\ElementInterface $element = null): mixed
    {
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function serializeValue(mixed $value, ?\craft\base\ElementInterface $element = null): mixed
    {
        return parent::serializeValue($value, $element);
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): ?string
    {
        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'craft-vimeo-pro/_components/fields/CraftVimeoProField_settings',
            [
                'field' => $this,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml(mixed $value, ?\craft\base\ElementInterface $element = null): string
    {
        // Register our asset bundle
        Craft::$app->getView()->registerAssetBundle(CraftVimeoProFieldFieldAsset::class);

        // Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);
        $vimeoAuth = CraftVimeoPro::$plugin->getSettings()->vimeoAuth;

        // Variables to pass down to our field JavaScript to let it namespace properly
        $jsonVars = [
            'id' => $id,
            'name' => $this->handle,
            'namespace' => $namespacedId,
            'vimeoAuth' => $vimeoAuth,
            'prefix' => Craft::$app->getView()->namespaceInputId(''),
            ];
        $jsonVars = Json::encode($jsonVars);
        Craft::$app->getView()->registerJs("$('#{$namespacedId}-field').CraftVimeoProCraftVimeoProField(" . $jsonVars . ");");

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'craft-vimeo-pro/_components/fields/CraftVimeoProField_input',
            [
                'name' => $this->handle,
                'value' => $value,
                'field' => $this,
                'id' => $id,
                'namespacedId' => $namespacedId,
            ]
        );
    }
}
