@extends('errors.layout')

@php
    $code = 401;
    $title = 'Unauthorized Access';
    $message = 'You are not authenticated or your session has expired. Please log in to continue.';
@endphp