@extends('admin.layout')

@section('admin-title')
    Themes
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => 'admin', 'Themes' => 'admin/theme']) !!}

    <h1>Themes</h1>

    <p>You can create new Themes here for your users to be able to select from to view the site. </p>

    <div class="text-right mb-3"><a class="btn btn-primary" href="{{ url('admin/themes/create') }}"><i class="fas fa-plus"></i> Create New Theme</a></div>
    @if (!count($siteThemes))
        <p>No themes found.</p>
    @else
        {!! $siteThemes->render() !!}
        <div class="row ml-md-2">
            <div class="d-flex row flex-wrap col-12 pb-1 px-0 ubt-bottom">
                <div class="col-12 col-md-5 font-weight-bold">Name</div>
                <div class="col-6 col-md font-weight-bold">Creators</div>
            </div>
            @foreach ($siteThemes as $siteTheme)
                <div class="d-flex row flex-wrap col-12 mt-1 pt-2 px-0 ubt-top">
                    <div class="col-12 col-md-5">
                        {!! $siteTheme->is_default ? '<i class="fas fa-star mr-2" data-toggle="tooltip" title="This is the default theme."></i>' : '' !!}{!! $siteTheme->is_active ? '' : '<i class="fas fa-eye-slash mr-2"></i>' !!}{{ $siteTheme->name }}
                        {!! $siteTheme->userCount ? '<small class="text-muted">In use by ' . $siteTheme->userCount . ' user' . ($siteTheme->userCount == 1 ? '' : 's') . '</small>' : '<small class="text-muted">Not in use</small>' !!}
                    </div>
                    <div class="col-6 col-md">{!! $siteTheme->creators ? $siteTheme->creatorDisplayName : 'N/A' !!}</div>
                    <div class="col-6 col-md-1 text-right"><a href="{{ url('admin/themes/edit/' . $siteTheme->id) }}" class="btn btn-primary py-0 px-2">Edit</a></div>
                </div>
            @endforeach
        </div>
        {!! $siteThemes->render() !!}
    @endif

@endsection
