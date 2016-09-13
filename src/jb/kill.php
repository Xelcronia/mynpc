<?php

namespace jb;

use pocketmine\scheduler\PluginTask;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\format\FullChunk;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\entity\Projectile;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\String;
use pocketmine\nbt\tag\Double;
use pocketmine\nbt\tag\List;
use pocketmine\nbt\tag\Float;
use pocketmine\nbt\tag\Short;
use pocketmine\nbt\tag\Byte;

if (substr(PHP_VERSION, 0, 1) == '5') {
	define('p7x',false);
	define('p5x',true);
	}else{
	define('p7x',true);
	define('p5x',false);
	echo 'php7';
}
class kill extends PluginBase implements Listener{

public function xxo(EntityDamageEvent $e){
	if ($e instanceof EntityDamageByEntityEvent){
	if(($p=$e->getDamager()) instanceof Player){
		$et=$e->getEntity();
		if($et->getDataProperty("fuck")==null) return;
		if (!array_key_exists($et->getId(),$this->elist)) return;
		$this->klist[$et->getId()]=$p->getName();
		//echo count($this->klist);
		//$et->setNameTag($this->cachec[$et->getId()]["nt"]."  HP:".$et->getHealth()."/".$et->getMaxHealth());
	}
	}
	
	
}
	public function dop($prop,$e){
		$it=[];
		foreach($this->cachec[$e->getEntity()->getId()]["drops"] as $k){
			$tm=explode(":",$k);
			if(mt_rand(0,100)<$tm[2]){
			$it=array_merge(array(new Item($tm[0],0,$tm[1])),$it);
			}
		}
		$e->setDrops($it);
	}
	public function spaw($x,$y,$z,$tp,$level,$hp,$sk,$i){
	
	 $nbt = new Compound;
     
    $motion = new Vector3(0,0,0);
$nbt->Skin = new Compound("Skin", [
          "Data" => new String("Data", $sk),
        ]);
        $nbt->Pos = new Enum("Pos", [
         
           new Double("", $x),
           new Double("", $y),
           new Double("", $z)
         
        ]);

        $nbt->Motion = new Enum("Motion", [
         
           new Double("", $motion->x),
           new Double("", $motion->y),
           new Double("", $motion->z)
         
        ]);
     
        $nbt->Rotation = new Enum("Rotation", [
         
            new Float("", 90),
            new Float("", 90)
         
        ]);
     
        $nbt->Health = new Short("Health", $hp);
	 $a=Entity::createEntity($tp, $level->getChunk($x>>4, $z>>4),$nbt);
	 $a->setMaxHealth($hp);
	 $a->setHealth($hp);
	 $a->spawnToAll();
	 return $a;
}
 public function onCommand(CommandSender $s, Command $cd, $label, array $a){
	 if($cd=="npc"){
		foreach($this->getServer()->getOnlinePlayers() as $j){
			$skin=$j->getSkinData();
			//$sks=$j->isSkinSlim();
			break;
		}
		 $pe=$this->c->get($a[0]);
		 if($pe==null) return;
//if($this->getServer()->getPlayer($s->getName())!=null){
	//$skin = $s->getSkinData();
   // $sks = $s->isSkinSlim();
	
	//$p=$this->getServer()->getPlayer($s->getName());
	/*
	$pos=$p->getPosition();
	$x=$pos->getX();
	$y=$pos->getY();
	$z=$pos->getZ();
	*/
	$sn=$this->sk->get($a[0]);
$et=$this->spaw($pe["x"],$pe["y"],$pe["z"],$pe["name"],$this->getServer()->getLevel($pe["lid"]),$pe["health"],$skin);
$et->setDataProperty("fuck",1,$pe["npc"]);
//$et->setDataProperty("kq",1,$a[0]);
$this->cachec[$et->getId()]=$pe;
$et->setNameTag($pe["nt"]);
//$et->setDataProperty("jtag",4,$pe["nt"]);
$this->elist[$et->getId()]=$et;
$s->sendMessage("完成！！");
//}
return;
	 }
	 if($cd=="new"){    
 $p=$s;
		 $pos=$p->getPosition();
		 $dat=[];
		 $dat["x"]=$pos->getX();
		 $dat["y"]=$pos->getY();
		 $dat["z"]=$pos->getZ();
		 $dat["lid"]=$pos->getLevel()->getId();
		 $dat["name"]="Human";
		 $dat["health"]=20;
		 $dat["nt"]="傻逼npc";
		 $dat["npc"]=$a[1];
		 $dat["drops"]=array("2:2:50","8:2:10");
		 $dat["伤害"]=4;
		 $dat["死亡指令"]="say {player}";
		 $dat["仇恨范围"]=10;
		  $dat["speed"]=6;
		 $this->c->set($a[0],$dat);
		 $this->c->save();
		 $s->sendMessage("done");
	 }
	 else
	 {
		 $this->rep=$a[0];
	 }
}


	public function onEnable(){
		$this->klist=[];
		$this->elist=[];
		$this->cachec=[];
		$this->rep=1;
			$this->getServer()->getPluginManager()->registerEvents($this, $this);
		
		@mkdir($this->getDataFolder());
       
		$this->c=new Config($this->getDataFolder()."cfg.yml",Config::YAML,array());
		$this->sk=new Config($this->getDataFolder()."fuck.yml",Config::YAML,array());
			$this->getLogger()->info(TextFormat::WHITE . "插件已启用！");
		$this->getLogger()->info(TextFormat::BLUE . "===========================");
		$this->getLogger()->info(TextFormat::YELLOW . "本插件由@CreeperGo编写，谢谢chenxiaoyi 的创意和支持");
		$this->getLogger()->info(TextFormat::BLUE  . "---------------------------");
		
 $sh=$this->getServer()->getScheduler();
 $sh->scheduleRepeatingTask(new jb($this),1.5);
    }
	public function onRespawn(PlayerRespawnEvent $event){
		echo "cAADSADSADSXD";
                $p = $event->getPlayer();
		$nm=$p->getName();
		foreach($this->klist as $id=>$pl){
			if($pl==$nm){
				unset($this->klist[$id]);
			}
		}
	}
        public function onQuit(PlayerQuitEvent $event){
		echo "cAADSADSADSXD";
                $p = $event->getPlayer();
		$nm=$p->getName();
		foreach($this->klist as $id=>$pl){
			if($pl==$nm){
				unset($this->klist[$id]);
			}
		}
	}
        public function gc(EntityDeathEvent $event){
	$entity = $event->getEntity();
        	$cause = $entity->getLastDamageCause();
		
			if($cause instanceof EntityDamageByEntityEvent){
	
            $killer = $cause->getDamager();
			if($killer instanceof Player){}else{$killer=$entity;}
			}
	$entity = $event->getEntity();
	//if($entity instanceof Player){
		
		//return;
	//}
    if($entity->getDataProperty("fuck")!=0){
		$this->getServer()->dispatchCommand(new ConsoleCommandSender,str_replace("{player}",$killer->getName(),$this->cachec[$entity->getId()]["死亡指令"]));
		$this->dop($entity->getDataProperty("fuck"),$event);
				unset($this->klist[$entity->getId()]);
		unset($this->elist[$entity->getId()]);
		if($this->rep==1){
		$pe=$this->cachec[$entity->getId()];
		foreach($this->getServer()->getOnlinePlayers() as $j){
			$skin=$j->getSkinData();
			$sks=$j->isSkinSlim();
			break;
		}
		$et=$this->spaw($pe["x"],$pe["y"],$pe["z"],$pe["name"],$this->getServer()->getLevel($pe["lid"]),$pe["health"],$skin,$sks);
$et->setDataProperty("fuck",1,$pe["npc"]);
//$et->setDataProperty("kq",1,$a[0]);
$this->cachec[$et->getId()]=$pe;
$et->setNameTag($pe["nt"]);
//$et->setDataProperty("jtag",4,$pe["nt"]);
$this->elist[$et->getId()]=$et;
}
unset($this->cachec[$entity->getId()]);

	}
	
	
	
}
}
class jb extends PluginTask
{
    public $p;

    public function __construct(kill $plugin)
    {
        parent::__construct($plugin);
        $this->p = $plugin;
		$this->dm=$plugin->c->get("dm");
			$this->sh=$plugin->c->get("knock");
    }

    public function onRun($currentTick)
    {   
	$p=$this->p;
	$s=$p->getServer();
	
     foreach($p->klist as $name=>$ply){
		
		 if(($pl=$s->getPlayer($ply))!=null){
	if (!array_key_exists($name,$p->elist)) continue; 
	
			 $ent=$p->elist[$name];
    $ent->setNameTag($p->cachec[$ent->getId()]["nt"]."  HP:".$ent->getHealth()."/".$ent->getMaxHealth());
	
			  $spawn = new Vector3($p->cachec[$ent->getId()]["x"],$p->cachec[$ent->getId()]["y"],$p->cachec[$ent->getId()]["z"]);
			 if($pl->distance($ent) > $p->cachec[$ent->getId()]["仇恨范围"] || $ent->distance($spawn) > $p->cachec[$ent->getId()]["仇恨范围"] ){echo "raise event";$this->BackToSpawn($ent); continue; }
					//$add=0.15;
				$y=$pl->y-$ent->y;
				$x=$pl->x-$ent->x;
				$atn = atan2($z, $x);
			if($p->elist[$name]->getDataProperty("fuck")==1){
				$ent=$p->elist[$name];
				$px=$pl->getPosition();
				
				
				if($ent->distance(new Vector3($px->getX(),$px->getY(),$px->getZ())) <= 0.8){
				$ev = new EntityDamageByEntityEvent($ent, $pl, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $p->cachec[$ent->getId()]["伤害"],0.5);
				$pl->attack($ev->getFinalDamage(), $ev);
					//$ent->setRotation(rad2deg($atn -M_PI_2),rad2deg(-atan2($y, sqrt($x ** 2 + $z ** 2))));
					
			    } 
			    else
			    {
						$sp=$p->cachec[$ent->getId()]["speed"];
					
			   // $ent->move(cos($atn = atan2($z, $x)) * $add,0, sin($atn) * $add);
            /*$ent->yaw = rad2deg($atn -M_PI_2);
            $ent->pitch = rad2deg(-atan2($y, sqrt($x ** 2 + $z ** 2)));*/
				$ent->move(($px->x-$ent->x)/$sp,($px->y-$ent->y)/$sp,($px->z-$ent->z)/$sp);
				//$ent->setRotation(rad2deg($atn -M_PI_2),rad2deg(-atan2($y, sqrt($x ** 2 + $z ** 2))));
		
			    }
				$ent->setRotation(rad2deg($atn -M_PI_2),rad2deg(-atan2($y, sqrt($x ** 2 + $z ** 2))));
              continue; 
			
		 }
		 //$px=$pl->getPosition();
		 $ent->setRotation(rad2deg($atn -M_PI_2),rad2deg(-atan2($y, sqrt($x ** 2 + $z ** 2))));
		     if(mt_rand(1, 27) < 4 && $ent->distance($pl) <= 10){
            $f = 1.8;
            $yaw = $ent->yaw + mt_rand(-180, 180) / 10;
            $pitch = $ent->pitch + mt_rand(-90, 90) / 10;
            $nbt = new Compound("", [
                "Pos" => new Enum("Pos", [
                    new Double("", $ent->x),
                    new Double("", $ent->y + 1.62),
                    new Double("", $ent->z)
                ]),
                "Motion" => new Enum("Motion", [
                    new Double("", -sin($yaw / 180 * M_PI) * cos($pitch / 180 * M_PI) * $f),
                    new Double("", -sin($pitch / 180 * M_PI) * $f),
                    new Double("", cos($yaw / 180 * M_PI) * cos($pitch / 180 * M_PI) * $f)
                ]),
                "Rotation" => new Enum("Rotation", [
                    new Float("", $yaw),
                    new Float("", $pitch)
                ]),
            ]);
            $arrow = Entity::createEntity("Arrow", $ent->chunk, $nbt, $ent);
            $ev = new EntityShootBowEvent($ent, Item::get(Item::ARROW, 0, 1), $arrow, $f);

            $s->getPluginManager()->callEvent($ev);

            $projectile = $ev->getProjectile();
            if($ev->isCancelled()){
                $ev->getProjectile()->kill();
            }elseif($projectile instanceof Projectile){
                $s->getPluginManager()->callEvent($launch = new ProjectileLaunchEvent($projectile));
                if($launch->isCancelled()){
                    $projectile->kill();
                }else{
                    $projectile->spawnToAll();
                   
                }
            }
        }
		else
		{
			$sp=$p->cachec[$ent->getId()]["speed"];
				$ent->move(($pl->x-$ent->x)/$sp,($pl->y-$ent->y)/$sp,($pl->z-$ent->z)/$sp);
		}
	 }
    }
}

public function BackToSpawn($ent){
foreach($p->klist as $name=>$ply){

$spawn = new Vector3($p->cachec[$ent->getId()]["x"],$p->cachec[$ent->getId()]["y"],$p->cachec[$ent->getId()]["z"]);
$s->getPlayer($ply)->sendMessage($p->msg->get("npc-back-to-spawn-tip"));
$ent->setPosition($spawn);
$this->heal($ent);
unset($p->klist[$ent->getId()]);
continue;
}
}

public function heal($ent){
$ent->setHealth((int)$p->cachec[$ent->getId()]["health"]);
}

}
