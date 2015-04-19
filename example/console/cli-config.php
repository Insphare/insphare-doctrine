<?php
use Insphare\ORM\Doctrine\Factory;

include_once dirname(__DIR__).DIRECTORY_SEPARATOR.'exampleBootstrap.php';
$entityManager = Factory::getEntityManager(true);

return $helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($entityManager->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager)
));
