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

namespace Eren5960\Job\commands;

use Eren5960\Job\forms\JForm;
use pocketmine\command\{
    Command, CommandSender
};

class JCommand extends Command{

    /**
     * JCommand constructor.
     */
    public function __construct(){
  		   parent::__construct("job");
  		   $this->setPermission("job.use");
  	}

    /**
     * @param CommandSender $sender
     * @param string $line
     * @param array $args
     * @return mixed|void
     */
    public function execute(CommandSender $sender, string $line, array $args){
        if(isset($args[0])){
            $sender->sendMessage("Usage: /job");
        }else{
            $sender->sendForm(new JForm($sender));
        }
    }
}
