<?php
return array(
     'controllers' => array(
         'invokables' => array(
             'Company\Controller\Company' => 'Company\Controller\CompanyController',
         ),
     ),
     'router' => array(
         'routes' => array(
             'company' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/company[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Company\Controller\Company',
                         'action'     => 'index',
                     ),
                 ),
             ),
         ),
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'company' => __DIR__ . '/../view',
         ),
     ),
 );