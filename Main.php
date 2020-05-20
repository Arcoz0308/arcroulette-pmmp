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
            if($count === 1) {
                if($sender instanceof Player) {
                if($economy->myMoney($sender) >= $money)  {
                   if(isset($args[0])) {
                   if($args[0] >=1 && $args[0] <= 36) {
                       $r = mt_rand(1, 36);
                       $win = $money * 35;
                       if($args[0] == $r) {
                           $economy->addMoney($sender, $win);
                           $sender->sendMessage("vous avez gagner, le chiffre gagnant est le" . $r);
                       } else {
                          $economy->reduceMoney($sender, $money);
                          $sender->sendMessage("vous avez perdu, le chiffre gagnant est le" . $r);
                       }
                   } elseif($args[0] === "rouge"||$args[0] === "bleu") {
                       $rr = mt_rand(1,2);
                       if($rr === 1) {
                           if($args[0] === "rouge") {
                               $economy->addMoney($sender, $money);
                               $sender->sendMessage("vous avez gagnez, la couleur gagnante est le rouge");
                           } else {
                               $economy->reduceMoney($sender, $money);
                               $sender->sendMessage("vous avez perdu, la couleur gagnante est le rouge");
                           }
                       } elseif($rr === 2) {
                           if($args[0] === "bleu") {
                               $economy->addMoney($sender, $money);
                               $sender->sendMessage("vous avez gagner, la couleur gagnante est le bleu");
                           } else {
                               $economy->reduceMoney($sender, $money);
                               $sender->sendMessage("vous avez perdu, la couleur gagnante est le bleu");
                           }
                       }   
                   } elseif(args[0] === "regle") {
                       $sender->sendMessage($this->getConfig()->get("regle"));
                   } else {
                       $sender->sendMessage($this->getConfig()->get("no_arg"));
                   }
                   } else {
                       $sender->sendMessage($this->getConfig()->get("no_arg"));
                   }
                } else {
                    $sender->sendMessage($this->getConfig()->get("no_money"));
                }
                } else { 
                    $sender->sendMessage("cette commande dois etre faitent en jeu");
                }  
                } else {
                $sender->sendMessage($this->getConfig()->get("many_args"));
            }
        }
        return true;
    }
}