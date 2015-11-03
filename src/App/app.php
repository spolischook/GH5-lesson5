<?php

require __DIR__.'/../../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

/**
 * @return array
 * @throws
 */
function getParameters()
{
    if (!$parametersYmlFile = realpath(__DIR__."/../../parameters.yml")) {
        throw \InvalidArgumentException('Can\'t find parameters.yml file');
    }

    return Yaml::parse($parametersYmlFile);
}

/**
 * @return \MongoDB
 */
function initDb()
{
    $m = new \MongoClient();
    $dbName = getParameters()['database']['name'];

    $db = $m->$dbName;

    return $db;
}
