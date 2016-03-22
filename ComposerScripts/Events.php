<?php
namespace ComposerScripts;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class Events
{
    public static function postPackageInstall(PackageEvent $event)
    {
        $installedPackage = $event->getOperation()->getPackage();
        echo $installedPackage->getName().' installato';
    }
}
