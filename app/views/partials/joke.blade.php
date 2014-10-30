<article class="joke col-md-8 col-md-offset-2" itemscope="" itemtype="http://schema.org/Article">
    <div class="row">
        <h1 class="col-md-12 text-left"><a href="joke/<?php echo $joke->id ?>"><?php echo ucfirst(utf8_encode($joke->title)); ?></a></h1>
        <div class="joke-tag col-md-12">
            <i class="fa fa-tag top-buffer">   
                <?php
                $jokeCategories = explode(',', $joke->categories);
                $next = false;
                foreach ($jokeCategories as $jokeCategory) {
                    if ($next) {
                        echo "|";
                    } else {
                        $next = true;
                    }
                    ?>
                    <a href="./<?php echo str_replace(' ', '-', utf8_encode($jokeCategory)); ?>-jokes">
                        <strong>
                    <?php echo($jokeCategory); ?>
                        </strong>
                    </a>
                <?php } ?>
            </i>
        </div>
        <div class="joke-text col-md-12"><?php echo $joke->text; ?></div>
        <div class="col-xs-12">
            <div class="joke-social col-xs-12 col-sm-3 top-buffer">
                <div class="fb-like" data-href="http://bestjokes.eu/joke/<?php echo $joke->id ?>" data-layout="button" data-action="like" data-show-faces="true" data-share="false"></div>
                <!--<i class="fa fa-twitter fa-2x color-twitter col-xs-4 col-sm-2  text-center"></i>
                <i class="fa fa-facebook-square fa-2x color-facebook col-xs-4 col-sm-2  text-center"></i>
                <i class="fa fa-share-alt fa-2x color-share col-xs-4 col-sm-2  text-center"></i>-->
            </div>
            <div class="joke-vote col-xs-12 col-sm-7 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-5 top-buffer">
                <a href="#" class="vote vote-minus" id="{{ $joke->id }}"><i class="fa fa-minus fa-2x color-minus col-xs-4 col-md-3 text-center"></i></a>
                <div class="col-xs-4 col-lg-6 text-center">Votes <strong><span id="{{$joke->id}}">{{{$joke->plus_votes - $joke->minus_votes}}}</span></strong></div>
                <a href="#" class="vote vote-plus" id="{{ $joke->id }}"><i class="fa fa-plus fa-2x color-plus col-xs-4 col-md-3 text-center"></i></a>
            </div>
        </div>
    </div>
</article>