1.  Get selenium server from http://seleniumhq.org/download/

2.  Get php-webdriver and Menta
	git clone https://fbrnc@github.com/fbrnc/php-webdriver.git php-webdriver
	git clone https://fbrnc@github.com/fbrnc/Menta.git Menta

3.  If your selenium server runs on a different machine go to phpunit.xml and adapt the testing.selenium.seleniumServerUrl setting
(Or copy the phpunit.xml to foo.xml and run following command with --configuration=foo.xml)

4.  Run the sample test:
	cd Menta/Demo
	phpunit MentaDemoTest.php

