@extends('layouts.master')

@section('about-us')
    <h1>{{{ $joke->title }}}</h1>
@stop

@section('jokes')
    @include('partials.joke')
@stop

   