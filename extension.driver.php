<?php

	Class extension_shopping_cart extends Extension
	{
		public function about(){
			return array('name' => 'Shopping Cart',
				'version' => '1.0',
				'release-date' => '2009-16-12',
				'author' => array('name' => 'Andrey Lubinov',
					'website' => 'http://las.com.ua',
					'email' => 'andrey.lubinov@gmail.com')
			);
		}
		
		public function getSubscribedDelegates(){
			return array(
				array(
					'page' => '/blueprints/events/new/',
					'delegate' => 'AppendEventFilter',
					'callback' => 'addFilterToEventEditor'
				),
				array(
					'page' => '/blueprints/events/edit/',
					'delegate' => 'AppendEventFilter',
					'callback' => 'addFilterToEventEditor'
				),
				array(
					'page' => '/frontend/',
					'delegate' => 'EventPostSaveFilter',
					'callback' => 'processEventData'
				)
			);
		}
		
		public function addFilterToEventEditor($context){ 
			$context['options'][] = array('cart-drop-all', @in_array('cart-drop-all', $context['selected']) , __('Cart: Drop All Items'));   
		}
		
		public function processEventData($context){
			if(!in_array('cart-drop-all', $context['event']->eParamFILTERS)) return;
			$_SESSION[__SYM_COOKIE_PREFIX_ . 'cart'] = null;
		}
		
		public function install() {
			try{
				Symphony::Database()->query("CREATE TABLE IF NOT EXISTS `tbl_fields_price` (
					  `id` int(11) unsigned NOT NULL auto_increment,
					  `field_id` int(11) unsigned NOT NULL,
				  PRIMARY KEY  (`id`),
				  KEY `field_id` (`field_id`)
				)");
			}
			catch(Exception $e){
				return false;
			}			
			return true;
		}
		
		public function uninstall() {
			if(parent::uninstall() == true){
				Symphony::Database()->query("DROP TABLE `tbl_fields_price`");
				return true;
			}			
			return false;
		}
	}

