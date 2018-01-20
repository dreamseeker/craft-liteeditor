<?php
/**
 * LiteEditor plugin for Craft CMS
 *
 * LiteEditor Model
 *
 * --snip--
 * Models are containers for data. Just about every time information is passed between services, controllers, and
 * templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 * --snip--
 *
 * @author    dreamseeker
 * @copyright Copyright (c) 2018 dreamseeker
 * @link      https://github.com/dreamseeker
 * @package   LiteEditor
 * @since     1.0.0
 */

namespace Craft;

class LiteEditorModel extends BaseModel
{
    /**
     * Defines this model's attributes.
     *
     * @return array
     */
    protected function defineAttributes()
    {
        return array_merge(parent::defineAttributes(), array(
            'text'     => array(AttributeType::String, 'default' => ''),
        ));
    }

}
