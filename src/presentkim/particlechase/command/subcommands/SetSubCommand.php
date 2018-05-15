<?php

namespace presentkim\particlechase\command\subcommands;

use pocketmine\{
  Server, Player
};
use pocketmine\command\CommandSender;
use pocketmine\level\particle\Particle;
use presentkim\particlechase\ParticleChase as Plugin;
use presentkim\particlechase\command\{
  PoolCommand, SubCommand
};
use presentkim\particlechase\util\Translation;

class SetSubCommand extends SubCommand{

    public function __construct(PoolCommand $owner){
        parent::__construct($owner, 'set');
    }

    /**
     * @param CommandSender $sender
     * @param String[]      $args
     *
     * @return bool
     */
    public function onCommand(CommandSender $sender, array $args) : bool{
        if (isset($args[1])) {
            $config = $this->plugin->getConfig();
            if ($args[0] === '*' && $sender instanceof Player) {
                $playerName = $sender->getLowerCaseName();
            } else {
                if (!Server::getInstance()->getPlayerExact($args[0]) && !$config->exists($args[0], true)) {
                    $sender->sendMessage(Plugin::$prefix . Translation::translate('command-generic-failure@invalid-player', $args[0]));
                    return true;
                }
                $playerName = strtolower($args[0]);
            }
            $particleName = strtoupper($args[1]);
            $particleMode = $args[2] ?? 0;
            $particleData = implode(' ', array_slice($args, 3));
            if (!defined(Particle::class . "::TYPE_" . $particleName)) {
                $sender->sendMessage(Plugin::$prefix . $this->translate('failure-invalid-particle', $args[1]));
            } else {
                $config->set($playerName, [
                  $particleName,
                  $particleMode,
                  $particleData,
                ]);
                $sender->sendMessage(Plugin::$prefix . $this->translate('success', $playerName, $particleName));
            }
            return true;
        }
        return false;
    }
}