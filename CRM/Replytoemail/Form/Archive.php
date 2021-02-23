<?php

use CRM_Replytoemail_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Replytoemail_Form_Archive extends CRM_Core_Form {

  public $_activityId;

  public function preProcess()
  {
    $this->_activityId = CRM_Utils_Request::retrieve('activityId', 'Positive');
    parent::preProcess();
  }

  public function buildQuickForm() {

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => E::ts('Archive'),
        'isDefault' => TRUE,
      ),
    ));
    parent::buildQuickForm();
  }

  public function postProcess() {
    civicrm_api3('Activity', 'create', [
      'id' => $this->_activityId,
      'source_contact_id' => "user_contact_id",
      'status_id' => "Completed",
    ]);
    CRM_Core_Session::setStatus(ts('This activity has been archived!'), ts('Archived Successfully'), 'success');
    parent::postProcess();
  }

}
