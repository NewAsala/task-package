<?php

namespace Composer;

use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-master',
    'version' => 'dev-master',
    'aliases' => 
    array (
    ),
    'reference' => '7b0178a819df55af676c706e0210f99c29d611ce',
    'name' => 'akrad/bridage',
  ),
  'versions' => 
  array (
    'akrad/bridage' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
      ),
      'reference' => '7b0178a819df55af676c706e0210f99c29d611ce',
    ),
    'guzzle/batch' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/cache' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/common' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/guzzle' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '3.9.x-dev',
      ),
      'reference' => 'f7778ed85e3db90009d79725afd6c3a82dab32fe',
    ),
    'guzzle/http' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/inflection' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/iterator' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/log' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/parser' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/plugin' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/plugin-async' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/plugin-backoff' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/plugin-cache' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/plugin-cookie' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/plugin-curlauth' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/plugin-error-response' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/plugin-history' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/plugin-log' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/plugin-md5' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/plugin-mock' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/plugin-oauth' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/service' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'guzzle/stream' => 
    array (
      'replaced' => 
      array (
        0 => '3.9.x-dev',
        1 => 'dev-master',
      ),
    ),
    'symfony/event-dispatcher' => 
    array (
      'pretty_version' => '2.8.x-dev',
      'version' => '2.8.9999999.9999999-dev',
      'aliases' => 
      array (
      ),
      'reference' => 'a77e974a5fecb4398833b0709210e3d5e334ffb0',
    ),
  ),
);







public static function getInstalledPackages()
{
return array_keys(self::$installed['versions']);
}









public static function isInstalled($packageName)
{
return isset(self::$installed['versions'][$packageName]);
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

$ranges = array();
if (isset(self::$installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = self::$installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}





public static function getVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['version'])) {
return null;
}

return self::$installed['versions'][$packageName]['version'];
}





public static function getPrettyVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return self::$installed['versions'][$packageName]['pretty_version'];
}





public static function getReference($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['reference'])) {
return null;
}

return self::$installed['versions'][$packageName]['reference'];
}





public static function getRootPackage()
{
return self::$installed['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
}
}
