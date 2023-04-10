<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function testGetSingleArticleWithAuth()
    {
        $user = User::factory()->create();

        $article = Article::factory()->create();

        // send a GET request to the API endpoint for the article as the connected user
        $response = $this->actingAs($user)->json('GET', "/api/articles/{$article->id}", []);

        // assert that the response has a successful status code and contains the article data
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'title' => $article->title,
            'content' => $article->content,
            'status' => $article->status,
            'author' => [
                'id' => $article->author->id,
                'name' => $article->author->name,
                'email' => $article->author->email,
            ],
        ]);
    }


    public function testGetSingleArticleWithoutAuth(): void
    {
        // create an article
        $article = Article::factory()->create();

        $response = $this->json('get', '/api/articles/' . $article->id);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    public function testArticleFieldsCannotBeUpdatedIfPublished()
    {
        $user = User::factory()->create();

        // Create a draft article
        $article = Article::factory()->create(['status' => 'published']);

        // Update the article with a new title and status (not a draft)
        $response = $this->actingAs($user)->putJson("/api/articles/{$article->id}", [
            'title' => 'New Title',
            'status' => 'published',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ]);

        // Check that the response has a 422 status code (unprocessable entity)
        $response->assertStatus(422);
        $response->assertJsonFragment([
            "message" => "Cannot modify article if it is in a published status. Modify the status of the article first ! (and 1 more error)",
            "errors" => [
                "title" => [
                    "Cannot modify article if it is in a published status. Modify the status of the article first !"
                ],
                "content" => [
                    "Cannot modify article if it is in a published status. Modify the status of the article first !"
                ]
            ]
        ]);

        // Check that the article was not updated in the database
        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
            'title' => 'New Title',
            'status' => 'published',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ]);
    }

    public function testArticleCanBeSetToDraftOrPublishedOnlyIfStatusIsSet()
    {
        $user = User::factory()->create();

        // Create a draft article
        $article = Article::factory()->create(['status' => 'published']);

        // Update the article with only the status field (not a draft article)
        $response = $this->actingAs($user)->putJson("/api/articles/{$article->id}", [
            'status' => 'draft',
        ]);

        // Check that the response has a 200 status code
        $response->assertStatus(200);

        // Update the article with only the status field (not a published article)
        $response = $this->actingAs($user)->putJson("/api/articles/{$article->id}", [
            'status' => 'published',
        ]);

        // Check that the response has a 200 status code
        $response->assertStatus(200);
    }

    public function testArticleFieldsCanBeUpdatedIfDraft()
    {
        $user = User::factory()->create();

        // Create a draft article
        $article = Article::factory()->create(['status' => 'draft']);

        // Update the article with a new title and status (not a draft)
        $response = $this->actingAs($user)->putJson("/api/articles/{$article->id}", [
            'title' => 'New Title',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ]);

        // Check that the response has a 200 status code
        $response->assertStatus(200);

        // Check that the article was updated in the database
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'New Title',
            'status' => 'draft',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ]);
    }

    public function testArticleCanBeCreatedWithDraftOrPublishedStatusOnly()
    {
        // Create a user to act as the author of the article
        $user = User::factory()->create();

        // Create an article with a draft status
        $response = $this->actingAs($user)->postJson('/api/articles', [
            'title' => 'New Article',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'status' => 'draft',
        ]);

        // Check that the response has a 201 status code (created)
        $response->assertStatus(201);

        // Check that the article was created with the correct status
        $this->assertDatabaseHas('articles', [
            'title' => 'New Article',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'status' => 'draft',
            'author_id' => $user->id,
        ]);

        // Create an article with a published status
        $response = $this->actingAs($user)->postJson('/api/articles', [
            'title' => 'New Article',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'status' => 'published',
        ]);

        // Check that the response has a 201 status code (created)
        $response->assertStatus(201);

        // Check that the article was created with the correct status
        $this->assertDatabaseHas('articles', [
            'title' => 'New Article',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'status' => 'published',
            'author_id' => $user->id,
        ]);

        // Attempt to create an article with an invalid status
        $response = $this->actingAs($user)->postJson('/api/articles', [
            'title' => 'New Article',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'status' => 'invalid',
        ]);

        // Check that the response has a 422 status code (unprocessable entity)
        $response->assertStatus(422);

        // Check that the article was not created in the database
        $this->assertDatabaseMissing('articles', [
            'title' => 'New Article',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'status' => 'invalid',
            'author_id' => $user->id,
        ]);
    }

    public function testArticleCanBeScheduledForPublicationIfDraft()
    {
        // Create a user to act as the author of the article
        $user = User::factory()->create();

        // Create a draft article
        $article = Article::factory()->create(['status' => 'draft']);

        // Set a future publication date for the article
        $futureDate = now()->addDays(7)->format('Y-m-d\TH:i:sO');
        $response = $this->actingAs($user)->putJson("/api/articles/{$article->id}", [
            'title' => $article->title,
            'content' => $article->content,
            'published_at' => $futureDate,
        ]);

        // Check that the response has a 200 status code (OK)
        $response->assertStatus(200);

        // Check that the article was updated in the database
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'status' => 'draft',
            'published_at' => $futureDate,
        ]);

        // Set a past publication date for the article
        $pastDate = now()->subDays(7)->format('Y-m-d\TH:i:sO');
        $response = $this->actingAs($user)->putJson("/api/articles/{$article->id}", [
            'title' => $article->title,
            'content' => $article->content,
            'published_at' => $pastDate,
        ]);

        // Check that the response has a 422 status code (unprocessable entity)
        $response->assertStatus(422);
        $response->assertJsonFragment([
            "message" => "The published at must be a date after or equal to now.",
            "errors" => [
                "published_at" => [
                    "The published at must be a date after or equal to now."
                ]
            ]
        ]);

        // Check that the article was not updated in the database
        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
            'status' => 'draft',
            'published_at' => $pastDate,
        ]);
    }

    public function testArticleIsNoLongerAccessibleAfterDeletion()
    {
        // Create a user to act as the author of the article
        $user = User::factory()->create();

        // Create an article
        $article = Article::factory()->create();

        // Delete the article
        $response = $this->actingAs($user)->deleteJson("/api/articles/{$article->id}");

        // Check that the response has a 204 status code (no content)
        $response->assertStatus(204);

        // Try to get the article
        $response = $this->getJson("/api/articles/{$article->id}");

        // Check that the response has a 404 status code (not found)
        $response->assertStatus(404);
    }

    public function testArticleTitleLength()
    {
        // Create a user to act as the author of the article
        $user = User::factory()->create();

        // Create an article with a draft status
        $response = $this->actingAs($user)->postJson('/api/articles', [
            'title' => 'a long title of 128 char a long title of 128 char a long title of 128 char a long title of 128 char a long title of 128 char a long title of 128 char a long title of 128 char',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'status' => 'draft',
        ]);

        // Check that the response has a 422 status code (unprocessable entity)
        $response->assertStatus(422);
    }
}
