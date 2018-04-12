<?php

$config['event']['SiteOrder'] = array(
  'entity_what' => 'e.*, siteorder_status.*, manager.*',
  'additional_email_entity_rel' => 'manager',
  'additional_email_entity_field' => 'email',
);

$config['event']['SupplierRequest'] = array(
  'entity_what' => 'e.*, supplier_request_status.*',
);

