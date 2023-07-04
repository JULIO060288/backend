<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    function can_get_all_books()
    {
        $books = Book::factory(4)->create();

        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title' => $books[0]->title,
        ])->assertJsonFragment([
            'title' => $books[1]->title,
        ]);

    }

    /** @test */
    function can_get_one_book()
    {
        $book = Book::factory()->create();

        $response = $this->getJson(route('books.show', $book));

        $response->assertJsonFragment([
            'title' => $book->title,
        ]);

    }

    /** @test */
    function can_create_books()
    {
        $this->postJson(route('books.store'), [])->assertJsonValidationErrorFor('title');

        $response = $this->postJson(route('books.store', [
            'title' => "Mi Nuevo Libro"
        ]))->assertJsonFragment([
            'title' => "Mi Nuevo Libro",
        ]);

        $response = $this->assertDatabaseHas('books', [
            'title' => "Mi Nuevo Libro",
        ]);

    }

    /** @test */
    function can_update_books()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])->assertJsonValidationErrorFor('title');

        $response = $this->patchJson(route('books.update', $book), [
            'title' => 'Editado book',
        ])->assertJsonFragment([
            'title' => 'Editado book',
        ]);

        $response = $this->assertDatabaseHas('books', [
            'title' => 'Editado book',
        ]);
    }

    /** @test */
    function can_delete_books()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }

}
