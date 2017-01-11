<?php

namespace MauticPlugin\FeedBundle;

use Doctrine\DBAL\Schema\Schema;
use Mautic\CoreBundle\Factory\MauticFactory;
use Mautic\PluginBundle\Bundle\PluginBundleBase;
use Mautic\PluginBundle\Entity\Plugin;

class FeedBundle extends PluginBundleBase
{

    public static function onPluginUpdate(Plugin $plugin, MauticFactory $factory, $metadata = null, Schema $installedSchema = null)
    {
//        if ($metadata !== null) {
//            self::updatePluginSchema($metadata, $installedSchema, $factory);
//        }
    }

}