<?php
/**
 * @package     CiamLoginRadius.Component
 * @subpackage  com_ciamloginradius
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::register('JHtmlUsers', JPATH_COMPONENT . '/helpers/html/users.php');
JHtml::register('users.spacer', array('JHtmlUsers', 'spacer'));
JHtml::register('users.helpsite', array('JHtmlUsers', 'helpsite'));
JHtml::register('users.templatestyle', array('JHtmlUsers', 'templatestyle'));
JHtml::register('users.admin_language', array('JHtmlUsers', 'admin_language'));
JHtml::register('users.language', array('JHtmlUsers', 'language'));
JHtml::register('users.editor', array('JHtmlUsers', 'editor'));

$fields = $this->form->getFieldset('params');
if (count($fields))
{
    ?>
    <fieldset id="users-profile-custom">
        <legend><?php echo JText::_('COM_USERS_SETTINGS_FIELDSET_LABEL'); ?></legend>
        <dl class="dl-horizontal">
            <?php
            foreach ($fields as $field)
            {
                if (!$field->hidden)
                {
                    ?>
                    <dt><?php echo $field->title; ?></dt>
                    <dd>
                        <?php
                        if (JHtml::isRegistered('users.' . $field->id))
                        {
                            echo JHtml::_('users.' . $field->id, $field->value);
                        } elseif (JHtml::isRegistered('users.' . $field->fieldname))
                        {
                            echo JHtml::_('users.' . $field->fieldname, $field->value);
                        } elseif (JHtml::isRegistered('users.' . $field->type))
                        {
                            echo JHtml::_('users.' . $field->type, $field->value);
                        } else
                        {
                            echo JHtml::_('users.value', $field->value);
                        }
                        ?>
                    </dd>
                <?php
                }
            }
            ?>
        </dl>
    </fieldset>
<?php } ?>
