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

namespace Eren5960\Job\events;

use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockEvent;
use Eren5960\Job\Main;

class JEvents implements Listener{

    /**
     * @param BlockBreakEvent $e
     */
    public function JBreak(BlockBreakEvent $e): void{
		$this->active($e);
	}

    /**
     * @param BlockPlaceEvent $e
     */
    public function JPlace(BlockPlaceEvent $e): void{
		$this->active($e);
	}

    /**
     * @param BlockEvent $event
     */
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
