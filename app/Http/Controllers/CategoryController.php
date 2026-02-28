<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Mappers\CategoryMapper;
use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryRepositoryInterface $repository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $categories = $this->repository->findAll($userId);
            $data = $categories->map(fn ($dto) => CategoryMapper::toArray($dto));

            return response()->json([
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'message' => 'Internal server error.',
            ], 500);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $category = $this->repository->findById($id, $userId);
            if (! $category) {
                return response()->json([
                    'message' => 'Category not found.',
                ], 404);
            }
            $data = CategoryMapper::toArray($category);

            return response()->json([
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'message' => 'Internal server error.',
            ], 500);
        }
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {

            $validated = $request->validated();
            $userId = $request->user()->id;
            $dto = CategoryMapper::fromRequest($validated, $userId);
            $category = $this->repository->create($dto);
            $data = CategoryMapper::toArray($category);

            return response()->json([
                'data' => $data,
            ], 201);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'message' => 'Internal server error.',
            ], 500);
        }
    }

    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $userId = $request->user()->id;
            $dto = CategoryMapper::fromRequest($validated, $userId);
            $category = $this->repository->update($id, $dto);
            if (! $category) {
                return response()->json([
                    'message' => 'Category not found.',
                ], 404);
            }
            $data = CategoryMapper::toArray($category);

            return response()->json([
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'message' => 'Internal server error.',
            ], 500);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $userId = $request->user()->id;

            $hasTransactions = Category::where('id', $id)
                ->where('user_id', $userId)
                ->withCount('transactions')
                ->first()?->transactions_count > 0;

            if ($hasTransactions) {
                return response()->json(['message' => 'Category has transactions linked.'], 409);
            }

            $deleted = $this->repository->delete($id, $userId);

            if (! $deleted) {
                return response()->json(['message' => 'Category not found.'], 404);
            }

            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            return response()->json(['message' => 'Internal server error.'], 500);
        }
    }
}
