<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

ini_set('xdebug.max_nesting_level', -1);
date_default_timezone_set('UTC');

class FeatureContext implements Context, SnippetAcceptingContext
{
    private $minkContext;

    /** @BeforeScenario */
    public function iAm(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();
        $this->minkContext = $environment->getContext(
            'Behat\MinkExtension\Context\MinkContext'
        );
        $this->minkContext->visit('/login');
    }

    /** @Given I am :username */
    public function iAm2($username)
    {
        $this->minkContext->visit('/login');
        $this->minkContext->fillField("username", "sensorario");
        $this->minkContext->fillField("password", "sensorario");
        $this->minkContext->pressButton("accedi");
    }

    /**
     * @Then I have a village named :villageName
     */
    public function iHaveAVillageNamed($villageName)
    {
        $this->minkContext->getSession()
            ->setCookie('village', $villageName);
        $this->minkContext->visit('/');
    }

    /**
     * @Then I have a castle
     */
    public function iHaveACastle()
    {
        $this->minkContext->getSession()
            ->setCookie('village', 'mordor');
        $this->minkContext->getSession()
            ->setCookie('windmill-built', '1');
        $this->minkContext->getSession()
            ->setCookie('castle-built', '1');
        $this->minkContext->visit('/');
    }

    /**
     * @Then I have a temple
     */
    public function iHaveATemple()
    {
        $this->minkContext->getSession()
            ->setCookie('village', 'mordor');
        $this->minkContext->getSession()
            ->setCookie('castle-built', '1');
        $this->minkContext->getSession()
            ->setCookie('temple-built', '1');
        $this->minkContext->visit('/');
    }

    /**
     * @Then also have a windmill
     */
    public function iHaveAWindmill()
    {
        $this->minkContext->getSession()
            ->setCookie('windmill-built', '1');
        $this->minkContext->visit('/');
    }
}
