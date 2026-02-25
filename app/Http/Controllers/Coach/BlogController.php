<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\CoachBlogRepositoryInterface;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    protected $blogRepo;

    public function __construct(CoachBlogRepositoryInterface $blogRepo)
    {
        $this->blogRepo = $blogRepo;
    }

    public function index()
    {
        $blogs = $this->blogRepo->getCoachBlogs(Auth::id());
        return view('coach.blogs.index', compact('blogs'));
    }

    public function create()
    {
        $categories = Category::where('is_active', 1)->get();
        return view('coach.blogs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->uploadImage($request->file('featured_image'));
        }

        $data['user_id'] = Auth::id();
        $this->blogRepo->store($data);

        return redirect()->route('coach.blogs.index')->with('success', 'Article submitted for review.');
    }

    public function edit(string $id)
    {
        $blog = $this->blogRepo->findCoachBlog(Auth::id(), $id);
        $categories = Category::where('is_active', 1)->get();

        return view('coach.blogs.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('featured_image')) {
            // Optional: Delete old image logic
            $blog = $this->blogRepo->findCoachBlog(Auth::id(), $id);
            if ($blog->featured_image && File::exists(public_path($blog->featured_image))) {
                File::delete(public_path($blog->featured_image));
            }
            
            $data['featured_image'] = $this->uploadImage($request->file('featured_image'));
        }

        $this->blogRepo->update($id, $data, Auth::id());

        return redirect()->route('coach.blogs.index')->with('success', 'Article updated.');
    }

    public function destroy(string $id)
    {
        $blog = $this->blogRepo->findCoachBlog(Auth::id(), $id);
        
        if ($blog->featured_image && File::exists(public_path($blog->featured_image))) {
            File::delete(public_path($blog->featured_image));
        }

        $this->blogRepo->delete($id, Auth::id());
        return redirect()->route('coach.blogs.index')->with('success', 'Article deleted.');
    }

    /**
     * Helper for Intervention Image Upload
     */
    private function uploadImage($file)
    {
        $filename = time() . '_' . uniqid() . '.webp';
        $directory = 'uploads/blogs';
        $path = public_path($directory . '/' . $filename);

        if (!File::exists(public_path($directory))) {
            File::makeDirectory(public_path($directory), 0755, true);
        }

        Image::read($file)
            ->cover(800, 450) // Standard 16:9 blog aspect ratio
            ->toWebp(80)
            ->save($path);

        return $directory . '/' . $filename;
    }
}