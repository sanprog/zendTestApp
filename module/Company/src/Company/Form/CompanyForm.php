<?php
namespace Company\Form;

 use Zend\Form\Form;
 use Zend\Db\Adapter\AdapterInterface;
 use Zend\Db\Adapter\Adapter;

 class CompanyForm extends Form
 {
     protected $adapter;


     public function __construct(AdapterInterface $dbAdapter)
     {

        $this->adapter =$dbAdapter;
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
                     'value_options' => $this->getOptionsForSelect(),
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
     public function getOptionsForSelect()
     {
     $dbAdapter = $this->adapter;
     $sql = 'SELECT id,name FROM type';
     $statement = $dbAdapter->query($sql);
     $result = $statement->execute();

     $selectData = array();

     foreach ($result as $res) {
        $selectData[$res['id']] = $res['name'];
     }
     return $selectData;
     }
 }