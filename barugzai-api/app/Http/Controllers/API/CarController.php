<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Car;

class CarController extends Controller
{
    public function getAllCars(Request $request)
    {
        // Check if any filter is applied
        $filtersApplied = $request->hasAny([
            // 'manufacturer_id',
            // 'car_model_id',
            'min_price',
            'max_price',
            'min_mileage',
            'max_mileage',
            'start_year',
            'end_year',
            'search',
            'sort_order',
            'sort_by'
        ]);

        try {
            // Initialize query builder
            $query = Car::with([
                'manufacturer:id,title',
                'carModel:id,name',
                'images:id,url,imageable_id,image_index',
                'seller'
            ]);

            // Apply filters
            // if ($request->has('manufacturer_id') && $request->manufacturer_id) {
            //     $query->where('manufacturer_id', $request->manufacturer_id);
            // }

            // if ($request->has('car_model_id') && $request->car_model_id) {
            //     $query->where('car_model_id', $request->car_model_id);
            // }

             if ($request->has('manufacturer_id') && $request->manufacturer_id) {
                            // Support multiple IDs (comma-separated or array)
                            $mfIds = is_array($request->manufacturer_id)
                                ? $request->manufacturer_id
                                : explode(',', $request->manufacturer_id);

                $query->whereIn('manufacturer_id', $mfIds);
            }

            if ($request->has('car_model_id') && $request->car_model_id) {
                // Support multiple IDs (comma-separated or array)
                $modelIds = is_array($request->car_model_id)
                    ? $request->car_model_id
                    : explode(',', $request->car_model_id);

                $query->whereIn('car_model_id', $modelIds);
            }


            // Handle independent price filters
            if ($request->has('min_price') && $request->min_price !== null) {
                $query->where('price', '>=', $request->min_price);
            }

            if ($request->has('max_price') && $request->max_price !== null) {
                $query->where('price', '<=', $request->max_price);
            }

            // Handle independent mileage filters
            if ($request->has('min_mileage') && $request->min_mileage !== null) {
                $query->where('mileage', '>=', $request->min_mileage);
            }

            if ($request->has('max_mileage') && $request->max_mileage !== null) {
                $query->where('mileage', '<=', $request->max_mileage);
            }

            // Handle independent year filters
            if ($request->has('start_year') && $request->start_year !== null) {
                $query->where('year', '>=', $request->start_year);
            }

            if ($request->has('end_year') && $request->end_year !== null) {
                $query->where('year', '<=', $request->end_year);
            }


            if ($request->has('search') && $request->search) {
                $searchTerm = $request->search;

                $query->where(function ($q) use ($searchTerm) {
                    // Search in related manufacturer title
                    $q->whereHas('manufacturer', function ($manufacturerQuery) use ($searchTerm) {
                        $manufacturerQuery->where('title', 'LIKE', '%' . $searchTerm . '%');
                    })
                        // Search in related car model name
                        ->orWhereHas('carModel', function ($modelQuery) use ($searchTerm) {
                            $modelQuery->where('name', 'LIKE', '%' . $searchTerm . '%');
                        });
                });
            }


            // Sorting Logic
            if ($request->has('sort_by') && $request->has('sort_order')) {
                $sortBy = $request->sort_by;
                $sortOrder = $request->sort_order === 'desc' ? 'desc' : 'asc'; // Default to ascending if not 'desc'
                $query->orderBy($sortBy, $sortOrder);
            } else {
                // Default sorting by creation date
                $query->orderBy('created_at', 'desc');
            }

            // Move sold cars to the bottom
            $query->orderByRaw("sold DESC, created_at DESC");

            if ($filtersApplied) {
                $query->where('sold', 0); // Or 0 if DB uses integer
            }


            // Paginate results
            $cars = $query->paginate(9); // Change '10' to your preferred items per page

            return response()->json(['data' => $cars], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch cars', 'details' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'car_model_id' => 'required|exists:car_models,id',
            'seller_id' => 'required|exists:sellers,id', // Validate seller_id
            'year' => 'required|integer',
            'mileage' => 'required|integer',
            'engine_size' => 'required|string',
            'regional_spec' => 'required|string',
            'warranty' => 'nullable|string',
            'service_contact' => 'nullable|string',
            'fuel_type' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'instagram_link' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif', // Validate images
            'sold' => 'nullable|boolean',
            'views_count' => 'nullable|integer',
            'vehicle_type' => 'nullable|string|in:SUV,Sedan,Hatchback,Coupe,Convertible,Wagon,Van,Minivan,Truck,Pickup,Bus,Motorcycle,Electric,Hybrid,Diesel,Petrol,Luxury,Commercial,Off-Road',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'details' => $validator->errors()], 422);
        }

        try {
            // Create the car with validated data
            $car = Car::create($validator->validated());

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('cars', 'public'); // Store image in 'cars' folder
                    $car->images()->create([
                        'url' => $path,
                        'image_index' => $index + 1, // Assign order starting from 1
                    ]);
                }
            }

            // Load relationships for response
            $car->load(['images', 'seller', 'manufacturer', 'carModel']);

            return response()->json(['data' => $car, 'message' => 'Car created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create car', 'details' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'manufacturer_id' => 'nullable|exists:manufacturers,id',
            'car_model_id' => 'nullable|exists:car_models,id',
            'seller_id' => 'nullable|exists:sellers,id', // Validate seller_id
            'year' => 'nullable|integer',
            'mileage' => 'nullable|integer',
            'engine_size' => 'nullable|string',
            'regional_spec' => 'nullable|string',
            'warranty' => 'nullable|string',
            'service_contact' => 'nullable|string',
            'fuel_type' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'instagram_link' => 'nullable|string',
            'sold' => 'nullable|boolean',
            'views_count' => 'nullable|integer',
            'vehicle_type' => 'nullable|string|in:SUV,Sedan,Hatchback,Coupe,Convertible,Wagon,Van,Minivan,Truck,Pickup,Bus,Motorcycle,Electric,Hybrid,Diesel,Petrol,Luxury,Commercial,Off-Road',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'details' => $validator->errors()], 422);
        }

        try {
            // Find the car
            $car = Car::findOrFail($id);

            // Update fields dynamically
            $car->update($validator->validated());

            return response()->json(['data' => $car, 'message' => 'Car updated successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Car not found', 'details' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update car', 'details' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $car = Car::with('manufacturer', 'carModel', 'images', 'seller')->findOrFail($id);
            return response()->json(['data' => $car], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Car not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch car', 'details' => $e->getMessage()], 500);
        }
    }



    public function destroy($id)
    {
        try {
            $car = Car::findOrFail($id);
            $car->delete();
            return response()->json(['message' => 'Car deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Car not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete car', 'details' => $e->getMessage()], 500);
        }
    }


    public function updateImages(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'exists:images,id',
            'image_indexes' => 'nullable|array',
            'image_indexes.*' => 'integer|min:1',
            'update_indexes' => 'nullable|array', // Array for updating indexes
            'update_indexes.*.id' => 'required|exists:images,id', // Existing image ID
            'update_indexes.*.index' => 'required|integer|min:1', // New index for existing image
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'details' => $validator->errors()], 422);
        }

        try {
            $car = Car::with('images')->findOrFail($id);

            // Remove specified images
            if ($request->filled('remove_images')) {
                foreach ($request->input('remove_images') as $imageId) {
                    $image = $car->images()->find($imageId);
                    if ($image) {
                        \Storage::disk('public')->delete($image->url); // Delete file
                        $image->delete(); // Delete record
                    }
                }
            }

            // Add new images with index
            if ($request->hasFile('new_images')) {
                foreach ($request->file('new_images') as $index => $image) {
                    $path = $image->store('cars', 'public');
                    $car->images()->create([
                        'url' => $path,
                        'image_index' => $request->input('image_indexes')[$index] ?? null, // Assign index if provided
                    ]);
                }
            }

            // Update indexes for existing images
            if ($request->filled('update_indexes')) {
                foreach ($request->input('update_indexes') as $update) {
                    $image = $car->images()->find($update['id']);
                    if ($image) {
                        $image->update(['image_index' => $update['index']]);
                    }
                }
            }

            return response()->json([
                'data' => $car->load('images'),
                'message' => 'Images updated successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Car not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update images', 'details' => $e->getMessage()], 500);
        }
    }

    public function getLatestCars(Request $request)
    {
        try {
            $query = Car::with(['images', 'manufacturer', 'carModel', 'seller']);
    
            if ($request->filled('manufacturer_id')) {
                $mfIds = is_array($request->manufacturer_id)
                    ? $request->manufacturer_id
                    : explode(',', $request->manufacturer_id);
    
                $query->whereIn('manufacturer_id', $mfIds);
            }
    
            if ($request->filled('car_model_id')) {
                $modelIds = is_array($request->car_model_id)
                    ? $request->car_model_id
                    : explode(',', $request->car_model_id);
    
                $query->whereIn('car_model_id', $modelIds);
            }
    
            $cars = $query
                ->latest()          // cleaner than orderBy
                ->limit(3)
                ->get();
    
            if ($cars->isEmpty()) {
                return response()->json([
                    'data' => [],
                    'message' => 'No cars found'
                ], 200);
            }
    
            return response()->json([
                'data' => $cars,
                'message' => 'Latest cars fetched successfully'
            ], 200);
    
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to fetch latest cars',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function latestCars()
    {
        try {
            // Fetch the latest three cars with specific fields
            $cars = Car::with([
                'images:id,url,imageable_id',
                'seller', // Load only necessary fields for images
                'manufacturer:id,title', // Ensure manufacturer data is loaded
                'carModel:id,name' // Ensure car model data is loaded
            ])
                ->orderBy('created_at', 'desc') // Order by the most recent
                ->take(3) // Get the last 3 cars
                ->get(['id', 'manufacturer_id', 'car_model_id', 'year', 'price', 'description', 'mileage', 'regional_spec', 'fuel_type']); // Include manufacturer_id and car_model_id

            // If no cars found, return a meaningful message
            if ($cars->isEmpty()) {
                return response()->json(['data' => [], 'message' => 'No cars found'], 200);
            }

            return response()->json(['data' => $cars, 'message' => 'Latest cars fetched successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch latest cars', 'details' => $e->getMessage()], 500);
        }
    }

    public function getRelatedListings($car_id)
    {
        try {
            // Find the car by its ID
            $car = Car::findOrFail($car_id);

            // Fetch related cars based on the same manufacturer
            $relatedListings = Car::with([
                'manufacturer:id,title', // Include manufacturer data
                'carModel:id,name', // Include car model data
                'images:id,url,imageable_id' // Include image data
            ])
                ->where('manufacturer_id', $car->manufacturer_id)
                ->where('id', '!=', $car->id) // Exclude the current car
                ->orderBy('created_at', 'desc') // Order by latest
                ->take(4) // Limit to 4 listings
                ->get();

            // Check if no related listings are found
            if ($relatedListings->isEmpty()) {
                return response()->json([
                    "error" => "No related listings found",
                    "details" => "No related listings found for the given car's manufacturer"
                ], 404);
            }

            return response()->json(['data' => $relatedListings, 'message' => 'Related listings fetched successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Car not found',
                'details' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch related listings',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    public function optimizedImage(Request $request, $path)
    {
        // Local storage path
        $localPath = storage_path("app/public/{$path}");

        if (file_exists($localPath)) {
            $fullPath = $localPath;
            $isRemote = false;
        } else {
            // Fallback to external URL
            $fullPath = "https://10xideas.com/barugzai-api/storage/{$path}";
            $isRemote = true;
        }

        $maxWidth  = (int) $request->query('max_width', 0);
        $maxHeight = (int) $request->query('max_height', 0);
        $quality   = min(100, max(0, (int) $request->query('quality', 50)));

        // Get original size and type (handle remote separately)
        if ($isRemote) {
            $imageInfo = getimagesize($fullPath);
        } else {
            $imageInfo = getimagesize($fullPath);
        }

        if (!$imageInfo) {
            abort(404, "Image not found");
        }

        [$origWidth, $origHeight, $type] = $imageInfo;

        // Maintain aspect ratio
        $newWidth  = $origWidth;
        $newHeight = $origHeight;

        if ($maxWidth > 0 && $newWidth > $maxWidth) {
            $ratio     = $maxWidth / $newWidth;
            $newWidth  = $maxWidth;
            $newHeight = (int) ($newHeight * $ratio);
        }

        if ($maxHeight > 0 && $newHeight > $maxHeight) {
            $ratio     = $maxHeight / $newHeight;
            $newHeight = $maxHeight;
            $newWidth  = (int) ($newWidth * $ratio);
        }

        // Create image resource
        if ($isRemote) {
            $imageData = file_get_contents($fullPath);
            $srcImage = imagecreatefromstring($imageData);
        } else {
            switch ($type) {
                case IMAGETYPE_JPEG:
                    $srcImage = imagecreatefromjpeg($fullPath);
                    break;
                case IMAGETYPE_PNG:
                    $srcImage = imagecreatefrompng($fullPath);
                    break;
                case IMAGETYPE_WEBP:
                    $srcImage = imagecreatefromwebp($fullPath);
                    break;
                default:
                    abort(415, "Unsupported image type");
            }
        }

        // Resize
        $dstImage = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency
        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_WEBP) {
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
            $transparent = imagecolorallocatealpha($dstImage, 255, 255, 255, 127);
            imagefilledrectangle($dstImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        // Output
        ob_start();
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($dstImage, null, $quality);
                $mime = 'image/jpeg';
                break;
            case IMAGETYPE_PNG:
                $pngQuality = (int) round(9 - ($quality / 100 * 9));
                imagepng($dstImage, null, $pngQuality);
                $mime = 'image/png';
                break;
            case IMAGETYPE_WEBP:
                imagewebp($dstImage, null, $quality);
                $mime = 'image/webp';
                break;
        }
        $output = ob_get_clean();

        imagedestroy($srcImage);
        imagedestroy($dstImage);

        return response($output)
            ->header('Content-Type', $mime)
            ->header('Cache-Control', 'public, max-age=31536000');
    }
}
