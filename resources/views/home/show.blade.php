@extends('layouts.app')
@section('title', 'Detail Foto')

@section('content')
    <style>
        .comment-bubble {
            position: relative;
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 10px 15px;
            margin-left: 12px;
            max-width: 100%;
            display: inline-block;
            color: #212529;
        }

        .comment-bubble::before {
            content: '';
            position: absolute;
            top: 12px;
            left: -8px;
            width: 0;
            height: 0;
            border-top: 8px solid transparent;
            border-bottom: 8px solid transparent;
            border-right: 8px solid #f8f9fa;
        }

        /* FIXED DARK MODE */
        [data-bs-theme="dark"] .comment-bubble {
            background-color: #343a40 !important;
            color: #f8f9fa !important;
        }

        [data-bs-theme="dark"] .comment-bubble::before {
            border-right-color: #343a40 !important;
        }
    </style>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <img src="{{ Storage::url($foto->LokasiFile) }}" class="img-fluid" alt="{{ $foto->JudulFoto }}"
                            loading="lazy">
                        <h2 class="mt-4">{{ $foto->JudulFoto }}</h2>
                        <p class="mb-2">{{ $foto->DeskripsiFoto }}</p>
                        <p>Uploaded by: {{ $foto->user->Username }}</p>
                        <p class="text-muted">Uploaded on: {{ $foto->created_at->format('d M Y H:i') }}</p>

                        <div class="row">
                            <div class="col">
                                <a href="{{ Storage::url($foto->LokasiFile) }}" class="btn btn-primary" download>
                                    <i class="fa-solid fa-download"></i> Download
                                </a>
                            </div>
                            <div class="col text-end">
                                @guest
                                @else
                                    <button
                                        class="btn {{ $foto->isLikedByUser(auth()->id()) ? 'btn-danger' : 'btn-primary' }} like-btn"
                                        data-foto-id="{{ $foto->id }}"
                                        data-liked="{{ $foto->isLikedByUser(auth()->id()) ? 'true' : 'false' }}"
                                        data-like-url="{{ route('foto.like', $foto->id) }}"
                                        data-unlike-url="{{ route('foto.unlike', $foto->id) }}">
                                        <i
                                            class="{{ $foto->isLikedByUser(auth()->id()) ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                                    </button>
                                @endguest
                                <p id="total-likes"><i class="fa-solid fa-heart"></i> {{ $foto->total_likes }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comment Section -->
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <h3>Comments</h3>
                <div id="comment-list">
                    @if ($foto->komentar && count($foto->komentar) > 0)
                        <ul class="list-unstyled">
                            @foreach ($foto->komentar as $index => $komentar)
                                <div class="d-flex align-items-start mb-3">
                                    <div class="flex-shrink-0">
                                        @if ($komentar->user && $komentar->user->profile_picture)
                                            <img src="{{ asset('storage/user_profile/' . $komentar->user->profile_picture) }}"
                                                alt="Profile Picture" class="rounded-circle" style="width: 45px; height: 45px;">
                                        @else
                                            <img src="{{ asset('assets/default-avatar.jpg') }}" alt="Profile Picture"
                                                class="rounded-circle" style="width: 45px; height: 45px;">
                                        @endif
                                    </div>

                                    <div class="flex-grow-1 ms-2 position-relative">
                                        <div class="comment-bubble">
                                            <strong>{{ $komentar->user->NamaLengkap }}</strong><br>
                                            {{ $komentar->IsiKomentar }}<br>
                                            <small class="text-muted">{{ $komentar->created_at->format('d M Y H:i') }}</small>
                                        </div>
                                    </div>

                                    @auth
                                        @if ($komentar->id_user == auth()->id())
                                            <form action="{{ route('foto.deleteComment', $komentar->id) }}" method="post" class="ms-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            @endforeach
                        </ul>
                        @if (count($foto->komentar) > 10)
                            <div class="text-center mt-3">
                                <button class="btn btn-primary load-more-btn mb-3">Load More Comments</button>
                            </div>
                        @endif
                    @else
                        <p>No comments yet.</p>
                    @endif
                </div>

                @auth
                    <form id="comment-form" action="{{ route('foto.komentar', $foto->id) }}" method="post">
                        @csrf
                        <div class="form-group">
                            <textarea id="comment-text" name="IsiKomentar" class="form-control" rows="3"
                                placeholder="Add a comment..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Send</button>
                    </form>
                @else
                    <p><a href="{{ route('login') }}">Login to comment</a></p>
                @endauth
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.load-more-btn').click(function () {
                $('.comment-container:hidden').slice(0, 10).show();
                if ($('.comment-container:hidden').length == 0) {
                    $('.load-more-btn').hide();
                }
            });
        });
    </script>

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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const likeBtn = document.querySelector('.like-btn');

            if (likeBtn) {
                likeBtn.addEventListener('click', function () {
                    const fotoId = this.dataset.fotoId;
                    const liked = this.dataset.liked === 'true';
                    const likeUrl = this.dataset.likeUrl;
                    const unlikeUrl = this.dataset.unlikeUrl;
                    const url = liked ? unlikeUrl : likeUrl;
                    const token = '{{ csrf_token() }}';
                    const btn = this;

                    fetch(url, {
                        method: liked ? 'DELETE' : 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                    })
                        .then(response => response.json())
                        .then(data => {
                            btn.dataset.liked = (!liked).toString();
                            btn.innerHTML = `<i class="${!liked ? 'fa-solid' : 'fa-regular'} fa-heart"></i>`;
                            btn.classList.remove(liked ? 'btn-danger' : 'btn-primary');
                            btn.classList.add(!liked ? 'btn-danger' : 'btn-primary');

                            document.getElementById('total-likes').innerHTML = `<i class="fa-solid fa-heart"></i> ${data.total_likes}`;
                        })
                        .catch(error => {
                            console.error('Like error:', error);
                        });
                });
            }
        });
    </script>
@endsection