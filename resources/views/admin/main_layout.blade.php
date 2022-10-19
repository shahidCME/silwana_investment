@extends('admin.includes.layouts')
@section('content')
<?php 
if(Session::has('Mymessage')){ 
    echo  Session::get('Mymessage');
} 
?>
@include($page)
@endsection('content')