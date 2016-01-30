<?php
namespace Company\Form;

 use Zend\Form\Form;

 class CompanyForm extends Form
 {
     public function __construct($name = null)
     {

//var_dump(Company\Model\ActivityTable::getActivityList());

         // we want to ignore the name passed
         parent::__construct('company');

         $this->add(array(
             'name' => 'id',
             'type' => 'Hidden',
         ));
         $this->add(array(
             'name' => 'name',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Company Name',
             ),
         ));
         $this->add(array(
             'type' => 'select',
             'name' => 'type',
             'options' => array(
                     'label' => 'Type',
                     'value_options' => array(
                             '0' => 'French',
                             '1' => 'English',
                             '2' => 'Japanese',
                             '3' => 'Chinese',
                     ),
             )
         ));
         $this->add(array(
             'name' => 'activity',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Activity',
             ),
         ));
         $this->add(array(
             'name' => 'contact',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Contact',
             ),
         ));
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Go',
                 'id' => 'submitbutton',
             ),
         ));
     }
 }