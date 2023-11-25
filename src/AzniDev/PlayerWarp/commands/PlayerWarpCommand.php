<?php

namespace AzniDev\PlayerWarp\commands;

use AzniDev\PlayerWarp\PlayerWarp;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;
use pocketmine\world\Position;

class PlayerWarpCommand extends Command
{

    public function __construct(array $command)
    {
        $this->setPermission('playerwarp.command');
        parent::__construct($command['name'], $command['description'], null, $command['aliases']);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TF::RED . 'Please run this command in game.');
            return;
        }

        if (!isset($args[0])) {
            $sender->sendMessage(TF::RED . 'Usage /pwarp help');
            return;
        }
        switch ($args[0]) {
            case 'tp':
            case 'teleport':
                $database = PlayerWarp::getInstance()->getDatabaseManager();
                if (isset($args[1])) {
                    if (in_array($args[1], array_keys($database->getAllPlayerWarps($sender)))) {
                        $warp = $database->getPlayerWarp($sender, $args[1]);
                        $sender->teleport(Position::fromObject(new Vector3($warp->getX(), $warp->getY(), $warp->getZ()), $warp->getWorld()));
                        $sender->sendMessage('You warped to ' . TF::GOLD . $args[1]);
                    } else {
                        $sender->sendMessage(TF::RED . 'Warp not found');
                    }
                } else {
                    $sender->sendMessage(TF::RED . 'Usage /pwarp tp (Warp Name)');
                }
                break;
            case 'set':
                $database = PlayerWarp::getInstance()->getDatabaseManager();
                if (isset($args[1])) {
                    if (!in_array($args[1], array_keys($database->getAllPlayerWarps($sender)))) {
                        foreach (PlayerWarp::getInstance()->getConfig()->get('permissions') as $permission => $max) {
                            if ($sender->hasPermission($permission)) {
                                if (count($database->getAllPlayerWarps($sender)) < $max) {
                                    $database->setPlayerWarp($sender, $args[1]);
                                    $sender->sendMessage('Warp set as ' . TF::GOLD . $args[1]);
                                } else {
                                    $sender->sendMessage(TF::RED . 'Your warp limit is up');
                                }
                                return;
                            }
                        }
                        if (count($database->getAllPlayerWarps($sender)) < PlayerWarp::getInstance()->getConfig()->get('max-warp')) {
                            $database->setPlayerWarp($sender, $args[1]);
                            $sender->sendMessage('Warp set as ' . TF::GOLD . $args[1]);
                        } else {
                            $sender->sendMessage(TF::RED . 'You warp limit is up');
                        }
                    } else {
                        $sender->sendMessage(TF::RED . 'The warp is already exists');
                    }
                } else {
                    $sender->sendMessage(TF::RED . 'Usage /pwarp set (Warp Name)');
                }
                break;
            case 'delete':
                $database = PlayerWarp::getInstance()->getDatabaseManager();
                if (isset($args[1])) {
                    if (in_array($args[1], array_keys($database->getAllPlayerWarps($sender)))) {
                        $database->deletePlayerWarp($sender, $args[1]);
                        $sender->sendMessage('Warp ' . TF::GOLD . $args[1] . TF::RESET . ' has been deleted');
                    } else {
                        $sender->sendMessage(TF::RED . 'Warp not found');
                    }
                } else {
                    $sender->sendMessage(TF::RED . 'Usage /pwarp delete (Warp Name)');
                }
                break;
            case 'list':
                $database = PlayerWarp::getInstance()->getDatabaseManager();
                $sender->sendMessage('All warps: ' . TF::GOLD . implode(', ', array_keys($database->getAllPlayerWarps($sender))));
                break;
            case 'help':
                $sender->sendMessage('Player Warp Commands:');
                $sender->sendMessage('');
                $sender->sendMessage(TF::GOLD . '  - /pwarp tp (Warp Name)');
                $sender->sendMessage(TF::GOLD . '  - /pwarp set (Warp Name)');
                $sender->sendMessage(TF::GOLD . '  - /pwarp delete (Warp Name)');
                $sender->sendMessage(TF::GOLD . '  - /pwarp list');
                $sender->sendMessage(TF::GOLD . '  - /pwarp help');
        }
    }
}