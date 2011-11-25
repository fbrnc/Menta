<?php

// bootstrap php-webdriver (assuming it is in a directorey "php-webdriver" next to the Menta root directory
require_once dirname(__FILE__) . '/../../php-webdriver/WebDriver/__init__.php';

// bootstrap Menta testing framework
require_once dirname(__FILE__) . '/../bootstrap.php';

// Add additional files (with default values) to configuration
Menta_ConfigurationPhpUnitVars::addConfigurationFile(dirname(__FILE__) . '/defaults.xml');

// Initialize session manager and provider selenium server url
Menta_SessionManager::init(Menta_ConfigurationPhpUnitVars::getInstance()->getValue('testing.selenium.seleniumServerUrl'));

// Do some stuff based on configuration values after the session is initialized
Menta_Events::addObserver('after_session_create', function(WebDriver_Session $session, $forceNew) {

	$configuration = Menta_ConfigurationPhpUnitVars::getInstance();
		
	// window focus
	if ($configuration->issetKey('testing.selenium.windowFocus') && $configuration->getValue('testing.selenium.windowFocus')) {
		$session->window('main'); // focus
	}

	// window position
	if ($configuration->issetKey('testing.selenium.windowPosition')) {
		list($x, $y) = explode(',', $configuration->getValue('testing.selenium.windowPosition'));
		$x = intval(trim($x)); $y = intval(trim($y));
		$session->window('main')->position(array('x' => $x, 'y' => $y));
	}

	// window size
	if ($configuration->issetKey('testing.selenium.windowSize')) {
		list($width, $height) = explode('x', $configuration->getValue('testing.selenium.windowSize'));
		$width = intval(trim($width)); $height = intval(trim($height));
		if (empty($height) || empty($width)) {
			throw new Exception('Invalid window size');
		}
		$session->window('main')->size(array('width' => $width, 'height' => $height));
	}

	// implicit wait
	if ($configuration->issetKey('testing.selenium.timeoutImplicitWait')) {
		$time = $configuration->getValue('testing.selenium.timeoutImplicitWait');
		$time = intval($time);
		$session->timeouts()->implicit_wait(array('ms' => $time));
	}
	
});

// Provide configuration object to all components
Menta_Events::addObserver('after_component_create', function(Menta_Component_Abstract $component) {
	$component->setConfiguration(Menta_ConfigurationPhpUnitVars::getInstance());
});


// WebDriver_Base::$debugFile = 'debug.txt';