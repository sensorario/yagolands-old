Feature: Guest user shoud user username and password
    to login into yagolands
    and start to play this game

    Scenario: guest user should logini as sensorario
        Given I go to "/"
        And I follow "login"
        Then I fill in "username" with "sensorario"
        And fill in "password" with "sensorario"
        When I press "accedi"
        Then the response should contain "Hi! sensorario"

    Scenario: sensorario should name its own village
        Given I am "sensorario"
        Then I fill in "village-name" with "Mordor"
        And press "accedi"
        Then the response should contain "Village: Mordor"

    Scenario: village is named and user build its own castle
        Given I am "sensorario"
        Then I have a village named "Mordor"
        And also have a windmill
        And I press "build castle"
        Then the response should contain "Seconds left:"

    Scenario: village is named and user build its own windmill
        Given I am "sensorario"
        Then I have a village named "Mordor"
        And I press "build windmill"
        Then the response should contain "Seconds left:"

    Scenario: castle is built and user build temple
        Given I am "sensorario"
        And I have a castle
        And I press "build temple"
        Then the response should contain "Seconds left:"

    Scenario: temple is built
        Given I am "sensorario"
        And I have a temple
        Then the response should contain "Congratulations sensorario, from the mordor village. You won the game."

