<?php

namespace kim\present\particlechase;

use kim\present\particlechase\command\PoolCommand;
use kim\present\particlechase\command\subcommands\{
	ListSubCommand, RemoveSubCommand, SetSubCommand
};
use kim\present\particlechase\task\AddParticleTask;
use kim\present\particlechase\util\Translation;
use pocketmine\plugin\PluginBase;

class ParticleChase extends PluginBase{
	/** @var ParticleChase */
	private static $instance = null;

	/**
	 * @return ParticleChase
	 */
	public static function getInstance() : ParticleChase{
		return self::$instance;
	}

	/** @var PoolCommand */
	private $command;

	/**
	 * Called when the plugin is loaded, before calling onEnable()
	 */
	public function onLoad() : void{
		if(self::$instance === null){
			self::$instance = $this;
			Translation::loadFromResource($this->getResource('lang/eng.yml'), true);
		}
	}

	/**
	 * Called when the plugin is enabled
	 */
	public function onEnable() : void{
		$dataFolder = $this->getDataFolder();
		if(!file_exists($dataFolder)){
			mkdir($dataFolder, 0777, true);
		}

		$this->reloadConfig();

		$langfilename = $dataFolder . 'lang.yml';
		if(!file_exists($langfilename)){
			$resource = $this->getResource('lang/eng.yml');
			fwrite($fp = fopen("{$dataFolder}lang.yml", "wb"), $contents = stream_get_contents($resource));
			fclose($fp);
			Translation::loadFromContents($contents);
		}else{
			Translation::load($langfilename);
		}

		if($this->command == null){
			$this->command = new PoolCommand($this, 'particlechase');
			$this->command->createSubCommand(SetSubCommand::class);
			$this->command->createSubCommand(RemoveSubCommand::class);
			$this->command->createSubCommand(ListSubCommand::class);
		}
		$this->command->updateTranslation();
		$this->command->updateSudCommandTranslation();
		if($this->command->isRegistered()){
			$this->getServer()->getCommandMap()->unregister($this->command);
		}
		$this->getServer()->getCommandMap()->register(strtolower($this->getName()), $this->command);

		$this->getScheduler()->scheduleRepeatingTask(new AddParticleTask($this), 2);
	}

	/**
	 * Called when the plugin is disabled
	 * Use this to free open things and finish actions
	 */
	public function onDisable(){
		$dataFolder = $this->getDataFolder();
		if(!file_exists($dataFolder)){
			mkdir($dataFolder, 0777, true);
		}

		$this->saveConfig();
	}
}