Feature: Two columns for post list
  As a Blog manager
  I want to have a post list at two columns
  In order that it looks better

  Background:
    Given the following authors exist:
      | name             |
      | someone          |
      | over             |
      | rainbow          |
    And the following posts exist:
      | title     | content      | author  |
      | way       | up high      | someone |
      | land      | I heard of   | over    |
      | once      | In a lullaby | rainbow |


  Scenario: Visit home page
    Given I visit the home page
    Then The post with title "way" is on the first column, first row
    And  The post with title "land" is on the second column, first row
    And  The post with title "once" is on the first column, second row
