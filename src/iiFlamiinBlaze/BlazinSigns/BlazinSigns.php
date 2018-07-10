<?php
/**
 *  ____  _            _______ _          _____
 * |  _ \| |          |__   __| |        |  __ \
 * | |_) | | __ _ _______| |  | |__   ___| |  | | _____   __
 * |  _ <| |/ _` |_  / _ \ |  | '_ \ / _ \ |  | |/ _ \ \ / /
 * | |_) | | (_| |/ /  __/ |  | | | |  __/ |__| |  __/\ V /
 * |____/|_|\__,_/___\___|_|  |_| |_|\___|_____/ \___| \_/
 *
 * Copyright (C) 2018 iiFlamiinBlaze
 *
 * iiFlamiinBlaze's plugins are licensed under MIT license!
 * Made by iiFlamiinBlaze for the PocketMine-MP Community!
 *
 * @author iiFlamiinBlaze
 * Twitter: https://twitter.com/iiFlamiinBlaze
 * GitHub: https://github.com/iiFlamiinBlaze
 * Discord: https://discord.gg/znEsFsG
 */
declare(strict_types=1);

namespace iiFlamiinBlaze\BlazinSigns;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat;
use pocketmine\Player;

class BlazinSigns extends PluginBase implements Listener{

	private const VERSION = "v1.0.0";
	private const PREFIX = TextFormat::GOLD . "BlazinSigns" . TextFormat::AQUA . " > ";

	/** @var array $initSigns */
	private $initSigns = [];
	/** @var array $signText */
	private $signText = [];
	/** @var array $signLine */
	private $signLine = [];

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info("BlazinSigns " . self::VERSION . " by BlazeTheDev is enabled");
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if(strtolower($command->getName()) === "blazinsigns"){
			if(!$sender instanceof Player){
				$sender->sendMessage(self::PREFIX . TextFormat::RED . "Use this command in-game");
				return false;
			}
			if(!$sender->hasPermission("blazinsigns.command")){
				$sender->sendMessage(self::PREFIX . TextFormat::RED . "You do not have permission to use this command");
				return false;
			}
			if(empty($args[0])){
				$sender->sendMessage(self::PREFIX . TextFormat::GRAY . "Usage: /blazinsigns <line #> <text>");
				return false;
			}
			switch($args[0]){
				case "1":
					$this->initSigns[] = $sender->getName();
					$this->signLine[$sender->getName()] = 0;
					$this->signText[$sender->getName()] = implode(" ", array_slice($args, 1));
					$sender->sendMessage(self::PREFIX . TextFormat::GREEN . "Tap a sign now to change the first line of text");
					break;
				case "2":
					$this->initSigns[] = $sender->getName();
					$this->signLine[$sender->getName()] = 1;
					$this->signText[$sender->getName()] = implode(" ", array_slice($args, 1));
					$sender->sendMessage(self::PREFIX . TextFormat::GREEN . "Tap a sign now to change the second line of text");
					break;
				case "3":
					$this->initSigns[] = $sender->getName();
					$this->signLine[$sender->getName()] = 2;
					$this->signText[$sender->getName()] = implode(" ", array_slice($args, 1));
					$sender->sendMessage(self::PREFIX . TextFormat::GREEN . "Tap a sign now to change the third line of text");
					break;
				case "4":
					$this->initSigns[] = $sender->getName();
					$this->signLine[$sender->getName()] = 3;
					$this->signText[$sender->getName()] = implode(" ", array_slice($args, 1));
					$sender->sendMessage(self::PREFIX . TextFormat::GREEN . "Tap a sign now to change the fourth line of text");
					break;
				default:
					$sender->sendMessage(self::PREFIX . TextFormat::GRAY . "Usage: /blazinsigns <line #> <text>");
					break;
			}
		}
		return true;
	}

	public function onInteract(PlayerInteractEvent $event) : void{
		$player = $event->getPlayer();
		$tile = $event->getPlayer()->getLevel()->getTile($event->getBlock());
		if($tile instanceof Sign){
			if(in_array($player->getName(), $this->initSigns)){
				$tile->setLine($this->signLine[$player->getName()], $this->signText[$player->getName()]);
				$player->sendMessage(self::PREFIX . TextFormat::GREEN . "You have successfully set line #" . strval($this->signLine[$player->getName()] + 1) . " of this sign");
				unset($this->initSigns[array_search($player->getName(), $this->initSigns)]);
				unset($this->signLine[$player->getName()]);
				unset($this->signText[$player->getName()]);
			}
		}
	}
}