<?php

namespace presentkim\particlechase\command\subcommands;

use pocketmine\command\CommandSender;
use presentkim\particlechase\{
  ParticleChaseMain as Plugin, util\Translation, command\SubCommand
};

class RemoveSubCommand extends SubCommand{

    public function __construct(Plugin $owner){
        parent::__construct($owner, Translation::translate('prefix'), 'command-particlechase-remove', 'particlechase.remove.cmd');
    }

    /**
     * @param CommandSender $sender
     * @param String[]      $args
     *
     * @return bool
     */
    public function onCommand(CommandSender $sender, array $args){
        if (isset($args[0])) {
            $playerName = strtolower($args[0]);

            $config = $this->owner->getConfig();
            if ($config->exists($playerName)) {
                $config->remove($playerName);
                $sender->sendMessage(Plugin::$prefix . $this->translate('success', $playerName));
            } else {
                $sender->sendMessage(Plugin::$prefix . Translation::translate('command-generic-failure@invalid-player', $args[0]));
            }
            return true;
        }
        return false;
    }
}