<?php

namespace kim\present\particlechase\command\subcommands;

use kim\present\particlechase\command\{
	PoolCommand, SubCommand
};
use kim\present\particlechase\ParticleChase as Plugin;
use kim\present\particlechase\util\Translation;
use pocketmine\{
	Player, Server
};
use pocketmine\command\CommandSender;
use pocketmine\level\particle\Particle;

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
		if(isset($args[1])){
			$config = $this->plugin->getConfig();
			if($args[0] === '*' && $sender instanceof Player){
				$playerName = $sender->getLowerCaseName();
			}else{
				if(!Server::getInstance()->getPlayerExact($args[0]) && !$config->exists($args[0], true)){
					$sender->sendMessage(Translation::translate('command-generic-failure@invalid-player', $args[0]));
					return true;
				}
				$playerName = strtolower($args[0]);
			}
			$particleName = strtoupper($args[1]);
			$particleMode = $args[2] ?? 0;
			$particleData = implode(' ', array_slice($args, 3));
			if(!defined(Particle::class . "::TYPE_" . $particleName)){
				$sender->sendMessage($this->translate('failure-invalid-particle', $args[1]));
			}else{
				$config->set($playerName, [
					$particleName,
					$particleMode,
					$particleData,
				]);
				$sender->sendMessage($this->translate('success', $playerName, $particleName));
			}
			return true;
		}
		return false;
	}
}