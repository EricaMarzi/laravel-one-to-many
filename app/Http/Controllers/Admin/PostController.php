<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $query = Post::orderByDesc('updated_at')->orderByDesc('created_at');
        if ($filter) {
            $value = $filter === 'published';
            $query->whereIsPublished($value);
        }

        $posts = $query->paginate(10)->withQueryString();
        return view('admin\posts\index', compact('posts', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin\posts\create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:5|max:50|unique:posts',
            'content' => 'required|string',
            'image' => 'nullable|image',
            'is_published' => 'nullable|boolean',
            'category_id' => 'nullable|exists:categories,id'
        ], [
            'title.required' => 'Il titolo è obbligatorio',
            'title.min' => 'Il titolo deve essere almeno di :min caratteri',
            'title.max' => 'Il titolo deve essere massimo di :max caratteri',
            'title.unique' => 'Titolo già esistente',
            'image.url' => 'L\'indirizzo non è valido',
            'content.required' => 'Il contenuto è obbligatorio',
            'category_id.exists' => 'Categoria non valida'
        ]);

        $data = $request->all();
        $post = new Post();
        $post->fill($data);
        $post->slug = Str::slug($post->title);
        $post->is_published = array_key_exists('is_published', $data);

        if (Arr::exists($data, 'image')) {
            $img_url = Storage::putFile('post_images', $data['image']);
            $post->image = $img_url;
        }

        $post->save();

        return to_route('admin.post.show', $post)->with('message', 'Post creato con successo')->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('admin\posts\show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('admin\posts\edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:50'],
            'content' => 'required|string',
            'image' => 'nullable|image',
            'is_published' => 'nullable|boolean',
            'category_id' => 'nullable|exists:categories,id'
        ], [
            'title.required' => 'Il titolo è obbligatorio',
            'title.min' => 'Il titolo deve essere almeno di :min caratteri',
            'title.max' => 'Il titolo deve essere massimo di :max caratteri',
            'title.unique' => 'Titolo già esistente',
            'image.url' => 'L\'indirizzo non è valido',
            'content.required' => 'Il contenuto è obbligatorio',
            'category_id.exists' => 'Categoria non valida'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($data['title']);
        $data['is_published'] = Arr::exists($data, 'is_published');
        $post->is_published = array_key_exists('is_published', $data);


        if ($request->hasFile('image')) {
            // Carica la nuova immagine e aggiorna l'URL nel database
            $img_url = Storage::putFile('post_images', $request->file('image'));
            $data['image'] = $img_url;
        }

        $post->update($data);

        return to_route('admin.post.show', $post)->with('message', 'Post modificato con successo')->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->image) Storage::delete($post->image);
        $post->delete();
        return to_route('admin.post.index')->with('type', 'danger')->with('message', 'Post eliminato');
    }
}
