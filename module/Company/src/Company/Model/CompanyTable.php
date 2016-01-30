<?php
namespace Company\Model;

 use Zend\Db\TableGateway\TableGateway;

 class CompanyTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();
         return $resultSet;
     }

     public function getCompany($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function saveCompany(Company $company)
     {
         $data = array(
             'name' => $company->name,
             'type'  => $company->type,
             'activity'  => $company->activity,
             'contact'  => $company->contact,
         );

         $id = (int) $company->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getCompany($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('Company id does not exist');
             }
         }
     }

     public function deleteCompany($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
 }