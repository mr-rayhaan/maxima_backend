<?php

namespace App\Http\Controllers;

use App\Models\Books;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Books  $books
     * @return \Illuminate\Http\Response
     */
    public function show(Books $books)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Books  $books
     * @return \Illuminate\Http\Response
     */
    public function edit(Books $books)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Books  $books
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Books $books)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Books  $books
     * @return \Illuminate\Http\Response
     */
    public function destroy(Books $books)
    {
        //
    }
    public function createBook(Request $request)
    {
        //validate user input
        $validator = Validator::make($request->all(), [
            'title' =>      'required|String|max:50',
            'description' => 'required|String|max:65535',
            'image' =>      'required|mimes:jpeg,bmp,png,gif,svg',
            'book' =>       'required|mimes:pdf'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        $title = $request->title;
        $description = $request->description;
        //create book without image without pdf
        $data = [];
        $data["title"] = $title;
        $data["description"] = $description;
        $data["img_path"] = '';
        $data["pdf_path"] = '';
        $newBook = Books::create($data);

        $pdfPath = $request->file('book')->store("books/" . $newBook->id . "/pdfs", ['disk' => "public"]);

        $imagePath = $request->file('image')->store("books/" . $newBook->id . "/images", ["disk" => "public"]);
        //add image and pdf to book
        $newBook->img_path = $imagePath;
        $newBook->pdf_path = $pdfPath;
        $newBook->save();

        return response()->json([
            'success' => true,
            'message' => 'Book has been created successfully',
            'book' => $newBook
        ]);
    }

    public function  updateBook(Request $request)
    {
        //validate user input
        $validator = Validator::make($request->all(), [
            'id' =>         'required|Integer',
            'title' =>      'nullable|String|max:50',
            'description' => 'nullable|String|max:65535',
            'image' =>      'nullable|mimes:jpeg,bmp,png,gif,svg',
            'book' =>       'nullable|mimes:pdf'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 400);
        }
        //get existing book from id
        $existingBook = Books::where('id', $request->id)->first();

        if (!(array)($existingBook)) {
            return response()->json([
                'success' => false,
                'message' => 'Book with id = ' . $request->id . ' does not exist.',
            ], 404);
        }

        if (isset($request->title)) {
            $existingBook->title = $request->title;
        }
        if (isset($request->description)) {
            $existingBook->description = $request->description;
        }
        if (isset($request->book)) {
            $existingBook->pdf_path = $request->file('book')->store("books/" . $existingBook->id . "/pdfs", ['disk' => "public"]);
        }
        if (isset($request->image)) {
            $existingBook->img_path = $request->file('image')->store("books/" . $existingBook->id . "/images", ['disk' => "public"]);
        }
        $existingBook->updated_at = now();
        //update book
        $existingBook->save();

        return response()->json([
            'success' => true,
            'message' => 'Book has been updated successfully',
            'book' => $existingBook
        ]);
    }

    public function deleteBook(Request $request)
    {
        //validate user input
        $validator = Validator::make($request->all(), [
            'id' =>      'required|Integer'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 400);
        }
        //get existing book from id
        $existingBook = Books::where('id', $request->id)->first();
        if (!(array)($existingBook)) {
            return response()->json([
                'success' => false,
                'message' => 'Book with id = ' . $request->id . ' does not exist.',
            ], 404);
        }

        $result = Books::where('id', $request->id)->delete();
        if ($result) {
            if (Storage::disk('public')->exists('books/' . $existingBook->id)) {
                Storage::disk('public')->deleteDirectory('books/' . $existingBook->id);
            }

            return response()->json([
                'success' => true,
                'message' => 'Book has been deleted successfully.'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong. Try again.'
        ]);
    }

    public function getAllBooks(Request $request)
    {
       return $books = Books::all();
    }
}
