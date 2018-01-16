<?php

namespace presentkim\particlechase;

use pocketmine\block\BlockFactory;
use pocketmine\command\{
  CommandExecutor, PluginCommand
};
use pocketmine\item\Item;
use pocketmine\level\particle\{
  AngryVillagerParticle, BlockForceFieldParticle, BubbleParticle, CriticalParticle, DustParticle, EnchantmentTableParticle, EnchantParticle, EntityFlameParticle, ExplodeParticle, FlameParticle, GenericParticle, HappyVillagerParticle, HeartParticle, HugeExplodeParticle, HugeExplodeSeedParticle, InkParticle, InstantEnchantParticle, ItemBreakParticle, LavaDripParticle, PortalParticle, RainSplashParticle, RedstoneParticle, SmokeParticle, SplashParticle, SporeParticle, TerrainParticle, WaterDripParticle, WaterParticle
};
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\{
  Task, TaskHandler
};
use pocketmine\Server;
use presentkim\particlechase\{
  command\CommandListener, util\Translation
};
use function presentkim\particlechase\util\{
  extensionLoad, toInt
};

class ParticleChaseMain extends PluginBase{

    /** @var self */
    private static $instance = null;

    /** @var PluginCommand[] */
    private $commands = [];

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

            $sqlite3Path = "{$this->getDataFolder()}data.sqlite3";
            if (file_exists($sqlite3Path)) {
                extensionLoad('sqlite3');

                $db = new \SQLITE3($sqlite3Path);
                $results = $db->query("SELECT * FROM particle_chase_list;");
                $config = $this->getConfig();
                while ($result = $results->fetchArray(SQLITE3_NUM)) {
                    $key = mb_convert_encoding($result[0], "ASCII", "UTF-8");
                    $value = [];
                    $value[] = mb_convert_encoding($result[1], "ASCII", "UTF-8"); // particle_name
                    $value[] = 0; // particle_mode
                    $value[] = mb_convert_encoding($result[2], "ASCII", "UTF-8"); // particle_data
                    $config->set($key, $value);
                }
                $this->saveConfig();
                unset($db, $results, $result);
                unlink($sqlite3Path);
            }
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
            Translation::loadFromResource($resource);
            stream_copy_to_stream($resource, $fp = fopen("{$dataFolder}lang.yml", "wb"));
            fclose($fp);
        } else {
            Translation::load($langfilename);
        }

        foreach ($this->commands as $command) {
            $this->getServer()->getCommandMap()->unregister($command);
        }
        $this->commands = [];
        $this->registerCommand(new CommandListener($this), Translation::translate('command-particlechase'), 'ParticleChase', 'particlechase.cmd', Translation::translate('command-particlechase@description'), Translation::translate('command-particlechase@usage'), Translation::getArray('command-particlechase@aliases'));
    }

    public function save(){
        $dataFolder = $this->getDataFolder();
        if (!file_exists($dataFolder)) {
            mkdir($dataFolder, 0777, true);
        }

        $this->saveConfig();
    }

    /**
     * @param CommandExecutor $executor
     * @param                 $name
     * @param                 $fallback
     * @param                 $permission
     * @param string          $description
     * @param null            $usageMessage
     * @param array|null      $aliases
     */
    private function registerCommand(CommandExecutor $executor, $name, $fallback, $permission, $description = "", $usageMessage = null, array $aliases = null){
        $command = new PluginCommand($name, $this);
        $command->setExecutor($executor);
        $command->setPermission($permission);
        $command->setDescription($description);
        $command->setUsage($usageMessage ?? ('/' . $name));
        if (is_array($aliases)) {
            $command->setAliases($aliases);
        }

        $this->getServer()->getCommandMap()->register($fallback, $command);
        $this->commands[] = $command;
    }

}
