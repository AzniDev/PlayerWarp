<?php

namespace AzniDev\PlayerWarp\manager;

use AzniDev\PlayerWarp\PlayerWarp;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class DatabaseManager
{

    /** Config $database */
    private Config $database;
    public function __construct()
    {
        $this->database = new Config(PlayerWarp::getInstance()->getDataFolder() . 'database.json', Config::JSON, []);
    }

    /**
     * @param Player $player
     * @param string $warpName
     * @return void
     */
    public function setPlayerWarp(Player $player, string $warpName): void
    {
        $allPlayerWarps = $this->getAllPlayerWarps($player);

        $allPlayerWarps[$warpName] = [
            'world' => $player->getWorld()->getFolderName(),
            'x' => $player->getPosition()->getX(),
            'y' => $player->getPosition()->getY(),
            'z' => $player->getPosition()->getZ()
        ];
        $this->database->set($player->getName(), $allPlayerWarps);
        $this->database->save();
    }

    /**
     * @param Player $player
     * @param string $warpName
     * @return void
     */
    public function deletePlayerWarp(Player $player, string $warpName) : void
    {
        $this->database->removeNested($player->getName() . '.' . $warpName);
        $this->database->save();
    }

    /**
     * @param Player $player
     * @param string $warpName
     * @return PlayerWarpManager
     */
    public function getPlayerWarp(Player $player, string $warpName) : PlayerWarpManager
    {
        return new PlayerWarpManager($player, $this->database->get($player->getName())[$warpName]);
    }

    /**
     * @param Player $player
     * @return array
     */
    public function getAllPlayerWarps(Player $player) : array
    {
        return $this->database->get($player->getName(), []);
    }
}