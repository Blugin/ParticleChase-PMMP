<?php

namespace presentkim\particlechase\command\subcommands;

use pocketmine\command\CommandSender;
use presentkim\particlechase\{
  ParticleChaseMain as Plugin, util\Translation, command\SubCommand
};

class LangSubCommand extends SubCommand{

    public function __construct(Plugin $owner){
        parent::__construct($owner, Translation::translate('prefix'), 'command-particlechase-lang', 'particlechase.lang.cmd');
    }

    /**
     * @param CommandSender $sender
     * @param array         $args
     *
     * @return bool
     */
    public function onCommand(CommandSender $sender, array $args) : bool{
        if (isset($args[0]) && is_string($args[0]) && ($args[0] = strtolower(trim($args[0])))) {
            $resource = $this->owner->getResource("lang/$args[0].yml");
            if (is_resource($resource)) {
                @mkdir($this->owner->getDataFolder());
                $langfilename = $this->owner->getDataFolder() . "lang.yml";
                Translation::loadFromResource($resource);
                Translation::save($langfilename);
                $sender->sendMessage($this->prefix . Translation::translate($this->getFullId('success'), $args[0]));
            } else {
                $sender->sendMessage($this->prefix . Translation::translate($this->getFullId('failure'), $args[0]));
            }
            return true;
        } else {
            return false;
        }
    }
}