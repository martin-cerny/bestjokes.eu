@extends('layouts.master')

@section('about-us')
    <h1>The Best {{{ $categoryTitle or '' }}} jokes</h1>
    <p>
        <?php if(!isset($categoryTitle) && isset($page) && $page <= 1) { ?>
            Welcome to the collected jokes by Martin Černý.
            I have selected only the best jokes. <br>On this website you will not find any stupid one. 
        <?php } ?>
    </p>
@stop

@section('jokes')
    @foreach ($jokes as $joke)
        @include('partials.joke')
    @endforeach
@stop

@section('pagination')
    <?php if($jokes->getTotal() > 10) { ?>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <?php echo (str_replace("?page=", "/", str_replace("$page?page=", '', $jokes->links()))) ?>
                </div>
                <div class="col-md-12 text-center bottom-buffer">
                    <a href="http://www.unijokes.com" class="talmer" style="text-decoration: none">Try more Jokes from Talmer</a>
                </div>
            </div>
        </div>
    </section>
<?php } ?>
@stop
