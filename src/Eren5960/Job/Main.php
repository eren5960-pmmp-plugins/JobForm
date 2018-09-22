<?php
/**
*  _____                    ____   ___    __     ___  
* | ____| _ __  ___  _ __  | ___| / _ \  / /_   / _ \ 
* |  _|  | '__|/ _ \| '_ \ |___ \| (_) || '_ \ | | | |
* | |___ | |  |  __/| | | | ___) |\__, || (_) || |_| |
* |_____||_|   \___||_| |_||____/   /_/  \___/  \___/ 
*
* @author Eren5960
* @link https://github.com/Eren5960
*/                             
declare(strict_types = 1);

namespace Eren5960\Job;

use Eren5960\Job\commands\JCommand;
use Eren5960\Job\events\JEvents;
use Eren5960\Job\lang\Lang;
use onebone\economyapi\EconomyAPI;
use pocketmine\form\{
	MenuOption, FormIcon
};
use pocketmine\plugin\PluginBase;
use pocketmine\Player;

class Main extends PluginBase{
	/** @var Main */
	private static $api;
	/** @var EconomyAPI */
	public $eco;
	/** @var Config */
	public $cfg;
	/** @var array */
	public $jobs = [];

	public function onLoad(): void{
		self::$api = $this;
	}
	
	public function onEnable(): void{
		if(!class_exists('onebone\economyapi\EconomyAPI')){ 
			$this->getLogger()->critical("EconomyAPI is required to use this plugin.");
		 	$this->setEnabled(false); 
			return; 
		}
		$this->saveDefaultConfig();
		Lang::init();
		$this->eco = EconomyAPI::getInstance();
		$this->cfg = $this->getConfig();
		$this->getServer()->getCommandMap()->register("JobUI", new JCommand);
		$this->getServer()->getPluginManager()->registerEvents(new JEvents, $this);
	}

    /**
     * @return Main
     */
    public static function getAPI(): Main{
		return self::$api; 
	}

    /**
     * @param Player $p
     * @param string $job
     * @return int|null
     */
    private function getEarn(Player $p, string $job): ?int{
        return !is_null($this->getPlayerJob($p)) ? $this->cfg->get($this->getPlayerJob($p))[$job] : null;
	}

    /**
     * @return array
     */
    public function jobs(): array{
		return $this->cfg->getAll();
	}

    /**
     * @param Player $p
     * @param string $jobName
     * @return bool
     */
    public function addJob(Player $p, string $jobName): bool{
		$job = !$this->inJob($p);
		if($job) $this->jobs[$p->getName()] = $jobName;
		return $job;
	}

    /**
     * @param Player $p
     * @return bool
     */
    public function quitJob(Player $p): bool{
		$job = $this->inJob($p);
		if($job) unset($this->jobs[$p->getName()]);
		return $job;
	}

    /**
     * @param Player $p
     * @return bool
     */
    public function inJob(Player $p): bool{
		return isset($this->jobs[$p->getName()]);
	}

    /**
     * @param Player $p
     * @param string $label
     */
    public function earnMoney(Player $p, string $label): void{
		$this->eco->addMoney($p,$this->getEarn($p,$label)); 
	}

    /**
     * @param Player $p
     * @param string $name
     * @return bool
     */
    public function subJobs(Player $p, string $name): bool{
        $values = $this->cfg->get($this->getPlayerJob($p));
        return isset($values[$name]);
    }

    /**
     * @param Player $p
     * @return array
     */
    public function buttons(Player $p): array{
        $menuoptions = [];
        $lang = new Lang($p);
        $menuoptions[] = new MenuOption($lang->translate("Job.quit.button.name"));
		foreach($this->jobs() as $name => $money){
		    $menuoptions[] = new MenuOption($name, isset($this->cfg->get($name)["image"]) ? new FormIcon($this->cfg->get($name)["image"], FormIcon::IMAGE_TYPE_PATH) : null);
		}
		return $menuoptions;
	}

    /**
     * @param Player $p
     * @return null|string
     */
    public function getPlayerJob(Player $p): ?string{
		return $this->jobs[$p->getName()];
	}
}