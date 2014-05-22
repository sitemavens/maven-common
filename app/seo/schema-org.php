<?php

namespace Maven\Seo;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class SchemaOrg {
	
	public function __construct() {
		;
	}
	
	public static function eventHtml( \Maven\Seo\Schemas\Event $event , $echo = true){
		//http://schema-creator.org/event.php
		
		$template = array();
		$template[] = "<div itemscope itemtype='http://schema.org/Event'>";
		$template[] = "		<a itemprop='url' href='{$event->getLocation()->getUrl()}'>";
		$template[] = "			<span itemprop='name'> {$event->getName()} </span>";
		$template[] = "		</a>";
		$template[] = "		<div itemprop='description'>{$event->getDescription()}</div>";
		$template[] = "		<div><meta itemprop='startDate' content='{$event->getStartDate()}'>{$event->getStartDate()}</div>";
		$template[] = "		<div><meta itemprop='endDate'   content='{$event->getEndDate()}'>{$event->getEndDate()}</div>";
		
		if ( $event->getDuration() )
			$template[] = "		<div><meta itemprop='duration'  content='{$event->getDuration()}'>{$event->getDuration()}</div>";

		$template[] = "		<div itemprop='location' itemscope itemtype='http://schema.org/Place'>";
		$template[] = "			<a itemprop='url' href='{$event->getLocation()->getUrl()}'>";
		$template[] = "				{$event->getLocation()->getName()}";
		$template[] = "			</a>";
		$template[] = "			<div itemprop='address' itemscope itemtype='http://schema.org/PostalAddress'>";
		$template[] = "				<span itemprop='addressLocality'>Philadelphia</span>,";
		$template[] = "				<span itemprop='addressRegion'>PA</span>";
		$template[] = "			</div>";
		$template[] = "		</div>";

		$template[] = "		<div itemprop='offers' itemscope itemtype='http://schema.org/AggregateOffer'>";
		$template[] = "			Priced from: <span itemprop='lowPrice'>{$event->getOffers()->getPriceSpecification()->getPrice() }</span>";
		$template[] = "			<span itemprop='offerCount'>1938</span> tickets left";
		$template[] = "		</div>";
		$template[] = "	</div>";
							
			
					
		$templateString = implode("\n", $template);
		
		if ( $echo )
			echo $templateString;
		else
			return $templateString;
		
	}
	
	private static function personHtml(){
		
	}
	
} 



