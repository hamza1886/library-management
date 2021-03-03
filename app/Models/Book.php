<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class Book
 * @package App\Models
 *
 * @mixin Builder
 *
 */
class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'isbn',
        'published_at',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'date',
    ];

    /**
     * The possible status a book can have.
     */
    const STATUS = [
        'AVAILABLE' => 'AVAILABLE',
        'CHECKED_OUT' => 'CHECKED_OUT',
    ];

    /**
     * The users that belong to the book.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_action_logs')->withPivot(['id', 'action', 'created_at']);
    }

    /**
     * Checkout the specific resource from the storage
     * @param Book $book
     * @param User $user
     */
    public static function checkout(Book $book, User $user)
    {
        DB::transaction(function () use ($book, $user) {
            $book->users()->attach($user->id, [
                'action' => config('enums.book_action.CHECKOUT')
            ]);
            $book->status = Book::STATUS['CHECKED_OUT'];
            $book->update();
        });
    }

    /**
     * Checkin the specific resource into the storage
     * @param Book $book
     * @param User $user
     */
    public static function checkin(Book $book, User $user)
    {
        DB::transaction(function () use ($book, $user) {
            $book->users()->attach($user->id, [
                'action' => config('enums.book_action.CHECKIN')
            ]);
            $book->status = Book::STATUS['AVAILABLE'];
            $book->update();
        });
    }
}
