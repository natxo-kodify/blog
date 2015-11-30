Feature: Comments
  As a Blog manager
  I want to allow people to rate the posts on the blog
  In order that they interact a little bit

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

    And the following posts ratings exist:
      | post_title | rating |
      | once       | 4      |
      | land       | 3      |

  Scenario: No ratings on a post page
    Given I am on "/posts/way"
    Then I should see "There are no ratings"

  Scenario: Rate a post
    Given I am on "/posts/way"
#   The next three should be run with selenium or zombie
    When  I give the post a rating of "5"
    And   I give the post a rating of "3"
    Then  I should see "4" in the ".post-rating" element

  Scenario: Order at posts list
    Given I am on the homepage
    And   Post with title "land" has a mean rating of "3"
    And   Post with title "once" has a mean rating of "4"
    Then  Posts should be ordered by date
    And   I follow "Order by rating"
    Then  Post with title "land" is after post with title "once"