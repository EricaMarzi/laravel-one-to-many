@extends('layouts.app')

@section('title', 'Modifica')

@section('content')
<header>
    <h1>Modifica Post</h1>
</header>

<form action="{{route('admin.post.update', $post)}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-12">
            <div class="mb-3">
                <label for="title" class="form-label">Titolo post</label>
                <input type="text" class="form-control" id="title" name="title" value="{{old('title', $post->title)}}" required>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label for="content" class="form-label">Contenuto post</label>
                <textarea class="form-control" id="content" rows="10" name="content" required>{{old('content', $post->content)}}
                </textarea>
            </div>
        </div>
        <div class="col-11">
            <div class="mb-3">
                <label for="image" class="form-label">Immagine post</label>
                <input type="file" class="form-control" id="image" placeholder="http:// o https://" name="image">
            </div>
        </div>
        <div class="col-1">
            <div class="mb-3">
                <img src="{{old('image', $post->image ?? 'https://marcolanci.it/boolean/assets/placeholder.png')}}" class="img-fluid" alt="Img post" id="preview">
            </div>
        </div>
        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="is_published" name="is_published" value="1" @if (old('is_published', $post->is_published)) checked @endif>
                <label class="form-check-label" for="is_published" name="is_published">
                    Pubblica
                </label>
            </div>
        </div>
    </div>
    <hr>
    <div class="d-flex align-items-center justify-content-between">
        <a href="{{route('admin.post.index')}}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Torna alla lista
        </a>
        <div class="d-flex align-items-center gap-2">
            <button type="reset" class="btn btn-sm btn-warning">
                <i class="fas fa-eraser me-2"></i>Svuota i campi
            </button>
            <button type="submit" class="btn btn-sm btn-success">
                <i class="fas fa-floppy-disk me-2"></i>Salva
            </button>
        </div>
    </div>
</form>
@endsection

@section('scritps')
<script>
    const placeholder = 'https://marcolanci.it/boolean/assets/placeholder.png';
    const input = document.getElementById('image');
    const preview = document.getElementById('preview');

    input.addEventListener('input', () => {
        preview.src = input.value || placeholder;
    })
</script>
@endsection