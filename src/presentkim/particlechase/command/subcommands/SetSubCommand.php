<?php

namespace presentkim\particlechase\command\subcommands;

use pocketmine\command\CommandSender;
use pocketmine\level\particle\Particle;
use pocketmine\Server;
use presentkim\particlechase\{
  ParticleChaseMain as Plugin, util\Translation, command\SubCommand
};
use function strtolower;

class SetSubCommand extends SubCommand{

    public function __construct(Plugin $owner){
        parent::__construct($owner, Translation::translate('prefix'), 'command-particlechase-set', 'particlechase.set.cmd');
    }

    /**
     * @param CommandSender $sender
     * @param array         $args
     *
     * @return bool
     */
    public function onCommand(CommandSender $sender, array $args){
        if (isset($args[1])) {
            $playerName = strtolower($args[0]);
            $player = Server::getInstance()->getPlayerExact($playerName);
            $result = $this->owner->query("SELECT particle_name FROM particle_chase_list WHERE player_name = \"$playerName\";")->fetchArray(SQLITE3_NUM)[0];
            if ($player === null && $result === null) {
                $sender->sendMessage($this->prefix . Translation::translate('command-generic-failure@invalid-player', $args[0]));
            } else {
                $particleName = strtoupper($args[1]);
                $particleData = implode(' ', array_slice($args, 2));
                if (!defined(Particle::class . "::TYPE_" . $particleName)) {
                    $sender->sendMessage($this->prefix . Translation::translate($this->getFullId('failure-invalid-particle'), $args[1]));
                } else {
                    if ($result === null) { // When first query result is not exists
                        $this->owner->query("INSERT INTO particle_chase_list VALUES (\"$playerName\", \"$particleName\", \"$particleData\");");
                    } else {
                        $this->owner->query("
                            UPDATE particle_chase_list
                                set particle_name = \"$particleName\",
                                    particle_data = \"$particleData\"
                            WHERE player_name = \"$playerName\";
                        ");
                    }
                    $sender->sendMessage($this->prefix . Translation::translate($this->getFullId('success'), $playerName, $particleName));
                }
            }
            return true;
        }
        return false;
    }
}