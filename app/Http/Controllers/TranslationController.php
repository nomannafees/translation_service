<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function index()
    {
        try {
            $translations = Translation::with(['tags:id,name'])
            ->select('id', 'key', 'content')
            ->paginate(10);
            if ($translations->isEmpty()) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'No translations found',
                    'data' => []
                ], 200);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Translations retrieved successfully',
                'data' => $translations
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving translations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'locale' => 'required|string',
                'key' => 'required|string|unique:translations,key',
                'content' => 'required|string',
                'tags' => 'array'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $translation = Translation::create($validated);
            if ($request->has('tags')) {
                $translation->tags()->sync($request->tags);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Translation created successfully',
                'data' => $translation
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the translation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function show($id)
    {
        $translation = Translation::with('tags')->find($id);
        if ($translation) {
            return response()->json([
                'status' => 'success',
                'message' => 'Translation found',
                'data' => $translation
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Translation data not found for the specified ID'
            ], 404);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'locale' => 'string',
                'key' => 'string|unique:translations,key,' . $id,
                'content' => 'string',
                'tags' => 'array'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $translation = Translation::findOrFail($id);
            $translation->update($validated);
            if ($request->has('tags')) {
                $translation->tags()->sync($request->tags);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Translation updated successfully',
                'data' => $translation
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Translation not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the translation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $translation = Translation::findOrFail($id);
            $translation->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Translation deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle case where the translation is not found
            return response()->json([
                'status' => 'error',
                'message' => 'Translation not found for the specified ID'
            ], 404);
        } catch (\Exception $e) {
            // Catch any other unexpected errors
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the translation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function search(Request $request)
    {

        try {
            $query = Translation::query();
            if ($request->has('tags')) {
                $query->whereHas('tags', function ($q) use ($request) {
                    $q->whereIn('name', $request->tags);
                });
            }
            if ($request->has('key')) {
                $query->where('key', 'like', '%' . $request->key . '%');
            }
            if ($request->has('search_content')) {
                $query->where('content', 'like', '%' . $request->search_content . '%');
            }
            $translations = $query->with('tags')->paginate(10);
            if ($translations->isEmpty()) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'No translations found matching the given criteria',
                    'data' => []
                ], 200);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Translations found',
                'data' => $translations
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while searching for translations',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function export()
    {
        try {
            return response()->stream(function () {
                // Open output stream for CSV/JSON
                $handle = fopen('php://output', 'w');
                Translation::select('id', 'key', 'content')
                ->chunk(500, function ($chunk) use ($handle) {
                    foreach ($chunk as $translation) {
                        echo json_encode($translation->only(['id', 'key', 'content'])) . "\n";
                    }
                });
                fclose($handle);
            }, 200, [
                'Content-Type' => 'application/json',
                'Cache-Control' => 'no-cache',
                'Content-Disposition' => 'attachment; filename="translations.json"',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while exporting translations',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
