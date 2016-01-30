<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Company\Model\Company;       
use Company\Form\CompanyForm; 


class IndexController extends AbstractActionController
{
    public function indexAction()
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
}
