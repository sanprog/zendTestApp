<?php
namespace Company;

 use Company\Model\Company;
 use Company\Model\CompanyTable;
 use Zend\Db\ResultSet\ResultSet;
 use Zend\Db\TableGateway\TableGateway;


 class Module 
 {
     public function getAutoloaderConfig()
     {
         return array(
             'Zend\Loader\ClassMapAutoloader' => array(
                 __DIR__ . '/autoload_classmap.php',
             ),
             'Zend\Loader\StandardAutoloader' => array(
                 'namespaces' => array(
                     __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                 ),
             ),
         );
     }

     public function getConfig()
     {
         return include __DIR__ . '/config/module.config.php';
     }

     public function getServiceConfig()
     {
         return array(
             'factories' => array(
                 'Company\Model\CompanyTable' =>  function($sm) {
                     $tableGateway = $sm->get('CompanyTableGateway');
                     $table = new CompanyTable($tableGateway);
                     return $table;
                 },
                 'CompanyTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new Company());
                     return new TableGateway('company', $dbAdapter, null, $resultSetPrototype);
                 },
             ),
         );
     }
 }