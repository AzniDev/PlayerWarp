<?php

namespace AzniDev\PlayerWarp;

use AzniDev\PlayerWarp\commands\PlayerWarpCommand;
use AzniDev\PlayerWarp\manager\DatabaseManager;
use AzniDev\PlayerWarp\manager\WarpManager;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class PlayerWarp extends PluginBase
{
    use SingletonTrait;

    public function onEnable() : void
    {
        self::setInstance($this);
        $this->saveDefaultConfig();
        $this->getDatabaseManager()->getAllWarps();
        $this->getServer()->getCommandMap()->register('PlayerWarpCommand', new PlayerWarpCommand($this->getConfig()->get('command')));
    }

    /**
     * @return DatabaseManager
     */
    public function getDatabaseManager() : DatabaseManager
    {
        return new DatabaseManager();
    }
}