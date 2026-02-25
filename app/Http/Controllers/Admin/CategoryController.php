<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Http\Requests\Admin\StoreCategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function index()
    {
        $categories = $this->categoryRepo->getAll();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->categoryRepo->create($request->validated());
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(string $id)
    {
        $category = $this->categoryRepo->findById($id);
        if (!$category) return back()->with('error', 'Category not found.');
        
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        // Simple validation here or use a FormRequest
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,'.$id,
            'icon_class' => 'nullable|string|max:100',
            'is_active' => 'sometimes|boolean', // sometimes because checkbox sends nothing if unchecked
        ]);
        
        // Handle checkbox unchecked state manually if needed, or rely on 'sometimes' logic
        if (!$request->has('is_active')) {
            $data['is_active'] = 0;
        }

        $this->categoryRepo->update($id, $data);
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(string $id)
    {
        $this->categoryRepo->delete($id);
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}