<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except([
            'index',
            'show',
            'checkout',
            'checkin'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $books = (new Book)->paginate(10);

        return response()->json([
            'data' => [
                'book' => $books,
            ],
            'message' => 'Success getting books',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateBookRequest $request
     * @return JsonResponse
     */
    public function store(CreateBookRequest $request)
    {
        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->isbn = $request->isbn;
        $book->published_at = $request->published_at;
        $book->save();

        return response()->json([
            'data' => [
                'book' => $book,
            ],
            'message' => 'Success adding book',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Book $book
     * @return JsonResponse
     */
    public function show(Book $book)
    {
        return response()->json([
            'data' => [
                'book' => $book,
            ],
            'message' => 'Success getting book',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBookRequest $request
     * @param Book $book
     * @return JsonResponse
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->title = $request->title;
        $book->author = $request->author;
        $book->isbn = $request->isbn;
        $book->published_at = $request->published_at;
        $book->update();

        return response()->json([
            'data' => [
                'book' => $book,
            ],
            'message' => 'Success updating book',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Book $book
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'data' => [],
            'message' => 'Success deleting book',
        ]);
    }

    /**
     * Checkout the specified resource from storage.
     *
     * @param Request $request
     * @param Book $book
     * @return JsonResponse
     */
    public function checkout(Request $request, Book $book)
    {
        if ($request->route('book')->status === Book::STATUS['CHECKED_OUT']) {
            return response()->json([
                'data' => [],
                'message' => 'The book is not available',
            ]);
        }

        // TODO: uncomment after login/register is available
        // $user = Auth::user();
        $user = User::find(1);

        Book::checkout($book, $user);

        return response()->json([
            'data' => [],
            'message' => 'Success checking-out book',
        ]);
    }

    /**
     * Checkin the specified resource from storage.
     *
     * @param Request $request
     * @param Book $book
     * @return JsonResponse
     */
    public function checkin(Request $request, Book $book)
    {
        if ($request->route('book')->status === Book::STATUS['AVAILABLE']) {
            return response()->json([
                'data' => [],
                'message' => 'The book is not checked-out',
            ]);
        }

        // TODO: uncomment after login/register is available
        // $user = Auth::user();
        $user = User::find(1);

        Book::checkin($book, $user);

        return response()->json([
            'data' => [],
            'message' => 'Success checking-in book',
        ]);
    }
}
