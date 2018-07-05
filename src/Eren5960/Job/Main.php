<?php

/**
*  _____                    ____   ___    __     ___  
* | ____| _ __  ___  _ __  | ___| / _ \  / /_   / _ \ 
* |  _|  | '__|/ _ \| '_ \ |___ \| (_) || '_ \ | | | |
* | |___ | |  |  __/| | | | ___) |\__, || (_) || |_| |
* |_____||_|   \___||_| |_||____/   /_/  \___/  \___/ 
* 
* @version v1
* @name JobForm
* @author Eren5960
* @link https://github.com/Eren5960/JobForm
*/                             

namespace Eren5960\Job;

use Eren5960\Job\commands\JCommand;
use Eren5960\Job\events\JEvents;
use Eren5960\Job\lang\Lang;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\Item;
use pocketmine\form\{MenuOption, FormIcon};
use pocketmine\plugin\PluginBase;
use pocketmine\Player;

class Main extends PluginBase{
	
	/** @var Main $api */
	private static $api;
	
	/** @var EconomyAPI $eco */
	public $eco;
	
	/** @var Config $cfg */
	public $cfg;
	
	/** @var array $qjob */
	public $qjob = [];
	
	
	public function onLoad():void
	{
		self::$api = $this;
	}
	
	public function onEnable():void
	{
		if(!class_exists('onebone\economyapi\EconomyAPI')){ 
		$this->getLogger()->error("EconomyAPI is required to use this plugin.");
		 $this->setEnabled(false); 
		return; 
		}
		@mkdir($gdf = $this->getDataFolder());
		$this->saveDefaultConfig();
		Lang::init();
		$this->eco = EconomyAPI::getInstance();
		$this->cfg = $this->getConfig();
		$this->getServer()->getCommandMap()->register("JobUI", new JCommand());
		$this->getServer()->getPluginManager()->registerEvents(new JEvents(),$this);
	}
	
	public static function getAPI() : Main
	{
		return self::$api; 
	}
	
	private function getEarn(Player $p, string $label):int
	{
                return $this->cfg->get($this->getPlayerJob($p))[$label];
	}
	
	public function jobs():array
	{
		return $this->cfg->getAll();
	}
	
	public function addJob(Player $p, string $jobName):bool
	{
		if($this->inJob($p)){
			return false;
		}else{
			$this->qjob[$p->getName()] = $jobName;
		   	return true;
		}
	}
	
	public function quitJob(Player $p):bool
	{
		if($this->inJob($p)){
			unset($this->qjob[$p->getName()]);
			return true;
		}else{
			return false;
		}
	}
	
	public function inJob(Player $p):bool
	{
		if(isset($this->qjob[$p->getName()])){
			return true;
		}else{
			return false;
		}
	}
	
	public function earnMoney(Player $p, string $label):void
	{
		$this->eco->addMoney($p,$this->getEarn($p,$label)); 
	}
	
        public function subJobs(Player $p, string $nameii):bool
	{
                $values = $this->cfg->get($this->getPlayerJob($p));
                return isset($values[$nameii]) ? true : false;
        }

	public function buttons(Player $p):array
	{
                $l = [];
                $lang = new Lang($p);
                $l[] = new MenuOption($lang->translate("Job.quit.button.name"));
		foreach($this->jobs() as $name => $money){
		  	  $l[] = new MenuOption($name, new FormIcon($this->cfg->get($name)["image"], FormIcon::IMAGE_TYPE_PATH));
		}
		return $l;
	}

	public function getPlayerJob(Player $p):?string
	{
		return $this->qjob[$p->getName()];
	}
}
