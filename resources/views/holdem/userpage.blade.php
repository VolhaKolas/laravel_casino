
@extends('layouts.main')

@section('content')
   @foreach($names as $name)
       {{$name->name . " " . $name->surname . " " . $name->online}}
   @endforeach


   <form action="{{ route('texas')  }}" method="post">
       {{ csrf_field() }}
       <button class="btn btn-primary">Рандомная игра</button>
   </form>


@endsection
