<?php

namespace presentkim\particlechase\command\subcommands;

use pocketmine\command\CommandSender;
use presentkim\particlechase\{
  ParticleChaseMain as Plugin, util\Translation, command\SubCommand
};

class ReloadSubCommand extends SubCommand{

    public function __construct(Plugin $owner){
        parent::__construct($owner, Translation::translate('prefix'), 'command-particlechase-reload', 'particlechase.reload.cmd');
    }

    /**
     * @param CommandSender $sender
     * @param array         $args
     *
     * @return bool
     */
    public function onCommand(CommandSender $sender, array $args) : bool{
        $this->owner->load();
        $sender->sendMessage($this->prefix . Translation::translate($this->getFullId('success')));

        return true;
    }
}