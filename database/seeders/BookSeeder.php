<?php

namespace Database\Seeders;

use App\Models\Book;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * The current Faker instance.
     *
     * @var Generator
     */
    protected $faker;

    /**
     * Create a new seeder instance.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    /**
     * Get a new Faker instance.
     *
     * @return Generator
     * @throws BindingResolutionException
     */
    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $books = [
            ['title' => 'Absalom, Absalom!', 'author' => 'William Faulkner', 'isbn' => '0005534186'],
            ['title' => 'A Time to Kill', 'author' => 'John Grisham', 'isbn' => '0978110196'],
            ['title' => 'The House of Mirth', 'author' => 'Edith Wharton', 'isbn' => '0978108248'],
            ['title' => 'East of Eden', 'author' => 'John Steinbeck', 'isbn' => '0978194527'],
            ['title' => 'The Sun also Rises', 'author' => 'Ernest Hemingway', 'isbn' => '0978194004'],
            ['title' => 'Vile Bodies', 'author' => 'Evelyn Waugh', 'isbn' => '0978194985'],
            ['title' => 'A Scanner Darkly', 'author' => 'Philip K. Dick', 'isbn' => '0978171349'],
            ['title' => 'Moab is my Washpot', 'author' => 'Stephen Fry', 'isbn' => '0978039912'],
            ['title' => 'Number the Stars', 'author' => 'Lois Lowry', 'isbn' => '0978031644'],
            ['title' => 'Noli me Tangere', 'author' => 'José Rizal', 'isbn' => '0978168968'],
            ['title' => 'Brave New World', 'author' => 'Aldous Huxley', 'isbn' => '0978179633'],
            ['title' => 'Rosemary and Rue', 'author' => 'Seanan McGuire', 'isbn' => '0978006232'],
            ['title' => 'Pale Fire', 'author' => 'Vladimir Nabakov', 'isbn' => '0978195248'],
            ['title' => 'Remembrance of Things', 'author' => 'Marcel Proust', 'isbn' => '0978125029'],
            ['title' => 'The Fault in Our Stars', 'author' => 'John Green', 'isbn' => '0978078691'],
            ['title' => 'Cold Comfort Farm', 'author' => 'Stella Gibbons', 'isbn' => '0978152476'],
            ['title' => 'In Cold Blood', 'author' => 'Truman Capote', 'isbn' => '0978153871'],
            ['title' => 'Behold, Here\'s Poison', 'author' => 'Georgette Heyer', 'isbn' => '0978125010'],
            ['title' => 'Band of Brothers', 'author' => 'Stephen E. Ambrose', 'isbn' => '0593139135'],
            ['title' => 'Mortal Engines', 'author' => 'Philip Reeve', 'isbn' => '0441013597'],
        ];

        foreach ($books as $index => $book) {
            $books[$index]['published_at'] = $this->faker->date();
            $books[$index]['status'] = $this->faker->randomElement(array_keys(Book::STATUS));
        }

        (new Book)->insert($books);
    }
}
