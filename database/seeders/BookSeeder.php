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
        $valid_isbn = [
            '0005534186',
            '0978110196',
            '0978108248',
            '0978194527',
            '0978194004',
            '0978194985',
            '0978171349',
            '0978039912',
            '0978031644',
            '0978168968',
            '0978179633',
            '0978006232',
            '0978195248',
            '0978125029',
            '0978078691',
            '0978152476',
            '0978153871',
            '0978125010',
            '0593139135',
            '0441013597',
        ];

        $books = [];

        foreach ($valid_isbn as $isbn) {
            $books[] = [
                'title' => $this->faker->title,
                'isbn' => $isbn,
                'published_at' => $this->faker->date(),
                'status' => $this->faker->randomElement(array_keys(Book::STATUS)),
            ];
        }

        (new Book)->insert($books);
    }
}
