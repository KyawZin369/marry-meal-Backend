<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MealController extends Controller
{
    public function index()
    {
        $meals = Meal::all();

        return response()->json(['meals' => $meals], 200);
    }

    public function show($id)
    {
        $meal = Meal::find($id);

        if (!$meal) {
            return response()->json(['error' => 'Meal not found'], 404);
        }

        return response()->json(['meal' => $meal], 200);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'ingredients' => 'required',
            'allergy_information' => 'nullable',
            'nutritional_information' => 'nullable',
            'dietary_restrictions' => 'nullable',
            'price' => 'required|numeric',
            'is_frozen' => 'required|boolean',
            'delivery_status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image upload
        ]);

        // Handle image upload
        $path = 'uploads/meals';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move($path, $filename);
            $validatedData['image'] = $filename;
        }

        $meal = Meal::create($validatedData);

        return response()->json(['meal' => $meal], 201);
    }

    public function update(Request $request, $id)
    {
        $meal = Meal::find($id);

        if (!$meal) {
            return response()->json(['error' => 'Meal not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required',
            'ingredients' => 'required',
            'allergy_information' => 'nullable',
            'nutritional_information' => 'nullable',
            'dietary_restrictions' => 'nullable',
            'price' => 'required|numeric',
            'is_frozen' => 'required|boolean',
            'delivery_status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image upload
        ]);

        // Handle image upload
        $path = 'uploads/meals';
        if ($request->hasFile('image')) {
            // Delete old image
            if ($meal->image && File::exists($path . '/' . $meal->image)) {
                File::delete($path . '/' . $meal->image);
            }

            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move($path, $filename);
            $validatedData['image'] = $filename;
        }

        $meal->update($validatedData);

        return response()->json(['meal' => $meal], 200);
    }

    public function destroy($id)
    {
        $meal = Meal::find($id);

        if (!$meal) {
            return response()->json(['error' => 'Meal not found'], 404);
        }

        $meal->delete();

        return response()->json(['message' => 'Meal deleted successfully'], 200);
    }
}
