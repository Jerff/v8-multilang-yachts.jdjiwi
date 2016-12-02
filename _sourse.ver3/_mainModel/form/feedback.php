<?php

cmfLoad('form/cmfContactForm');
$contact = new cmfContactForm();
cmfCommand::set('isMainForm');
$this->assing('contact', $contact);
$this->assing('form', $contact->getForm());
?>