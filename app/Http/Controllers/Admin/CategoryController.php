<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    private $categoryRepository;
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    
    public function create()
    {
        return view('admin.category.create', ['parent_categories' => $this->categoryRepository->whereNull('parent_id')->lists('name', 'id')]);
    }
    
    public function store(CategoryRequest $request)
    {
        $category = $this->categoryRepository->create([
            'name' => $request->name,
            'parent_id' => $request->parent_id ?: null,
        ]);
        if ($category) {
            return redirect()->route('admin.categories.index')->with([
                'status' => 'success',
                'message' => trans('messages.admin.categories.add.success')
            ]);
        }
        
        return redirect()->back()->with([
            'status' => 'danger',
            'message' => trans('messages.admin.categories.add.failed')
        ]);
    }
    
    public function index()
    {
        $categories = $this->categoryRepository->paginate();
        
        return view('admin.category.index', compact('categories'));
    }
}
