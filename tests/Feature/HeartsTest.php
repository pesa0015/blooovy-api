<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Book;
use App\Bookshelf;
use App\User;

class HeartsTest extends TestCase
{
    /**
     * @group storeHeart
     *
     */
    public function testStoreHeart()
    {
        $me = $this->newUser(true);

        $token = $me->token;

        $book = factory(Book::class)->create();

        // Test post with missing payload
        $response = $this->callHttpWithToken('POST', 'hearts', $token);
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'userId' => ['The user id field is required.'],
                'bookId' => ['The book id field is required.']
            ]
        ]);

        $payload = [
            'bookId' => $book->id
        ];

        // Test post with missing userId payload
        $response = $this->callHttpWithToken('POST', 'hearts', $token, $payload);
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'userId' => ['The user id field is required.']
            ]
        ]);

        $user = factory(User::class)->create();

        $payload = [
            'bookId' => $book->id,
            'userId' => $user->id
        ];

        $response = $this->callHttpWithToken('POST', 'hearts', $token, $payload);
        $response->assertStatus(403);

        $this->assertDatabaseMissing('bookshelves', [
            'user_id' => $me->user->id,
            'book_id' => $book->id
        ]);

        $this->assertEquals($response->getData(), 'user_have_not_liked_book');

        $bookshelf = factory(Bookshelf::class)->create();

        $this->assertDatabaseHas('bookshelves', [
            'user_id' => $me->user->id,
            'book_id' => $book->id
        ]);

        $this->assertDatabaseMissing('bookshelves', [
            'user_id' => $user->id,
            'book_id' => $book->id
        ]);

        $response = $this->callHttpWithToken('POST', 'hearts', $token, $payload);
        $response->assertStatus(403);
        
        $this->assertEquals($response->getData(), 'partner_have_not_liked_book');

        $user->books()->attach($book);

        $this->assertDatabaseHas('bookshelves', [
            'user_id' => $user->id,
            'book_id' => $book->id
        ]);

        $this->assertDatabaseMissing('hearts', [
            'user_id'       => $me->user->id,
            'heart_user_id' => $user->id,
            'book_id'       => $book->id
        ]);

        $response = $this->callHttpWithToken('POST', 'hearts', $token, $payload);
        $response->assertStatus(200);
        
        $this->assertDatabaseHas('hearts', [
            'user_id'       => $me->user->id,
            'heart_user_id' => $user->id,
            'book_id'       => $book->id
        ]);
    }
}