<?php

use CRM_Replytoemail_ExtensionUtil as E;

return [
  'replytoemail_source_contact_id' => [
    'name' => 'replytoemail_source_contact_id',
    'group' => 'replytoemai',
    'type' => 'Integer',
    'is_domain' => 1,
    'is_contact' => 1,
    'title' => E::ts('Source Contact ID for email Replies'),
    'html_type' => 'entity_reference',
    'default' => 0,
    'entity_reference_options' => ['entity' => 'Contact', 'select' => ['minimumInputLength' => 0]],
    'settings_pages' => ['replytoemail' => ['weight' => 10]],
    'add' => 1.0,
  ],
];
