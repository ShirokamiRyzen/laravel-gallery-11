@extends('layouts.app')

@section('content')

    <style>
        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }

        .ratio-16x9 {
            aspect-ratio: 16 / 9;
        }

        .object-fit-cover {
            object-fit: cover;
        }
    </style>

    <div style="height: 100vh; background-image: url(https://images.unsplash.com/photo-1619441207978-3d326c46e2c9?q=80&w=2069&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D); background-size: cover; background-position: center;"
        class="position-relative w-100 mb-5">
        <div class="position-absolute text-white d-flex flex-column align-items-center justify-content-center"
            style="top: 0; right: 0; bottom: 0; left: 0; background-color: rgba(0, 0, 0, 0.5);">
            <span>Gallery App</span>
            <h1 class="mb-4 mt-2 font-weight-bold text-center">Made with Laravel Framework</h1>
            <div class="text-center">
                @if (auth()->check())
                    <a href="{{ route('album.create') }}" class="btn px-5 py-3 text-white mt-3 mt-sm-0 mx-1"
                        style="border-radius: 30px; background-color: #4346eb;"><i class="fa-solid fa-plus"></i> Add Album</a>
                    <a href="{{ route('foto.create') }}" class="btn px-5 py-3 text-white mt-3 mt-sm-0 mx-1"
                        style="border-radius: 30px; border:1px solid #4346eb;"><i class="fa-solid fa-plus"></i> Add Photo</a>
                @else
                    <a href="{{ route('login') }}" class="btn px-5 py-3 text-white mt-3 mt-sm-0 mx-1"
                        style="border-radius: 30px; background-color: #4346eb;"><i class="fa-solid fa-right-to-bracket"></i>
                        Login</a>
                    <a href="{{ route('register.create') }}" class="btn px-5 py-3 text-white mt-3 mt-sm-0 mx-1"
                        style="border-radius: 30px; border:1px solid #4346eb;"><i class="fa-solid fa-user-plus"></i>
                        Register</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="row justify-content-center mb-3" id="images">
        <form action="{{ route('home.index') }}#images" method="GET" class="col-lg-6">
            <div class="input-group">
                <input type="text" name="query" class="form-control" placeholder="Search photos...">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </div>
        </form>
    </div>

    <!-- Filter -->
    <div class="row justify-content-center mb-3">
        <form action="{{ route('home.index') }}#images" method="GET" class="col-lg-6">
            <div class="input-group">
                <select name="album_query" class="form-control">
                    <option value="" selected disabled>Select Album</option>
                    @foreach ($albumNames as $albumName)
                        <option value="{{ $albumName }}">{{ $albumName }}</option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Search Albums</button>
                </div>
            </div>
        </form>
    </div>

    @if ($searchQuery)
        <center>
            <h4>Search results for "{{ $searchQuery }}":</h4>
        </center>
    @endif

    <div class="container col-xxl-8 px-4 py-5">
        @if (count($fotos) > 0)
            <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                @foreach ($fotos as $foto)
                    <div class="col-lg-4 mb-4">
                        <a href="{{ route('home.show', $foto->id) }}" class="text-decoration-none">
                            <div class="border rounded p-3">
                                <div class="ratio-16x9 mb-3">
                                    <img src="{{ Storage::url($foto->LokasiFile) }}?quality=10"
                                        class="img-fluid object-fit-cover w-100 h-100" alt="{{ $foto->JudulFoto }}" loading="lazy">
                                </div>
                                <h3 class="mb-2">{{ Str::limit($foto->JudulFoto, 25) }}</h3>
                                <p class="mb-2">{{ Str::limit($foto->DeskripsiFoto, 40) }}</p>
                                <p class="mb-0">Uploaded by: {{ $foto->user->Username }}</p>
                                <p class="mt-2"><i class="fa-solid fa-heart"></i> {{ $foto->total_likes }}&nbsp;&nbsp;&nbsp;
                                    <i class="fa-solid fa-comment"></i> {{ $foto->komentar_count }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="col-12 text-center">
                <p class="font-weight-bold">No image was uploaded.</p>
                <p class="font-weight-bold">¯\_(ツ)_/¯</p>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="card-footer">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                @if ($fotos->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $fotos->previousPageUrl() }}#images">&laquo;</a></li>
                @endif

                @for ($i = 1; $i <= $fotos->lastPage(); $i++)
                    <li class="page-item {{ $i === $fotos->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $fotos->url($i) }}#images">{{ $i }}</a>
                    </li>
                @endfor

                @if ($fotos->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $fotos->nextPageUrl() }}#images">&raquo;</a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                @endif
            </ul>
        </nav>
    </div>

    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
            });
        @elseif (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
            });
        @endif
    </script>
@endsection