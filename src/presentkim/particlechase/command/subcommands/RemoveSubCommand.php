<?php

namespace presentkim\particlechase\command\subcommands;

use pocketmine\command\CommandSender;
use pocketmine\event\EventPriority;
use pocketmine\Server;
use presentkim\particlechase\{
  ParticleChaseMain as Plugin, util\Translation, command\SubCommand
};
use function presentkim\particlechase\util\toInt;
use function strtolower;

class RemoveSubCommand extends SubCommand{

    public function __construct(Plugin $owner){
        parent::__construct($owner, Translation::translate('prefix'), 'command-particlechase-remove', 'particlechase.remove.cmd');
    }

    /**
     * @param CommandSender $sender
     * @param array         $args
     *
     * @return bool
     */
    public function onCommand(CommandSender $sender, array $args){
        if (isset($args[0])) {
            $playerName = strtolower($args[0]);

            $config = $this->owner->getConfig();
            if ($config->exists($playerName)) {
                $config->remove($playerName);
                $sender->sendMessage($this->prefix . Translation::translate($this->getFullId('success'), $playerName));
            } else {
                $sender->sendMessage($this->prefix . Translation::translate('command-generic-failure@invalid-player', $args[0]));
            }
            return true;
        }
        return false;
    }
}