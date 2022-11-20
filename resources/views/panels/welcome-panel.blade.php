@php

    $levelAmount = 'level';

    if (Auth::User()->level() >= 2) {
        $levelAmount = 'levels';

    }

@endphp

<div class="card">
    <div class="card-header @role('admin', true) bg-secondary text-white @endrole">

        Welcome {{ Auth::user()->name }}

        @role('admin', true)
            <span class="pull-right badge badge-primary" style="margin-top:4px">
                Admin Access
            </span>
        @else
            <span class="pull-right badge badge-warning" style="margin-top:4px">
                User Access
            </span>
        @endrole

    </div>
    <div class="card-body">
        <h2 class="lead">
            {{ trans('auth.loggedIn') }}
        </h2>

<!--        <p>
            <em>Thank you</em> for checking this project out. <strong>Please remember to star it!</strong>
        </p>
        <p>
            <iframe src="https://ghbtns.com/github-btn.html?user=jeremykenedy&repo=laravel-auth&type=star&count=true" frameborder="0" scrolling="0" width="170px" height="20px" style="margin: 0px 0 -3px .5em;"></iframe>
        </p>
        <p>
            This page route is protected by <code>activated</code> middleware. Only accounts with activated emails are able pass this middleware.
        </p>
        <p>
            <small>
                Users registered via Social providers are by default activated.
            </small>
        </p>-->

        @section('content')
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        @if(session()->get('message'))
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        @endif
                        <div class="card">
                            <div class="card-header">File Upload</div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('file.upload') }}" aria-label="{{ __('Upload') }}">
                                    @csrf
                                    <div class="form-group row ">
                                        <label for="title" class="col-sm-4 col-form-label text-md-right">{{ __('File Upload') }}</label>
                                        <div class="col-md-6">
                                            <div id="file" class="dropzone"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="title" class="col-sm-4 col-form-label text-md-right">{{ __('Title') }}</label>
                                        <div class="col-md-6">
                                            <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title') }}" required autofocus />
                                            @if ($errors->has('title'))
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="overview" class="col-sm-4 col-form-label text-md-right">{{ __('Overview') }}</label>
                                        <div class="col-md-6">
                                            <textarea id="overview" cols="10" rows="10" class="form-control{{ $errors->has('overview') ? ' is-invalid' : '' }}" name="overview" value="{{ old('overview') }}" required autofocus></textarea>
                                            @if ($errors->has('overview'))
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('overview') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="price" class="col-md-4 col-form-label text-md-right">{{ __('Price') }}</label>
                                        <div class="col-md-6">
                                            <input id="price" type="text" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" name="price" required>
                                            @if ($errors->has('price'))
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Upload') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection
        @section('scripts')
            <script>
                var drop = new Dropzone('#file', {
                    createImageThumbnails: false,
                    addRemoveLinks: true,
                    url: "{{ route('upload') }}",
                    headers: {
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
                    }
                });
            </script>
        @endsection

        @if(session()->get('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <hr>

        <p>
            You have
                <strong>
                    @role('admin')
                       Admin
                    @endrole
                    @role('user')
                       User
                    @endrole
                </strong>
            Access
        </p>

        <hr>

        <p>
            You have access to {{ $levelAmount }}:
            @level(5)
                <span class="badge badge-primary margin-half">5</span>
            @endlevel

            @level(4)
                <span class="badge badge-info margin-half">4</span>
            @endlevel

            @level(3)
                <span class="badge badge-success margin-half">3</span>
            @endlevel

            @level(2)
                <span class="badge badge-warning margin-half">2</span>
            @endlevel

            @level(1)
                <span class="badge badge-default margin-half">1</span>
            @endlevel
        </p>

        @role('admin')

            <hr>

            <p>
                You have permissions:
                @permission('view.users')
                    <span class="badge badge-primary margin-half margin-left-0">
                        {{ trans('permsandroles.permissionView') }}
                    </span>
                @endpermission

                @permission('create.users')
                    <span class="badge badge-info margin-half margin-left-0">
                        {{ trans('permsandroles.permissionCreate') }}
                    </span>
                @endpermission

                @permission('edit.users')
                    <span class="badge badge-warning margin-half margin-left-0">
                        {{ trans('permsandroles.permissionEdit') }}
                    </span>
                @endpermission

                @permission('delete.users')
                    <span class="badge badge-danger margin-half margin-left-0">
                        {{ trans('permsandroles.permissionDelete') }}
                    </span>
                @endpermission

            </p>

        @endrole

    </div>
</div>
