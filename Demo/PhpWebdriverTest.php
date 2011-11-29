<?php

// bootstrap php-webdriver (assuming it is in a directorey "php-webdriver" next to the Menta root directory
require_once dirname(__FILE__) . '/../../php-webdriver/WebDriver/__init__.php';

try {

	# get webdriver
	$webDriver = new WebDriver('http://seleniumserver:4444/wd/hub');

	# create session
	$session = $webDriver->session('firefox');

	$session->window('main'); // focus
	$session->window('main')->position(array('x' => 0, 'y' => 0)); // position
	$session->window('main')->size(array('width' => 1280, 'height' => 1024)); // size

	# Got to google
	$session->open('http://www.google.com/ncr');

	# Search
	$input = $session->element(WebDriver_Container::ID, 'lst-ib');
	$input->value(array('value' => array('AOE media')));
	$input->value(array('value' => array(WebDriver_Keys::ReturnKey)));

	sleep(2);

	$firstResult = $session->element(WebDriver_Container::XPATH, '//ol[@id="rso"]/li[1]//a');
	printf("Search result: %s\n", $firstResult->text());

	$firstResult->click();

	sleep(5);

	# Go back to search results
	$session->back();

	sleep(5);

} catch (Exception $e) {
	echo $e->getMessage();
}

# close session/connection
$session->close();
