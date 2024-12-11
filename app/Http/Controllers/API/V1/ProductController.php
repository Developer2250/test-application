<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    private $baseUrl = 'https://dummyjson.com/products';

    public function getProducts(Request $request)
    {
        try {
            $limit = $request->input('limit', 20);
            $category = $request->input('category', 'all');

            if ($category === 'all') {
                $response = Http::get($this->baseUrl, [
                    'limit' => $limit,
                ]);
            } else {
                $response = Http::get($this->baseUrl, [
                    'limit' => $limit,
                    'category' => $category,
                ]);
            }

            if ($response->failed()) {
                \Log::error('Failed to fetch products from API.');
                return response()->json([
                    'products' => [],
                    'error' => 'Failed to fetch products from API.',
                ]);
            }

            $data = $response->json();
            $products = $data['products'] ?? [];

            if ($request->ajax()) {
                return response()->json([
                    'products' => $products,
                    'limit' => $limit,
                ]);
            }

            return view('test', compact('products', 'limit'));
        } catch (\Throwable $e) {
            \Log::error([$e->getMessage(), $e->getFile(), $e->getLine()]);
            return response()->json([
                'products' => [],
                'error' => 'Oops something went to wrong. Please try again. ',
            ]);
        }
    }

    public function categories()
    {
        try {
            $response = Http::get($this->baseUrl . '/categories');
            return response()->json($response->json());
        } catch (\Throwable $e) {
            \Log::error([$e->getMessage(), $e->getFile(), $e->getLine()]);
            return response()->json([
                'success' => false,
                'message' => 'Oops something went to wrong. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function filter()
    {
        try {
            $category = request('category');
            $response = Http::get($this->baseUrl . "/category/" . $category);
            return response()->json($response->json());
        } catch (\Throwable $e) {
            \Log::error([$e->getMessage(), $e->getFile(), $e->getLine()]);
            return response()->json([
                'success' => false,
                'message' => 'Oops something went to wrong. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function search()
    {
        try {
            $query = request('q');
            $limit = 200;
            $page = 1;
            $response = Http::get($this->baseUrl . "/search", [
                'q' => $query,
                'limit' => $limit,
                'page' => $page,
            ]);
            $data = $response->json();
            return response()->json($data);
        } catch (\Throwable $e) {
            \Log::error([$e->getMessage(), $e->getFile(), $e->getLine()]);
            return response()->json([
                'success' => false,
                'message' => 'Oops something went to wrong. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
