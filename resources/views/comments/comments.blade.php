@php
    if (isset($approved) && $approved) {
        if (isset($type)) {
            $comments = $model->approvedComments->where('type', $type);
        } else {
            $comments = $model->approvedComments->where('type', 'User-User');
        }
    } else {
        if (isset($type)) {
            $comments = $model->commentz->where('type', $type);
        } else {
            $comments = $model->commentz->where('type', 'User-User');
        }
    }

    $theme = Auth::user()->theme ?? (App\Models\Theme::where('is_default', true)->first() ?? null);
    $conditionalTheme = null;
    if (class_exists('\App\Models\Weather\WeatherSeason')) {
        $conditionalTheme =
            App\Models\Theme::where('link_type', 'season')
                ->where('link_id', Settings::get('site_season'))
                ->first() ??
            (App\Models\Theme::where('link_type', 'weather')
                ->where('link_id', Settings::get('site_weather'))
                ->first() ??
                $theme);
    }

    $decoratorTheme = Auth::user()->decoratorTheme ?? null;
    if (!isset($commentType)) {
        $commentType = 'comment';
    }
@endphp

<div class="row">
    <div class="{{ !isset($type) || $type == 'User-User' ? 'h2' : 'hide' }}">
        Comments
    </div>

    <div class="ml-auto">
        <div class="form-inline justify-content-end">
            <div class="form-group ml-3 mb-3">
                {!! Form::select(
                    'sort',
                    [
                        'newest' => 'Newest First',
                        'oldest' => 'Oldest First',
                    ],
                    Request::get($commentType . '-sort') ?: 'newest',
                    ['class' => 'form-control', 'id' => $commentType . '-sort'],
                ) !!}
            </div>
            <div class="form-group ml-3 mb-3">
                {!! Form::select(
                    'perPage',
                    [
                        5 => '5 Per Page',
                        10 => '10 Per Page',
                        25 => '25 Per Page',
                        50 => '50 Per Page',
                        100 => '100 Per Page',
                    ],
                    Request::get($commentType . '-perPage') ?: 5,
                    ['class' => 'form-control', 'id' => $commentType . '-perPage'],
                ) !!}
            </div>
        </div>
    </div>
</div>
<div id="{{ $commentType }}-comments">
    <div class="justify-content-center text-center mb-2">
        <i class="fas fa-spinner fa-spin fa-2x"></i>
    </div>
</div>

@auth
    @include('comments._form', [
        'compact' => isset($type) && $type == 'Staff-Staff' && config('lorekeeper.settings.wysiwyg_comments') ? true : false,
    ])
@else
    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">Authentication required</h5>
            <p class="card-text">You must log in to post a comment.</p>
            <a href="{{ route('login') }}" class="btn btn-primary">Log in</a>
        </div>
    </div>
@endauth

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            tinymce.init({
                selector: '.comment-wysiwyg',
                height: 250,
                menubar: false,
                convert_urls: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen spoiler',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | spoiler-add spoiler-remove | removeformat | code',
                content_css: [
                    '{{ asset('css/app.css') }}',
                    '{{ asset('css/lorekeeper.css?v=' . filemtime(public_path('css/lorekeeper.css'))) }}',
                    '{{ asset('css/all.min.css') }}',
                    {!! file_exists(public_path() . '/css/custom.css') ? "'" . asset('css/custom.css?v=') . filemtime(public_path('css/custom.css')) . "'," : '' !!}
                    {!! $theme?->cssUrl ? "'" . asset($theme?->cssUrl) . "'," : '' !!}
                    {!! $conditionalTheme?->cssUrl ? "'" . asset($conditionalTheme?->cssUrl) . "'," : '' !!}
                    {!! $decoratorTheme?->cssUrl ? "'" . asset($decoratorTheme?->cssUrl) . "'," : '' !!}
                ],
                content_style: `
                    {!! str_replace(['<style>', '</style>'], '', view('layouts.editable_theme', ['theme' => $theme])) !!}
                    {!! isset($conditionalTheme) && $conditionalTheme ? str_replace(['<style>', '</style>'], '', view('layouts.editable_theme', ['theme' => $conditionalTheme])) : '' !!}
                    {!! isset($decoratorTheme) && $decoratorTheme ? str_replace(['<style>', '</style>'], '', view('layouts.editable_theme', ['theme' => $decoratorTheme])) : '' !!}
                `,
                spoiler_caption: 'Toggle Spoiler',
                target_list: false
            function sortComments() {
                $('#{{ $commentType }}-comments').fadeOut();
                $.ajax({
                    url: "{{ url('sort-comments/' . base64_encode(urlencode(get_class($model))) . '/' . $model->getKey()) }}",
                    type: 'GET',
                    data: {
                        url: '{{ url()->current() }}',
                        allow_dislikes: '{{ isset($allow_dislikes) ? $allow_dislikes : false }}',
                        approved: '{{ isset($approved) ? $approved : false }}',
                        type: '{{ isset($type) ? $type : null }}',
                        sort: $('#{{ $commentType }}-sort').val(),
                        perPage: $('#{{ $commentType }}-perPage').val(),
                        page: '{{ request()->query('page') }}',
                    },
                    success: function(data) {
                        $('#{{ $commentType }}-comments').html(data);
                        // update current url to reflect sort change
                        if (
                            ($('#{{ $commentType }}-sort').val() != 'newest' || $('#{{ $commentType }}-perPage').val() != 5) ||
                            (window.location.href.indexOf('{{ $commentType }}-sort') != -1 || window.location.href.indexOf('{{ $commentType }}-perPage') != -1)
                        ) { // don't add to url if default
                            var url = new URL(window.location.href);
                            url.searchParams.set('{{ $commentType }}-sort', $('#{{ $commentType }}-sort').val());
                            url.searchParams.set('{{ $commentType }}-perPage', $('#{{ $commentType }}-perPage').val());

                            window.history.pushState({}, '', url);
                        }
                        $('#{{ $commentType }}-comments').fadeIn();
                        @include('js._tinymce_wysiwyg', ['tinymceSelector' => '.comment-wysiwyg', 'tinymceHeight' => '250', 'tinymceScript' => false])
                    }
                });
            }

            $('#{{ $commentType }}-sort').change(function() {
                sortComments();
            });

            $('#{{ $commentType }}-perPage').change(function() {
                sortComments();
            });

            sortComments(); // initial sort
        });
    </script>
@endsection
