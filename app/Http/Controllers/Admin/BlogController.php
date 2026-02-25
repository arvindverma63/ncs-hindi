<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BlogController extends Controller
{
    protected $blogRepo;
    protected $categoryRepo;

    public function __construct(BlogRepositoryInterface $blogRepo, CategoryRepositoryInterface $categoryRepo)
    {
        $this->blogRepo = $blogRepo;
        $this->categoryRepo = $categoryRepo;
    }

    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
            'status' => 'nullable|in:0,1',
        ]);

        $blogs = $this->blogRepo->getAll();
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        $categories = $this->categoryRepo->getAll();
        return view('admin.blogs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_published' => 'required|boolean',
        ]);

        $data = $request->except('featured_image');
        $data['user_id'] = Auth::id();
        $data['slug'] = Str::slug($request->title) . '-' . time();

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $filename = time() . '.webp'; // Converting to webp is better for performance
            $path = 'uploads/blogs/' . $filename;

            // Initialize Manager with GD Driver
            $manager = new ImageManager(new Driver());
            $img = $manager->read($image);

            // Resize and optimize
            $img->scale(width: 1200); 
            
            // Save encoded image string to storage
            Storage::disk('public')->put($path, (string) $img->toWebp(80));
            $data['featured_image'] = $path;
        }

        $this->blogRepo->create($data);
        return redirect()->route('admin.blogs.index')->with('success', 'Blog post created.');
    }

    public function edit(string $id)
    {
        $blog = $this->blogRepo->findById($id);
        if (!$blog) {
            return redirect()->route('admin.blogs.index')->with('error', 'Blog post not found.');
        }

        $categories = $this->categoryRepo->getAll();
        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_published' => 'required|boolean',
        ]);

        $blog = $this->blogRepo->findById($id);
        $data = $request->except('featured_image');

        if ($request->title !== $blog->title) {
            $data['slug'] = Str::slug($request->title) . '-' . time();
        }

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image && Storage::disk('public')->exists($blog->featured_image)) {
                Storage::disk('public')->delete($blog->featured_image);
            }

            $image = $request->file('featured_image');
            $filename = time() . '.webp';
            $path = 'uploads/blogs/' . $filename;

            $manager = new ImageManager(new Driver());
            $img = $manager->read($image);
            $img->scale(width: 1200);

            Storage::disk('public')->put($path, (string) $img->toWebp(80));
            $data['featured_image'] = $path;
        }

        $this->blogRepo->update($id, $data);
        return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated.');
    }
    public function destroy(string $id)
    {
        $blog = $this->blogRepo->findById($id);
        if ($blog) {
            if ($blog->featured_image && Storage::disk('public')->exists($blog->featured_image)) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $this->blogRepo->delete($id);
            return back()->with('success', 'Blog post deleted successfully.');
        }
        return back()->with('error', 'Blog post not found.');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:blogs,id',
            'status' => 'required|boolean',
        ]);

        $updated = $this->blogRepo->updateStatus($request->id, $request->status);

        return response()->json([
            'success' => $updated,
            'message' => $updated ? 'Publish status updated.' : 'Failed to update status.'
        ]);
    }
}