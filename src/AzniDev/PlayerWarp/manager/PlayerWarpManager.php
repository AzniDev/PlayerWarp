<?php

namespace AzniDev\PlayerWarp\manager;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\World;

class PlayerWarpManager
{

    /** string $owner */
    private string $owner;

    /** array $condition */
    private array $condition;

    public function __construct(string $owner, array $condition)
    {
        $this->owner = $owner;
        $this->condition = $condition;
    }

    /**
     * @return string
     */
    public function getOwner() : string
    {
        return $this->owner;
    }

    /**
     * @return World
     */
    public function getWorld() : World
    {
        return Server::getInstance()->getWorldManager()->getWorldByName($this->condition['world']);
    }

    /**
     * @return float|int
     */
    public function getX() : float|int
    {
        return $this->condition['x'];
    }

    /**
     * @return float|int
     */
    public function getY() : float|int
    {
        return $this->condition['y'];
    }

    /**
     * @return float|int
     */
    public function getZ() : float|int
    {
        return $this->condition['z'];
    }
}