Feature: Guest user shoud user username and password
    to login into yagolands
    and start to play this game

    Scenario: guest user should logini as sensorario
        Given I go to "/login"
        Then I fill in "username" with "sensorario"
        And fill in "password" with "sensorario"
        When I press "accedi"
        Then the response should contain "sensorario"
