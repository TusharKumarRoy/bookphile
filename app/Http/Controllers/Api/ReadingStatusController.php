<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\ReadingStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReadingStatusController extends Controller
{
    /**
     * Update reading status for a book.
     */
    public function update(Request $request, Book $book): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:want_to_read,currently_reading,finished_reading',
            'current_page' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $userId = Auth::id();
        $status = $request->input('status');
        $currentPage = $request->input('current_page');
        $notes = $request->input('notes');

        // Determine dates based on status
        $startedReading = null;
        $finishedReading = null;

        $existingStatus = ReadingStatus::where('user_id', $userId)
                                      ->where('book_id', $book->id)
                                      ->first();

        if ($status === 'currently_reading') {
            $startedReading = $existingStatus ? $existingStatus->started_reading : now()->toDateString();
        } elseif ($status === 'finished_reading') {
            $startedReading = $existingStatus ? $existingStatus->started_reading : now()->toDateString();
            $finishedReading = now()->toDateString();
        }

        // Update or create reading status
        $readingStatus = ReadingStatus::updateOrCreate(
            [
                'user_id' => $userId,
                'book_id' => $book->id,
            ],
            [
                'status' => $status,
                'started_reading' => $startedReading,
                'finished_reading' => $finishedReading,
                'current_page' => $currentPage,
                'notes' => $notes,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Reading status updated successfully',
            'data' => [
                'status' => $readingStatus->status,
                'started_reading' => $readingStatus->started_reading,
                'finished_reading' => $readingStatus->finished_reading,
                'current_page' => $readingStatus->current_page,
                'notes' => $readingStatus->notes,
            ],
        ]);
    }

    /**
     * Get current reading status for a book.
     */
    public function show(Book $book): JsonResponse
    {
        $userId = Auth::id();
        $readingStatus = ReadingStatus::where('user_id', $userId)
                                     ->where('book_id', $book->id)
                                     ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'reading_status' => $readingStatus ? [
                    'status' => $readingStatus->status,
                    'started_reading' => $readingStatus->started_reading,
                    'finished_reading' => $readingStatus->finished_reading,
                    'current_page' => $readingStatus->current_page,
                    'notes' => $readingStatus->notes,
                    'is_favorite' => $readingStatus->is_favorite,
                ] : null,
            ],
        ]);
    }

    /**
     * Remove reading status for a book.
     */
    public function destroy(Book $book): JsonResponse
    {
        $userId = Auth::id();
        $readingStatus = ReadingStatus::where('user_id', $userId)
                                     ->where('book_id', $book->id)
                                     ->first();

        if (!$readingStatus) {
            return response()->json([
                'success' => false,
                'message' => 'Reading status not found',
            ], 404);
        }

        $readingStatus->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reading status removed successfully',
        ]);
    }

    /**
     * Toggle favorite status for a book.
     */
    public function toggleFavorite(Book $book): JsonResponse
    {
        $userId = Auth::id();
        $readingStatus = ReadingStatus::where('user_id', $userId)
                                     ->where('book_id', $book->id)
                                     ->first();

        if (!$readingStatus) {
            return response()->json([
                'success' => false,
                'message' => 'You need to add this book to your reading list first',
            ], 404);
        }

        $readingStatus->update([
            'is_favorite' => !$readingStatus->is_favorite,
        ]);

        return response()->json([
            'success' => true,
            'message' => $readingStatus->is_favorite ? 'Added to favorites' : 'Removed from favorites',
            'data' => [
                'is_favorite' => $readingStatus->is_favorite,
            ],
        ]);
    }
}
