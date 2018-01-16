<?php

namespace presentkim\particlechase;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\block\BlockFactory;
use pocketmine\math\Vector3;
use pocketmine\scheduler\{
  Task, TaskHandler
};
use pocketmine\level\particle\{
  AngryVillagerParticle, BlockForceFieldParticle, BubbleParticle, CriticalParticle, DustParticle, EnchantmentTableParticle, EnchantParticle, EntityFlameParticle, ExplodeParticle, FlameParticle, GenericParticle, HappyVillagerParticle, HeartParticle, HugeExplodeParticle, HugeExplodeSeedParticle, InkParticle, InstantEnchantParticle, ItemBreakParticle, LavaDripParticle, PortalParticle, RainSplashParticle, RedstoneParticle, SmokeParticle, SplashParticle, SporeParticle, TerrainParticle, WaterDripParticle, WaterParticle
};
use presentkim\particlechase\util\Translation;
use presentkim\particlechase\command\PoolCommand;
use presentkim\particlechase\command\subcommands\{
  SetSubCommand, RemoveSubCommand, ListSubCommand, LangSubCommand, ReloadSubCommand, SaveSubCommand
};
use function presentkim\particlechase\util\toInt;

class ParticleChaseMain extends PluginBase{

    /** @var self */
    private static $instance = null;

    /** @var PoolCommand */
    private $command;

    /** @var TaskHandler */
    private $taskHandler = null;

    /** @return self */
    public static function getInstance(){
        return self::$instance;
    }

    public function onLoad(){
        if (self::$instance === null) {
            self::$instance = $this;
            $this->getServer()->getLoader()->loadClass('presentkim\particlechase\util\Utils');
            Translation::loadFromResource($this->getResource('lang/eng.yml'), true);
        }
    }

    public function onEnable(){
        $this->load();

        $this->taskHandler = Server::getInstance()->getScheduler()->scheduleRepeatingTask(new class() extends Task{

            /** @var ParticleChaseMain */
            private $owner;

            public function __construct(){
                $this->owner = ParticleChaseMain::getInstance();
            }

            public function onRun(int $currentTick){
                $playerData = $this->owner->getConfig()->getAll();
                foreach (Server::getInstance()->getOnlinePlayers() as $key => $value) {
                    $playerName = $value->getLowerCaseName();
                    if (isset($playerData[$playerName])) {
                        $data = $playerData[$playerName];
                        if ($data[1] == 1) {
                            $vec = $value->add(0, $value->height, 0);
                        } else {
                            $vec = $value;
                        }
                        if (($particle = $this->getParticle($vec, $data[0], $data[2])) !== null) {
                            $value->getLevel()->addParticle($particle);
                        }
                    }
                }
            }

            /**
             * @param Vector3 $vec
             * @param string  $name
             * @param string  $data = ''
             *
             * @return GenericParticle|null
             */
            private function getParticle(Vector3 $vec, string $name, string $data = ''){
                if (strcasecmp($name, "VILLAGER_ANGRY") == 0) {
                    return new AngryVillagerParticle($vec);
                } elseif (strcasecmp($name, "BLOCK_FORCE_FIELD") == 0) {
                    return new BlockForceFieldParticle($vec, toInt($data, 0));
                } elseif (strcasecmp($name, "BUBBLE") == 0) {
                    return new BubbleParticle($vec);
                } elseif (strcasecmp($name, "CRITICAL") == 0) {
                    return new CriticalParticle($vec, toInt($data, 2));
                } elseif (strcasecmp($name, "DUST") == 0) {
                    $datas = explode(' ', $data);
                    $r = isset($datas[0]) ? toInt($datas[0], 0) : 0;
                    $g = isset($datas[1]) ? toInt($datas[1], 0) : 0;
                    $b = isset($datas[2]) ? toInt($datas[2], 0) : 0;
                    $a = isset($datas[2]) ? toInt($datas[2], 255) : 255;
                    return new DustParticle($vec, $r, $g, $b, $a);
                } elseif (strcasecmp($name, "ENCHANTMENT_TABLE") == 0) {
                    return new EnchantmentTableParticle($vec);
                } elseif (strcasecmp($name, "MOB_SPELL") == 0) {
                    return new EnchantParticle($vec);
                } elseif (strcasecmp($name, "MOB_FLAME") == 0) {
                    return new EntityFlameParticle($vec);
                } elseif (strcasecmp($name, "EXPLODE") == 0) {
                    return new ExplodeParticle($vec);
                } elseif (strcasecmp($name, "FLAME") == 0) {
                    return new FlameParticle($vec);
                } elseif (strcasecmp($name, "VILLAGER_HAPPY") == 0) {
                    return new HappyVillagerParticle($vec);
                } elseif (strcasecmp($name, "HEART") == 0) {
                    return new HeartParticle($vec, toInt($data, 0));
                } elseif (strcasecmp($name, "HUGE_EXPLODE") == 0) {
                    return new HugeExplodeParticle($vec);
                } elseif (strcasecmp($name, "HUGE_EXPLODE_SEED") == 0) {
                    return new HugeExplodeSeedParticle($vec);
                } elseif (strcasecmp($name, "INK") == 0) {
                    return new InkParticle($vec, toInt($data, 0));
                } elseif (strcasecmp($name, "MOB_SPELL_INSTANTANEOUS") == 0) {
                    return new InstantEnchantParticle($vec);
                } elseif (strcasecmp($name, "ITEM_BREAK") == 0) {
                    $datas = explode(' ', $data);
                    $id = isset($datas[0]) ? (toInt($datas[0], 1)) : 1;
                    $meta = isset($datas[1]) ? (toInt($datas[1], 0)) : 0;
                    return new ItemBreakParticle($vec, Item::get($id, $meta));
                } elseif (strcasecmp($name, "DRIP_LAVA") == 0) {
                    return new LavaDripParticle($vec);
                } elseif (strcasecmp($name, "PORTAL") == 0) {
                    return new PortalParticle($vec);
                } elseif (strcasecmp($name, "RAIN_SPLASH") == 0) {
                    return new RainSplashParticle($vec);
                } elseif (strcasecmp($name, "REDSTONE") == 0) {
                    return new RedstoneParticle($vec, toInt($data, 1));
                } elseif (strcasecmp($name, "SMOKE") == 0) {
                    return new SmokeParticle($vec, toInt($data, 0));
                } elseif (strcasecmp($name, "WATER_SPLASH") == 0) {
                    return new SplashParticle($vec);
                } elseif (strcasecmp($name, "TOWN_AURA") == 0) {
                    return new SporeParticle($vec);
                } elseif (strcasecmp($name, "TERRAIN") == 0) {
                    $datas = explode(' ', $data);
                    $id = isset($datas[0]) ? (toInt($datas[0], 1)) : 1;
                    $meta = isset($datas[1]) ? (toInt($datas[1], 0)) : 0;
                    return new TerrainParticle($vec, BlockFactory::get($id, $meta));
                } elseif (strcasecmp($name, "DRIP_WATER") == 0) {
                    return new WaterDripParticle($vec);
                } elseif (strcasecmp($name, "WATER_WAKE") == 0) {
                    return new WaterParticle($vec);
                } else {
                    return null;
                }
            }
        }, 2);
    }

    public function onDisable(){
        $this->save();
        $this->taskHandler->cancel();
    }

    public function load(){
        $dataFolder = $this->getDataFolder();
        if (!file_exists($dataFolder)) {
            mkdir($dataFolder, 0777, true);
        }

        $this->reloadConfig();

        $langfilename = $dataFolder . 'lang.yml';
        if (!file_exists($langfilename)) {
            $resource = $this->getResource('lang/eng.yml');
            fwrite($fp = fopen("{$dataFolder}lang.yml", "wb"), $contents = stream_get_contents($resource));
            fclose($fp);
            Translation::loadFromContents($contents);
        } else {
            Translation::load($langfilename);
        }

        $this->reloadCommand();
    }

    public function save(){
        $dataFolder = $this->getDataFolder();
        if (!file_exists($dataFolder)) {
            mkdir($dataFolder, 0777, true);
        }

        $this->saveConfig();
    }

    public function reloadCommand(){
        if ($this->command == null) {
            $this->command = new PoolCommand($this, 'particlechase');
            $this->command->createSubCommand(SetSubCommand::class);
            $this->command->createSubCommand(RemoveSubCommand::class);
            $this->command->createSubCommand(ListSubCommand::class);
            $this->command->createSubCommand(LangSubCommand::class);
            $this->command->createSubCommand(ReloadSubCommand::class);
            $this->command->createSubCommand(SaveSubCommand::class);
        }
        $this->command->updateTranslation();
        $this->command->updateSudCommandTranslation();
        if ($this->command->isRegistered()) {
            $this->getServer()->getCommandMap()->unregister($this->command);
        }
        $this->getServer()->getCommandMap()->register(strtolower($this->getName()), $this->command);
    }
}
