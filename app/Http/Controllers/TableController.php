<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TableController extends Controller
{
    // List all tables
    public function index()
    {
        $tables = Table::all();

        return response()->json([
            'success' => true,
            'tables' => $tables,
        ]);
    }

    public function show(Table $table)
    {
        return response()->json([
            'success' => true,
            'table' => $table,
        ]);
    }

    // Create a new table
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:tables,title',
            'seats' => 'required|integer|min:1',
            'is_available' => 'nullable|integer|min:0|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $table = Table::create([
            'title' => $request->title,
            'seats' => $request->seats,
            'is_available' => $request->is_available,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Table created successfully.',
            'table' => $table,
        ], 201);
    }

    public function update(Request $request, Table $table)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:tables,title,' . $table->id,
            'seats' => 'required|integer|min:1',
            'is_available' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $table->update([
            'title' => $request->title,
            'seats' => $request->seats,
            'is_available' => $request->has('is_available') ? $request->is_available : $table->is_available,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Table updated successfully.',
            'table' => $table,
        ]);
    }


    // Delete a table
    public function destroy(Table $table)
    {
        $table->delete();

        return response()->json([
            'success' => true,
            'message' => 'Table deleted successfully.',
        ]);
    }
}


