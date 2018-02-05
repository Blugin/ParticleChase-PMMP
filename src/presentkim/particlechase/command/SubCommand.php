<?php

namespace presentkim\particlechase\command;

use pocketmine\command\CommandSender;
use presentkim\particlechase\ParticleChaseMain as Plugin;
use presentkim\particlechase\util\{
  Translation, Utils
};

abstract class SubCommand{

    /** @var PoolCommand */
    protected $owner;

    /** @var Plugin */
    protected $plugin;

    /** @var string */
    protected $strId;

    /** @var string */
    protected $permission;

    /** @var string */
    protected $label;

    /** @var string[] */
    protected $aliases;

    /** @var string */
    protected $usage;

    /**
     * SubCommand constructor.
     *
     * @param PoolCommand $owner
     * @param string      $label
     */
    public function __construct(PoolCommand $owner, string $label){
        $this->owner = $owner;
        $this->plugin = $owner->getPlugin();

        $this->strId = "command-{$owner->uname}-{$label}";
        $this->permission = "{$owner->uname}.cmd.{$label}";

        $this->updateTranslation();
    }

    /**
     * @param CommandSender $sender
     * @param String[]      $args
     */
    public function execute(CommandSender $sender, array $args) : void{
        if (!$this->checkPermission($sender)) {
            $sender->sendMessage(Plugin::$prefix . Translation::translate('command-generic-failure@permission'));
        } elseif (!$this->onCommand($sender, $args)) {
            $sender->sendMessage(Plugin::$prefix . $this->usage);
        }
    }

    /**
     * @param CommandSender $sender
     * @param String[]      $args
     *
     * @return bool
     */
    abstract public function onCommand(CommandSender $sender, array $args) : bool;

    /**
     * @param CommandSender $target
     *
     * @return bool
     */
    public function checkPermission(CommandSender $target) : bool{
        if ($this->permission === null) {
            return true;
        } else {
            return $target->hasPermission($this->permission);
        }
    }

    /**
     * @param string   $tag
     * @param string[] $params
     *
     * @return string
     */
    public function translate(string $tag, string ...$params) : string{
        return Translation::translate("{$this->strId}@{$tag}", ...$params);
    }

    /**
     * @param string $label
     *
     * @return bool
     */
    public function checkLabel(string $label) : bool{
        return strcasecmp($label, $this->label) === 0 || $this->aliases && Utils::in_arrayi($label, $this->aliases);
    }

    public function updateTranslation() : void{
        $this->label = Translation::translate($this->strId);
        $this->aliases = Translation::getArray("{$this->strId}@aliases");
        $this->usage = $this->translate('usage');
    }

    /** @return string */
    public function getLabel() : string{
        return $this->label;
    }

    /** @param string $label */
    public function setLabel(string $label) : void{
        $this->label = $label;
    }

    /**  @return string[] */
    public function getAliases() : array{
        return $this->aliases;
    }

    /** @param string[] $aliases */
    public function setAliases(array $aliases) : void{
        $this->aliases = $aliases;
    }

    /** @return string */
    public function getUsage() : string{
        return $this->usage;
    }

    /** @param string $usage */
    public function setUsage(string $usage) : void{
        $this->usage = $usage;
    }
}