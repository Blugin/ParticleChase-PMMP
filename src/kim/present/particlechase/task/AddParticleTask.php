<?php

namespace kim\present\particlechase\task;

use pocketmine\Server;
use pocketmine\block\BlockFactory;
use pocketmine\item\Item;
use pocketmine\level\particle\{
  AngryVillagerParticle, BlockForceFieldParticle, BubbleParticle, CriticalParticle, DustParticle, EnchantmentTableParticle, EnchantParticle, EntityFlameParticle, ExplodeParticle, FlameParticle, GenericParticle, HappyVillagerParticle, HeartParticle, HugeExplodeParticle, HugeExplodeSeedParticle, InkParticle, InstantEnchantParticle, ItemBreakParticle, LavaDripParticle, PortalParticle, RainSplashParticle, RedstoneParticle, SmokeParticle, SplashParticle, SporeParticle, TerrainParticle, WaterDripParticle, WaterParticle
};
use pocketmine\math\Vector3;
use kim\present\particlechase\util\Utils;
use pocketmine\scheduler\PluginTask;

class AddParticleTask extends PluginTask{

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
            return new BlockForceFieldParticle($vec, Utils::toInt($data, 0));
        } elseif (strcasecmp($name, "BUBBLE") == 0) {
            return new BubbleParticle($vec);
        } elseif (strcasecmp($name, "CRITICAL") == 0) {
            return new CriticalParticle($vec, Utils::toInt($data, 2));
        } elseif (strcasecmp($name, "DUST") == 0) {
            $datas = explode(' ', $data);
            $r = isset($datas[0]) ? Utils::toInt($datas[0], 0) : 0;
            $g = isset($datas[1]) ? Utils::toInt($datas[1], 0) : 0;
            $b = isset($datas[2]) ? Utils::toInt($datas[2], 0) : 0;
            $a = isset($datas[2]) ? Utils::toInt($datas[2], 255) : 255;
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
            return new HeartParticle($vec, Utils::toInt($data, 0));
        } elseif (strcasecmp($name, "HUGE_EXPLODE") == 0) {
            return new HugeExplodeParticle($vec);
        } elseif (strcasecmp($name, "HUGE_EXPLODE_SEED") == 0) {
            return new HugeExplodeSeedParticle($vec);
        } elseif (strcasecmp($name, "INK") == 0) {
            return new InkParticle($vec, Utils::toInt($data, 0));
        } elseif (strcasecmp($name, "MOB_SPELL_INSTANTANEOUS") == 0) {
            return new InstantEnchantParticle($vec);
        } elseif (strcasecmp($name, "ITEM_BREAK") == 0) {
            $datas = explode(' ', $data);
            $id = isset($datas[0]) ? (Utils::toInt($datas[0], 1)) : 1;
            $meta = isset($datas[1]) ? (Utils::toInt($datas[1], 0)) : 0;
            return new ItemBreakParticle($vec, Item::get($id, $meta));
        } elseif (strcasecmp($name, "DRIP_LAVA") == 0) {
            return new LavaDripParticle($vec);
        } elseif (strcasecmp($name, "PORTAL") == 0) {
            return new PortalParticle($vec);
        } elseif (strcasecmp($name, "RAIN_SPLASH") == 0) {
            return new RainSplashParticle($vec);
        } elseif (strcasecmp($name, "REDSTONE") == 0) {
            return new RedstoneParticle($vec, Utils::toInt($data, 1));
        } elseif (strcasecmp($name, "SMOKE") == 0) {
            return new SmokeParticle($vec, Utils::toInt($data, 0));
        } elseif (strcasecmp($name, "WATER_SPLASH") == 0) {
            return new SplashParticle($vec);
        } elseif (strcasecmp($name, "TOWN_AURA") == 0) {
            return new SporeParticle($vec);
        } elseif (strcasecmp($name, "TERRAIN") == 0) {
            $datas = explode(' ', $data);
            $id = isset($datas[0]) ? (Utils::toInt($datas[0], 1)) : 1;
            $meta = isset($datas[1]) ? (Utils::toInt($datas[1], 0)) : 0;
            return new TerrainParticle($vec, BlockFactory::get($id, $meta));
        } elseif (strcasecmp($name, "DRIP_WATER") == 0) {
            return new WaterDripParticle($vec);
        } elseif (strcasecmp($name, "WATER_WAKE") == 0) {
            return new WaterParticle($vec);
        } else {
            return null;
        }
    }
}