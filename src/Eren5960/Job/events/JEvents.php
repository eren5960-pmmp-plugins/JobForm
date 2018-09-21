<?php

/**
*  _____                    ____   ___    __     ___  
* | ____| _ __  ___  _ __  | ___| / _ \  / /_   / _ \ 
* |  _|  | '__|/ _ \| '_ \ |___ \| (_) || '_ \ | | | |
* | |___ | |  |  __/| | | | ___) |\__, || (_) || |_| |
* |_____||_|   \___||_| |_||____/   /_/  \___/  \___/ 
* 
* @version v1.2
* @author Eren5960
* @link https://github.com/Eren5960/JobForm
*/                  

namespace Eren5960\Job\events;

use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockEvent;
use Eren5960\Job\Main;

class JEvents implements Listener{
	
	public function JBreak(BlockBreakEvent $e): void{
		$this->active($e);
	}

    public function JPlace(BlockPlaceEvent $e): void{
		$this->active($e);
	}

	private function active(BlockEvent $event): void{
		$block = $event->getBlock();
		$player = $event->getPlayer();
		$api = Main::getAPI();
		if($api->inJob($player)){
			if($api->subJobs($player, $block->getId().":".$block->getDamage().":place")){
				$api->earnMoney($player, $block->getId().":".$block->getDamage().":place");
			}
		}
	}
}
