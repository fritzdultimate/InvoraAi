@extends('errors.layout')

@php
    $code = 429;
    $title = 'Too Many Requests';
    $message = 'You are doing that too fast. Please slow down and try again shortly.';
@endphp