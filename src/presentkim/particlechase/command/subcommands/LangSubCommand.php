<?php

namespace presentkim\particlechase\command\subcommands;

use pocketmine\command\CommandSender;
use presentkim\particlechase\{
  command\PoolCommand, ParticleChaseMain as Plugin, util\Translation, command\SubCommand
};

class LangSubCommand extends SubCommand{

    public function __construct(PoolCommand $owner){
        parent::__construct($owner, 'lang');
    }

    /**
     * @param CommandSender $sender
     * @param String[]      $args
     *
     * @return bool
     */
    public function onCommand(CommandSender $sender, array $args){
        if (isset($args[0]) && is_string($args[0]) && ($args[0] = strtolower(trim($args[0])))) {
            $resource = $this->plugin->getResource("lang/$args[0].yml");
            if (is_resource($resource)) {
                $dataFolder = $this->plugin->getDataFolder();
                if (!file_exists($dataFolder)) {
                    mkdir($dataFolder, 0777, true);
                }

                fwrite($fp = fopen("{$dataFolder}lang.yml", "wb"), $contents = stream_get_contents($resource));
                fclose($fp);
                Translation::loadFromContents($contents);
                $this->plugin->reloadCommand();
                
                $sender->sendMessage(Plugin::$prefix . $this->translate('success', $args[0]));
            } else {
                $sender->sendMessage(Plugin::$prefix . $this->translate('failure', $args[0]));
            }
            return true;
        } else {
            return false;
        }
    }
}