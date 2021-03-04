<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function testBookNotFound()
    {
        ['user' => $user, 'access_token' => $access_token] = $this->loginUser();

        $response = $this->json('POST', 'api/books/1/checkin', [], ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]);
        $response->assertStatus(404)
            ->assertJson([
                'data' => [],
                'message' => 'Book record not found!',
            ]);
    }

    public function testCheckoutAvailableBook()
    {
        $book = Book::factory()->create(['status' => Book::STATUS['AVAILABLE']]);
        ['user' => $user, 'access_token' => $access_token] = $this->loginUser();

        $response = $this->json('POST', 'api/books/' . $book->id . '/checkout', [], ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
                'message' => 'Success checking-out book',
            ]);
    }

    public function testCheckoutCheckedOutBook()
    {
        $book = Book::factory()->create(['status' => Book::STATUS['CHECKED_OUT']]);
        ['user' => $user, 'access_token' => $access_token] = $this->loginUser();

        $response = $this->json('POST', 'api/books/' . $book->id . '/checkout', [], ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
                'message' => 'The book is not available',
            ]);
    }

    public function testCheckinCheckedOutBook()
    {
        $book = Book::factory()->create(['status' => Book::STATUS['CHECKED_OUT']]);
        ['user' => $user, 'access_token' => $access_token] = $this->loginUser();

        $response = $this->json('POST', 'api/books/' . $book->id . '/checkin', [], ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
                'message' => 'Success checking-in book',
            ]);
    }

    public function testCheckinAvailableBook()
    {
        $book = Book::factory()->create(['status' => Book::STATUS['AVAILABLE']]);
        ['user' => $user, 'access_token' => $access_token] = $this->loginUser();

        $response = $this->json('POST', 'api/books/' . $book->id . '/checkin', [], ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
                'message' => 'The book is not checked-out',
            ]);
    }
}
