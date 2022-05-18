<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Comment;

class PostTest extends TestCase
{ 
    use RefreshDatabase;

    public function testNoBlogPostsWhenNothingInDatabase()
    {
        $response = $this->get('/posts');

        $response->assertSeeText('No Posts found!');
    }

    public function testSee1BlogPostWhenThereIs1()
    {
        // Arrange
        $post = $this->createDummyBlogPost();

        // Act
        $response = $this->get('/posts');

        // Assert
        $response->assertSeeText('New Title');

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'New Title'
        ]);
    }

    public function testSee1BlogPostWithComments()
    {
        // Arrange
        $post = $this->createDummyBlogPost();
        // factory(Comment::class)->create(['blog_post_id' => 2]); // NOT WORKING IN THAT VERSION OF LARAVEL
        Comment::factory(4)->create([
            'blog_post_id' => $post->id
        ]);

        // Act
        $response = $this->get('/posts');

        // Assert
        $response->assertSeeText('4 comments');
    }

    public function testStoreValid()
    {
        $user = $this->user();

        $params = [
            'title' => 'Valid',
            'content' => 'At least 10 characters',
        ];

        $this->actingAs($user);

        # Action / Assert
        $this->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'The blog post was created!');
    }

    public function testStoreFail()
    {
        $user = $this->user();

        $params = [
            'title' => 'x',
            'content' => 'x',
        ];

        $this->actingAs($user);

        # Action / Assert
        $this->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();

        $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');

        // dd($messages->getMessages());
    }

    public function testUpdateValid()
    {
        // Arrange
        $post = $this->createDummyBlogPost();

        // Act
        // $response = $this->get('/posts');

        // $this->assertDatabaseHas('blog_posts', $post->toArray()); // failing because list of fields in different order

        $params = [
            'title' => 'A new name title',
            'content' => 'Content was changed',
        ];

        # Action / Assert
        $this->put("/posts/{$post->id}", $params)
            ->assertStatus(302)
            ->assertSessionHas('status');
        
        $this->assertEquals(session('status'), 'Blog post was updated!');

        $this->assertDatabaseMissing('blog_posts', $post->toArray());

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'A new name title',
            'content' => 'Content was changed',
        ]);
    }

    public function testDelete()
    {   
        $user = $this->user();
        $this->actingAs($user);

        // Arrange
        $post = $this->createDummyBlogPost();
        // $this->assertDatabaseHas('blog_posts', $post->toArray()); // failing because list of fields in different order


        # Action / Assert
        $this->delete("/posts/{$post->id}")
            ->assertStatus(302)
            ->assertSessionHas('status');
        
        $this->assertEquals(session('status'), 'Blog post was deleted!');

        $this->assertDatabaseMissing('blog_posts', $post->toArray());
    }

    private function createDummyBlogPost(){
        $user = $this->user();
        $this->actingAs($user);
        // $post = new BlogPost();
        // $post->title = 'New title';
        // $post->content = 'Content of the blog post';
        // $post->save();

        return BlogPost::factory()->newTitle()->create();
        // return BlogPost::factory()->configure('new-title')->create(); // WRONG

        // return $post;
    }
}
