<?php 
namespace Company\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Company\Model\Company;       
use Company\Model\Activity;       
use Company\Form\CompanyForm; 

class CompanyController extends AbstractActionController
{
     protected $companyTable;

     public function indexAction()
     {
          return new ViewModel(array(
             'companys' => $this->getCompanyList()->fetchAll(),
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

          return new ViewModel(array(
             'company' => $this->getCompanyList()->getCompany($id),
         ));
     }

     public function addAction()
     {
         $form = new CompanyForm();
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

         // Get the Company with the specified id.  An exception is thrown
         // if it cannot be found, in which case go to the index page.
         try {
             $company = $this->getCompanyList()->getCompany($id);
         }
         catch (\Exception $ex) {
             return $this->redirect()->toRoute('company', array(
                 'action' => 'index'
             ));
         }

         $form  = new CompanyForm();
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
}