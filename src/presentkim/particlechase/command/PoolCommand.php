<?php

namespace presentkim\particlechase\command;


use pocketmine\command\{
  Command, PluginCommand, CommandExecutor, CommandSender, ConsoleCommandSender
};
use presentkim\particlechase\util\Translation;
use presentkim\particlechase\ParticleChaseMain as Plugin;

class PoolCommand extends PluginCommand implements CommandExecutor{

    /** @var SubCommand[] */
    protected $subCommands = [];

    /** @var \ReflectionProperty */
    private $property = null;

    /** @var string */
    public $uname;

    /**
     * @param Plugin       $owner
     * @param string       $name
     * @param SubCommand[] $subCommands
     */
    public function __construct(Plugin $owner, string $name, SubCommand ...$subCommands){
        parent::__construct($name, $owner);
        $this->setExecutor($this);

        $reflection = new \ReflectionClass(Command::class);
        $this->property = $reflection->getProperty('name');
        $this->property->setAccessible(true);

        $this->uname = $name;
        $this->setPermission("{$name}.cmd");
        $this->updateTranslation();

        $this->subCommands = $subCommands;
    }

    /**
     * @param CommandSender $sender
     * @param Command       $command
     * @param string        $label
     * @param string[]      $args
     *
     * @return bool
     */
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        if (isset($args[0])) {
            $label = array_shift($args);
            foreach ($this->subCommands as $key => $value) {
                if ($value->checkLabel($label)) {
                    $value->execute($sender, $args);
                    return true;
                }
            }
        }
        $sender->sendMessage($this->getPlugin()->getServer()->getLanguage()->translateString("commands.generic.usage", [$this->getUsage($sender)]));
        return true;
    }

    /**
     * @param CommandSender|null $sender
     *
     * @return string
     */
    public function getUsage(CommandSender $sender = null) : string{
        if ($sender === null) {
            return $this->usageMessage;
        } else {
            $subCommands = [];
            foreach ($this->subCommands as $key => $subCommand) {
                if ($subCommand->checkPermission($sender)) {
                    $subCommands[] = $subCommand->getLabel();
                }
            }
            return Translation::translate("command-{$this->uname}@usage", implode(Translation::translate("command-{$this->uname}@usage-separator"), $subCommands));
        }
    }

    /** @return SubCommand[] */
    public function getSubCommands() : array{
        return $this->subCommands;
    }

    /** @param SubCommand[] $subCommands */
    public function setSubCommands(SubCommand ...$subCommands) : void{
        $this->subCommands = $subCommands;
    }

    /** @param SubCommand::class $subCommandClass */
    public function createSubCommand($subCommandClass) : void{
        $this->subCommands[] = new $subCommandClass($this);
    }

    public function updateTranslation() : void{
        $this->property->setValue($this, Translation::translate("command-{$this->uname}"));
        $this->description = Translation::translate("command-{$this->uname}@description");
        $this->usageMessage = $this->getUsage(new ConsoleCommandSender());
        $aliases = Translation::getArray("command-{$this->uname}@aliases");
        if (is_array($aliases)) {
            $this->setAliases($aliases);
        }
    }

    public function updateSudCommandTranslation() : void{
        foreach ($this->subCommands as $key => $value) {
            $value->updateTranslation();
        }
    }
}