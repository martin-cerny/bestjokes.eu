<!doctype html>
<html lang="en">
<head>
    <?php if(isset($type)) { ?>  
        <title><?php echo $joke->title ?> - bestjokes.eu</title>
    <?php } else { ?>
        <title>The best<?php echo isset($categoryTitle) ? ' ' .$categoryTitle : ' '; ?>jokes from collection of {{ $totalItems or ''}} - bestjokes.eu</title>
    <?php } ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <?php if(isset($type)) { ?>  
        <meta name="description" content="See joke - <?php echo substr($joke->text, 0, 120- strlen($joke->title))?>...">
    <?php } else { ?>
        <meta name="description" content="See the most popular<?php echo isset($categoryTitle) ? ' ' .$categoryTitle : ' '; ?>jokes according to user rating classified into 52 categories.">
    <?php } ?>
    <meta name="author" content="Martin Černý">
    <link rel="icon" href="{{ 'favicon.ico' }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" >
    {{ HTML::style('css/style.css'); }}
</head>
<body>
    <header>
        <nav>
            <div class="navbar navbar-default navbar-static-top" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="navbar-collapse collapse text-center">
                    <div class="col-lg-12">
                        <ul class="nav navbar-nav">
                            <li <?php echo isset($categoryTitle) ? '' : 'class="active"' ?>>{{ HTML::link("/", "All") }}</li>
                            @foreach ($categories as $category)
                                @if ($category->main == 1)
                                    <li <?php if (isset($categoryTitle) && $categoryTitle == $category->name) {echo('class=active');} ?>>
                                        <?php $name = str_replace(' ', '-', utf8_encode($category->name)) . "-jokes"; ?>
                                        {{ HTML::linkRoute('category', utf8_encode(ucwords($category->name)), array('category' => $name)) }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <section class="about-us">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center top-buffer">
                    @section('about-us')
                    @show
                </div>
            </div>
        </div>
    </section>
    <section class="jokes">
        <div class="container">
            @section('jokes')
            @show
        </div>
    </section>
    @section('pagination')
    @show
    <section class="social">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-3 col-md-offset-3"><i class="fa fa-twitter fa-3x"></i></div>
                    <div class="col-md-3"><i class="fa fa-facebook-square fa-3x"></i></div>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="col-md-12 top-buffer">
                        @foreach ($categories as $category)
                        <div class="col-xs-4 col-sm-2 col-md-2 text-center">
                            <?php $name = str_replace(' ', '-', utf8_encode($category->name)) . "-jokes"; ?>
                            {{ HTML::linkRoute('category', utf8_encode(ucwords($category->name)), array('category' => $name), array('style' => 'color: white; opacity: 0.75')) }}
                        </div>
                        @endforeach
                    </div>
                    <div class="copyright">2014 Black</div>
                </div>
            </div>
        </div>
    </footer>
</body>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
{{ HTML::script('js/main.js'); }}
@include('partials.scripts')
</html>
