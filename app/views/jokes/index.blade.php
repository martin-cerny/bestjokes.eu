@extends('layouts.master')

@section('about-us')
    <h1>The Best {{{ $categoryTitle or '' }}} jokes</h1>
    <p>
        Welcome to the collected jokes by Martin Černý.
        I have selected only the best jokes. On this website you will not find any stupid one. 
    </p>
@stop

@section('jokes')
    @foreach ($jokes as $joke)
        @include('partials.joke')
    @endforeach
@stop

@section('pagination')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <?php echo (str_replace("?page=", "/", str_replace("$page?page=", '', $jokes->links()))) ?>
                </div>
            </div>
        </div>
    </section>
@stop
