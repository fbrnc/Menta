<?php
/**
 * Abstract component class
 *
 * @author Fabrizio Branca
 * @since 2011-11-24
 */
abstract class Menta_Component_Abstract implements Menta_Interface_Component {

	/**
	 * @var WebDriver_Session
	 */
	protected $session;

	/**
	 * @var Menta_Interface_Configuration
	 */
	protected $configuration;

	/**
	 * Set session
	 *
	 * @param WebDriver_Session $session
	 * @return Menta_Component_Abstract
	 */
	public function setSession(WebDriver_Session $session) {
		$this->session = $session;
		return $this;
	}

	/**
	 * Get session
	 *
	 * @return WebDriver_Session
	 */
	public function getSession() {
		if (!$this->session instanceof WebDriver_Session) {
			$this->session = Menta_SessionManager::getSession();
		}
		return $this->session;
	}

	/**
	 * Set configuration
	 *
	 * @param Menta_Interface_Configuration $configuration
	 * @return Menta_Component_Abstract
	 */
	public function setConfiguration(Menta_Interface_Configuration $configuration) {
		$this->configuration = $configuration;
		return $this;
	}

	/**
	 * Get configuration
	 *
	 * @return Menta_Interface_Configuration
	 */
	public function getConfiguration() {
		return $this->configuration;
	}

}
