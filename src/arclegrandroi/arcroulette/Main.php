<?php

namespace arclegrandroi\arcroulette;

use pocketmine\command\Command;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use onebone\economyapi\EconomyAPI;


class Main extends PluginBase {
      public function onLoad() {
          $this->saveDefaultConfig();
      }
      public function onEnable() {
          @mkdir($this->getDataFolder());
       
        $this->getResource("config.yml");
      }
      public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
          $economy =
              EconomyAPI::getInstance();
           $money = ($this->getConfig()->get("money"));
        if($command->getName() == "roulette") {
            $count = count($args);
            if($count == 1) {
                if($sender instanceof Player) {
                if($economy->myMoney($sender) >= $money)  {
                   if(isset($args[0])) {
                   if($args[0] >=1 && $args[0] <= 36) {
                       $winnmr = mt_rand(1, 36);
                       $win = $money * 35;
                       if($args[0] == $winnmr) {
                           $economy->addMoney($sender, $win);
                           $sender->sendMessage($this->getConfig()->get("win"));
                       } else {
                          $economy->reduceMoney($sender, $money);
                          $sender->sendMessage($this->getConfig()->get("lose"));
                       }
                   } elseif($args[0] == "rouge"||$args[0] == "bleu") {
                       $rr = mt_rand(1,2);
                       if($rr == 1) {
                           if($args[0] == "rouge") {
                               $economy->addMoney($sender, $money);
                               $sender->sendMessage($this->getConfig()->get("red_win"));
                           } else {
                               $economy->reduceMoney($sender, $money);
                               $sender->sendMessage($this->getConfig()->get("red_lose"));
                           }
                       } elseif($rr == 2) {
                           if($args[0] == "bleu") {
                               $economy->addMoney($sender, $money);
                               $sender->sendMessage($this->getConfig()->get("bleu_win"));
                           } else {
                               $economy->reduceMoney($sender, $money);
                               $sender->sendMessage($this->getConfig()->get("bleu_lose"));
                           }
                       }   
                   } elseif(args[0] == "regle") {
                       $sender->sendMessage($this->getConfig()->get("regle"));
                   } else {
                       $sender->sendMessage($this->getConfig()->get("notgod"));
                   }
                   } else {
                       $sender->sendMessage($this->getConfig()->get("no_arg"));
                   }
                } else {
                    $sender->sendMessage($this->getConfig()->get("no_money"));
                }
                } else { 
                    $sender->sendMessage($this->getConfig()->get("in_game"));
                }  
                } else {
                $sender->sendMessage($this->getConfig()->get("many_args"));
            }
        }
        return true;
    }
}
