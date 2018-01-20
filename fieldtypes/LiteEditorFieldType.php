<?php
/**
 * LiteEditor plugin for Craft CMS
 *
 * LiteEditor FieldType
 *
 * --snip--
 * Whenever someone creates a new field in Craft, they must specify what type of field it is. The system comes with
 * a handful of field types baked in, and we’ve made it extremely easy for plugins to add new ones.
 *
 * https://craftcms.com/docs/plugins/field-types
 * --snip--
 *
 * @author    dreamseeker
 * @copyright Copyright (c) 2018 dreamseeker
 * @link      https://github.com/dreamseeker
 * @package   LiteEditor
 * @since     1.0.0
 */

namespace Craft;

class LiteEditorFieldType extends BaseFieldType
{
    /**
     * Returns the name of the fieldtype.
     *
     * @return mixed
     */
    public function getName()
    {
        return Craft::t('LiteEditor');
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
     * @return string
     */
    public function getInputHtml($name, $value)
    {
        if (!$value)
            $value = new LiteEditorModel();

        $id = craft()->templates->formatInputId($name);
        $namespacedId = craft()->templates->namespaceInputId($id);

        // include Library Source
        craft()->templates->includeCssResource('liteeditor/css/lite-editor.min.css');
        craft()->templates->includeJsResource('liteeditor/js/lite-editor.min.js');

        // initialized Lite Editor
        $configJson = $this->_getLiteEditorConfigJson();
        craft()->templates->includeJs('new LiteEditor("#' . $namespacedId . '",' . $configJson . ');');

        // Variables to pass down to our rendered template
        $variables = array(
            'id' => $id,
            'name' => $name,
            'namespaceId' => $namespacedId,
            'values' => $value
            );

        return craft()->templates->render('liteeditor/fields/LiteEditorFieldType.twig', $variables);
    }

    /**
     * Returns the input value as it should be saved to the database.
     *
     * @param mixed $value
     * @return mixed
     */
    public function prepValueFromPost($value)
    {
        return $value;
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

    // Field Settings
    // =========================================================================

    /**
     * Prepares the field's value for use.
     *
     * @param mixed $value
     * @return mixed
     */
    public function getSettingsHtml()
    {
        return craft()->templates->render('liteeditor/fields/LiteEditorSetting.twig', array(
            'settings' => $this->getSettings(),
            'liteEditorConfigOptions' => $this->_getLiteEditorConfigOptions()
        ));
    }

    /**
     * @inheritDoc BaseSavableComponentType::defineSettings()
     *
     * @return array
     */
    protected function defineSettings()
    {
        return array(
            'configFile' => AttributeType::String
        );
    }

    /**
     * Returns the available Lite Editor config options.
     *
     * @return array
     */
    private function _getLiteEditorConfigOptions()
    {
        $options = array('' => Craft::t('Default'));
        $path = craft()->path->getConfigPath() . 'liteeditor/';

        if (IOHelper::folderExists($path))
        {
            $configFiles = IOHelper::getFolderContents($path, false, '\.json$');

            if (is_array($configFiles))
            {
                foreach ($configFiles as $file)
                {
                    $options[IOHelper::getFileName($file)] = IOHelper::getFileName($file, false);
                }
            }
        }

        return $options;
    }

    /**
     * Returns the LiteEditor config JSON used by this field.
     *
     * @return string
     */
    private function _getLiteEditorConfigJson()
    {
        if ($this->getSettings()->configFile)
        {
            $configPath = craft()->path->getConfigPath() . 'liteeditor/' . $this->getSettings()->configFile;
            $json = IOHelper::getFileContents($configPath);
        }

        if (empty($json))
        {
            $json = '{}';
        }

        return $json;
    }
}
