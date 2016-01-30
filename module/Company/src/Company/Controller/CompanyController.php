<?php 
namespace Company\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Company\Model\Company;       
use Company\Form\CompanyForm; 

class CompanyController extends AbstractActionController
{
     protected $companyTable;

     public function indexAction()
     {
        $typeList = $this->getTypeList();
          return new ViewModel(array(
             'companys' => $this->getCompanyList()->fetchAll(),
             'typeList' => $typeList,
         ));
     }

     public function viewAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('company', array(
                 'action' => 'index'
             ));
         }
         $typeList = $this->getTypeList();
         //set type by index
         $company = $this->getCompanyList()->getCompany($id);
         $company->type = $typeList[$company->type];

          return new ViewModel(array(
             'company' => $company,
         ));
     }

     public function addAction()
     {
         $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
         $form  = new CompanyForm($dbAdapter);
         $form->get('submit')->setValue('Add');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $company = new Company();
             $form->setInputFilter($company->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $company->exchangeArray($form->getData());
                 $this->getCompanyList()->saveCompany($company);

                 // Redirect to list of companys
                 return $this->redirect()->toRoute('company');
             }
         }
         return array('form' => $form);
     }

     public function editAction()
     {

         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('company', array(
                 'action' => 'add'
             ));
         }

        
         try {
             $company = $this->getCompanyList()->getCompany($id);
         }
         catch (\Exception $ex) {
             return $this->redirect()->toRoute('company', array(
                 'action' => 'index'
             ));
         }

         $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
         $form  = new CompanyForm($dbAdapter);
         $form->bind($company);
         $form->get('submit')->setAttribute('value', 'Edit');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setInputFilter($company->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $this->getCompanyList()->saveCompany($company);

                 // Redirect to list of companys
                 return $this->redirect()->toRoute('company');
             }
         }

         return array(
             'id' => $id,
             'form' => $form,
         );
     }

     public function deleteAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('company');
         }

         $request = $this->getRequest();
         if ($request->isPost()) {
             $del = $request->getPost('del', 'No');

             if ($del == 'Yes') {
                 $id = (int) $request->getPost('id');
                 $this->getCompanyList()->deleteCompany($id);
             }

             // Redirect to list of albums
             return $this->redirect()->toRoute('company');
         }

         return array(
             'id'    => $id,
             'company' => $this->getCompanyList()->getCompany($id)
         );
     }

     public function getCompanyList()
     {
         if (!$this->companyTable) {
             $sm = $this->getServiceLocator();
             $this->companyTable = $sm->get('Company\Model\CompanyTable');
         }
         return $this->companyTable;
     }
     public function getTypeList()
     {
             $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
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