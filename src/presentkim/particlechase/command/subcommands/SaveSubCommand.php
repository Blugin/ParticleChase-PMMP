<?php

namespace presentkim\particlechase\command\subcommands;

use pocketmine\command\CommandSender;
use presentkim\particlechase\{
  ParticleChaseMain as Plugin, util\Translation, command\SubCommand
};

class SaveSubCommand extends SubCommand{

    public function __construct(Plugin $owner){
        parent::__construct($owner, Translation::translate('prefix'), 'command-particlechase-save', 'particlechase.save.cmd');
    }

    /**
     * @param CommandSender $sender
     * @param array         $args
     *
     * @return bool
     */
    public function onCommand(CommandSender $sender, array $args){
        $this->owner->save();
        $sender->sendMessage($this->prefix . Translation::translate($this->getFullId('success')));

        return true;
    }
}