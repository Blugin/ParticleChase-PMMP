<?php

namespace kim\present\particlechase\command\subcommands;

use pocketmine\command\CommandSender;
use pocketmine\Server;
use kim\present\particlechase\ParticleChase as Plugin;
use kim\present\particlechase\command\{
  PoolCommand, SubCommand
};
use kim\present\particlechase\util\{
  Translation, Utils
};

class ListSubCommand extends SubCommand{

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
        $list = [];
        foreach ($this->plugin->getConfig()->getAll() as $key => $value) {
            if (($player = Server::getInstance()->getPlayerExact($key)) !== null) {
                $key = $player->getName();
            }
            $list[] = [
              $key,
              $value[0],
              Translation::translate($value[1] == 1 ? 'modename@head' : 'modename@foot'),
              $value[2],
            ];
        }

        $max = ceil(count($list) / 5);
        $page = min($max, (isset($args[0]) ? Utils::toInt($args[0], 1, function (int $i){
              return $i > 0 ? 1 : -1;
          }) : 1) - 1);
        $sender->sendMessage(Plugin::$prefix . $this->translate('head', $page + 1, $max));
        for ($i = $page * 5; $i < ($page + 1) * 5 && $i < count($list); $i++) {
            $sender->sendMessage($this->translate('item', ...$list[$i]));
        }

        return true;
    }
}