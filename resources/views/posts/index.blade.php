@extends('layouts.app')

@section('head')
    {{--    Ajax loading in --}}
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>

    {{--    Cloudflare Toggle   --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>
@endsection

@section('content')
    <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">
                <h2>It's posting time</h2>
            </div>

            <div class="pull-right">
                @can('posts_create')
                    <a class="btn btn-success" href="{{ route('posts.create') }}"> Add new Pokemon</a>
                @endcan
            </div>

            <div>
                <div class="mx-auto float-right">
                    <div class="">
                        <form action="{{ route('posts.index') }}" method="GET" role="search">
                            <div class="input-group">
                            <span class="input-group-btn">
                                <button class="btn btn-info" type="submit">Search</button>
                                        <span class="fas fa-search"></span>
                            </span>
                                <input type="text" class="form-control mr-2" name="term" placeholder="Name or Type" id="term">
                                <a href="{{ route('posts.index') }}" class="mt-1"></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>Name</th>
            <th>Image</th>
            <th>Caption</th>
            <th>Caught</th>
            <th>Show</th>
        </tr>
        @foreach ($posts as $post)
            @if($post->status == 1)
            <tr>
                <td>{{ $post->name }}</td>
                <td><img src="{{ asset("storage/images/".$post['image']) }}" alt="{{ $post->name }}" height="250px" width="250px"></td>
                <td>{{ $post->caption }}</td>
                <td>
                    <div>
                        @if($post->user()->find(Auth::id()))
                            <form action="{{ route('unliked', $post)  }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div>
                                    <div>
                                        <input type="id" id="id" name="id" value="{{$post->id}}" hidden>
                                        <button type="submit" class="btn btn-outline-warning">Liked</button>
                                    </div>
                                </div>
                            </form>
                        @elseif($post->user()->find(Auth::id()) === null)
                            <form action="{{ route('liked', $post)  }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="id" id="id" name="id" value="{{$post->id}}" hidden>
                                <button type="submit" class="btn btn-outline-success">Not Liked</button>
                            </form>
                        @endif
                    </div>
                </td>
                @can('posts_status')
                <td>
                    <input type="checkbox" data-id="{{ $post->id }}" name="status"
                           class="js-switch" {{ $post->status == 1 ? 'checked' : '' }}>
                </td>
                @endcan
                <td>
                    <form action="{{ route('posts.destroy',$post) }}" method="POST">

                        @can('posts_edit')
                            <a class="btn btn-primary" href="{{ route('posts.edit',$post) }}">Edit</a>
                        @endcan

                        @csrf
                        @method('DELETE')
                        @can('posts_delete')
                            <button type="submit" class="btn btn-danger">Delete Post</button>
                        @endcan
                    </form>

                </td>
            </tr>
@endif  @endforeach
    </table>

@endsection

@section('script')
    <script>
        let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function(html) {
            let newSlider = new Switchery(html,  { size: 'small' });
        });
    </script>
    <script src="{{asset("js/slider.js")}}"></script>
@endsection
