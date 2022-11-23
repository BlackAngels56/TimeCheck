<?php

declare(strict_types=1);

namespace BlackAngels56\TimeCheck;

use DateTime;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener
{
    public $pt;

    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getConfig();
    }

    public function onJoin(PlayerJoinEvent $ev)
    {
        $playername = $ev->getPlayer()->getName();
        $this->pt[$playername] = time();
    }

    public function onDisconnect(PlayerQuitEvent $ev)
    {
        $pn = $ev->getPlayer()->getName();
        $tq = time();
        $tj = $this->pt[$pn];
        $tc = $this->getConfig()->get($pn);
        $this->getConfig()->set($pn, $tc + ($tq - $tj));
        $this->getConfig()->save();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        $time = $this->getConfig()->get($args[0] ?? $sender->getName());
        if (!$time) return false;
        $date = new DateTime("00:00:00");
        $date->modify("+ $time seconds");

        $this->getServer()->broadcastMessage($date->format("H:i:s"));
    }

}
