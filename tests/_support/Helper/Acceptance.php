<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{

  private $webDriver = null;
  private $webDriverModule = null;

  public function _before(\Codeception\TestCase $test)
  {
    if (!$this->hasModule('WebDriver') && !$this->hasModule('Selenium2')) {
      throw new \Exception('PageWait uses the WebDriver. Please be sure that this module is activated.');
    }
    // Use WebDriver
    if ($this->hasModule('WebDriver')) {
      $this->webDriverModule = $this->getModule('WebDriver');
      $this->webDriver = $this->webDriverModule->webDriver;
    }
  }
  
  public function waitPageLoad($timeout = 10)
  {
    $this->webDriverModule->wait(3);
    $this->webDriverModule->waitForJs('return document.readyState == "complete"', $timeout);
  }

}
