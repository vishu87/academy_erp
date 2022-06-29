@extends('layout_web')

@section('content')

<x-web.container :background="$background" :logo="$logo_url" controller="" init=""  footer="">

	{!! $content !!}
	
</x-web.container>

@endsection