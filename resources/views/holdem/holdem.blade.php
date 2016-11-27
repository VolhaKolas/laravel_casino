
@extends('layouts.main')

@section('content')



    <div class="background">
        <div class="wrapper">
            @foreach($numbers as $key => $n)
                <div class="a{{$key}}" style="background-position: {{($n % 100 - 2) * 100 /12}}% {{25 * (round($n/100))}}% ">
                </div>
            @endforeach

        </div>
    </div>

    <div class="play">
        <input type="button" value="Играть">
    </div>
{{var_dump($priority)}}

@endsection

