<?php
/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace BjyAuthorize;

use Zend\ServiceManager\ServiceLocatorInterface;

return array(
    'initializers' => array(
        function ($instance, ServiceLocatorInterface $serviceLocator) {
            if ($instance instanceof Service\AuthorizeAwareInterface) {
                /* @var $authorize \BjyAuthorize\Service\Authorize */
                $authorize = $serviceLocator->get('BjyAuthorize\Service\Authorize');

                $instance->setAuthorizeService($authorize);
            }
        }
    ),
    'factories' => array(
        'BjyAuthorize\Service\Authorize' => 'BjyAuthorize\Service\AuthorizeFactory',

        'BjyAuthorize\Provider\Identity\ZfcUserZendDb' => function (ServiceLocatorInterface $serviceLocator) {
            /* @var $adapter \Zend\Db\Adapter\Adapter */
            $adapter     = $serviceLocator->get('zfcuser_zend_db_adapter');
            /* @var $userService \ZfcUser\Service\User */
            $userService = $serviceLocator->get('zfcuser_user_service');
            $provider    = new Provider\Identity\ZfcUserZendDb($adapter, $userService);

            return $provider;
        },

        'BjyAuthorize\Provider\Identity\ZfcUserDoctrine' => function (ServiceLocatorInterface $serviceLocator) {
            /* @var $objectManager \Doctrine\ORM\EntityManager */
            $objectManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
            /* @var $userService \ZfcUser\Service\User */
            $userService   = $serviceLocator->get('zfcuser_user_service');
            $provider      = new Provider\Identity\ZfcUserDoctrine($objectManager, $userService);

            return $provider;
        },

        'BjyAuthorize\View\UnauthorizedStrategy' => function (ServiceLocatorInterface $serviceLocator) {
            $config = $serviceLocator->get('Config');

            return new View\UnauthorizedStrategy($config['bjyauthorize']['template']);
        },

        'BjyAuthorize\Provider\Role\ZendDb' => function (ServiceLocatorInterface $serviceLocator) {
            return new Provider\Role\ZendDb(array(), $serviceLocator);
        },

        'BjyAuthorize\Provider\Role\Doctrine' => function (ServiceLocatorInterface $serviceLocator) {
            /* @var $objectManager \Doctrine\ORM\EntityManager */
            $objectManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

            return new Provider\Role\Doctrine(array(), $objectManager);
        },
    ),
);