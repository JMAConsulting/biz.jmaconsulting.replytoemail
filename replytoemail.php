<?php

require_once 'replytoemail.civix.php';
// phpcs:disable
use CRM_Replytoemail_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function replytoemail_civicrm_config(&$config) {
  _replytoemail_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function replytoemail_civicrm_xmlMenu(&$files) {
  _replytoemail_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function replytoemail_civicrm_install() {
  $inbound = civicrm_api3('OptionValue', 'get', [
    'sequential' => 1,
    'return' => ["id"],
    'option_group_id' => "activity_type",
    'name' => "Inbound Email",
  ]);
  if (!empty($inbound['id'])) {
    civicrm_api3('OptionValue', 'create', [
      'id' => $inbound['id'],
      'option_group_id' => "activity_type",
      'description' => "",
      'is_reserved' => 1,
      'is_active' => 1,
      'icon' => 'crm-i fa-paper-plane-o',
    ]);
  }
  _replytoemail_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function replytoemail_civicrm_postInstall() {
  _replytoemail_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function replytoemail_civicrm_uninstall() {
  _replytoemail_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function replytoemail_civicrm_enable() {
  _replytoemail_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function replytoemail_civicrm_disable() {
  _replytoemail_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function replytoemail_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _replytoemail_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function replytoemail_civicrm_managed(&$entities) {
  _replytoemail_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function replytoemail_civicrm_caseTypes(&$caseTypes) {
  _replytoemail_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function replytoemail_civicrm_angularModules(&$angularModules) {
  _replytoemail_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function replytoemail_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _replytoemail_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function replytoemail_civicrm_entityTypes(&$entityTypes) {
  _replytoemail_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function replytoemail_civicrm_themes(&$themes) {
  _replytoemail_civix_civicrm_themes($themes);
}

/**
 * Implementation of hook_civicrm_buildForm
 *
 */
function replytoemail_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Activity_Form_Activity') {
    $activityTypes = CRM_Activity_BAO_Activity::buildOptions('activity_type_id', 'get', ['flip' => TRUE]);
    if ($form->_activityTypeId == $activityTypes['Inbound Email']  && $form->_action & CRM_Core_Action::VIEW) {
      // Add the reply button to the form.
      CRM_Core_Region::instance('page-body')->add(array(
        'template' => 'CRM/Replytoemail/Reply.tpl',
      ));
    }
  }
  if ($formName == 'CRM_Contact_Form_Task_Email' && $form->_context == 'sendReply') {
    $activityId = CRM_Utils_Request::retrieve('activityId', 'Positive');
    $form->add('hidden', 'original_activity_id');
    if (!empty($activityId)) {
      $defaults = ['original_activity_id' => $activityId];
      // Fetch the subject.
      $subject = civicrm_api3('Activity', 'get', [
        'id' => $activityId,
        'return' => ['subject'],
        'sequential' => 1,
      ]);
      if (!empty($subject['values'])) {
        $defaults['subject'] = 'RE: ' . $subject['values'][0]['subject'];
      }
      $form->setDefaults($defaults);
    }
  }
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_postProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postProcess
 */
function replytoemail_civicrm_postProcess($formName, &$form) {
  if ($formName == 'CRM_Contact_Form_Task_Email' && $form->_context == 'sendReply') {
    if (!empty($form->_submitValues['original_activity_id'])) {
      // We set the status of the inbound email activity to completed.
      civicrm_api3('Activity', 'create', [
        'id' => $form->_submitValues['original_activity_id'],
        'source_contact_id' => "user_contact_id",
        'status_id' => "Completed",
      ]);
    }
  }
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function replytoemail_civicrm_navigationMenu(&$menu) {
//  _replytoemail_civix_insert_navigation_menu($menu, 'Mailings', array(
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ));
//  _replytoemail_civix_navigationMenu($menu);
//}