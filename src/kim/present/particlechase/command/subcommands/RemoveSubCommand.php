<?php

namespace kim\present\particlechase\command\subcommands;

use pocketmine\Player;
use pocketmine\command\CommandSender;
use kim\present\particlechase\ParticleChase as Plugin;
use kim\present\particlechase\command\{
  PoolCommand, SubCommand
};
use kim\present\particlechase\util\Translation;

class RemoveSubCommand extends SubCommand{

    public function __construct(PoolCommand $owner){
        parent::__construct($owner, 'remove');
    }

    /**
     * @param CommandSender $sender
     * @param String[]      $args
     *
     * @return bool
     */
    public function onCommand(CommandSender $sender, array $args) : bool{
        if (isset($args[0])) {
            $config = $this->plugin->getConfig();
            if ($args[0] === '*' && $sender instanceof Player) {
                $playerName = $sender->getLowerCaseName();
            } else {
                if (!$config->exists($args[0], true)) {
                    $sender->sendMessage(Plugin::$prefix . Translation::translate('command-generic-failure@invalid-player', $args[0]));
                    return true;
                }
                $playerName = strtolower($args[0]);
            }
            $config->remove($playerName);
            $sender->sendMessage(Plugin::$prefix . $this->translate('success', $playerName));
            return true;
        }
        return false;
    }
}