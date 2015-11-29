Feature: Comments
  As a Blog manager
  I want to allow comments from authors
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
    And the following comments exist:
      | text              | post title | author  |
      | nice!!            | way        | someone |
      | Is that a song?   | land       | rainbow |

  Scenario: No comments on a post page
    Given I am on "/posts?title=once"
    Then I should see "There are no comments"

  Scenario: See comments on a post page
    Given I am on "/posts?title=way"
    Then I should see 1 ".comment" elements
    And I should see "nice!!" in the ".comment-text" element
    And I should not see "Is that a song?" in the ".comment-text" element

  Scenario: Create a comment
    Given I am on "/posts?title=once"
    And I should not see ".comment"
    When I follow "Comment"
    And I select "someone" from "comment[author]"
    And I fill in "comment[text]" with "Judy Garland was great!"
    And I press "Publish"
    Then the post with title "once" should have a comment with the text "Judy Garland was great!"
    Then I should see 1 ".comment" elements
    Then I should see "Judy Garland was great!" in the ".comment-text" element