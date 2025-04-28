<?php

namespace App\Http\Controllers;

use App\Jobs\DataJob;
use App\Models\Book;
use App\Services\DataService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{

    private DataService $service;

    public function __construct(DataService $service)
    {
        $this->service = $service;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        if (!Cache::get('data_loaded', false)) {
            try {
                $this->service->loadData();
            } catch (Exception $e) {
                return response()->json(['msg' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
            }
            dispatch(new DataJob($this->service));

            Cache::put('data_loaded', true);
        }

        $books = Book::with('authors')->paginate(15);

        return response()->json(['books' => $books]);
    }

    public function search(): JsonResponse
    {
        $books = Book::with('authors')
            ->when(request()->param, function ($query, $search) {
                $query->where('title', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%")
                    ->orWhereHas('authors', function ($query) use ($search) {
                        $query->where('name', 'LIKE', "%$search%");
                    });
            })
            ->paginate(15);

        return response()->json(['books' => $books]);
    }
}
