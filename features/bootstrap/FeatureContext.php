<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

ini_set('xdebug.max_nesting_level', -1);

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
}
