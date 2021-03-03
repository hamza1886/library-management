<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();

        DB::transaction(function () {
            $this->call(BookSeeder::class);

            $books = (new Book)->where('status', '=', Book::STATUS['CHECKED_OUT'])->get();

            foreach ($books as $book) {
                $user_id = (new User)->inRandomOrder()->first()->id;
                $book->users()->attach($user_id, [
                    'action' => config('enums.book_action.CHECKOUT')
                ]);
            }
        });
    }
}
