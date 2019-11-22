<!doctype html>

<html leng="en">


<head>

     <meta charset="utf-8" >
	 <meta name="viewport" content="width=device-width, initial-scale=1">
	 
    <title> @yield('title')</title>
	
	@include('includes.styles')
	
</head>

<body>

       
	   {{-- @include('includes.header') --}}

	<div id="page_content">
    	<div id="page_content_inner">
			@yield('header')
			@yield('contents')
		</div>
	</div>		
	
	@yield('scripts')




</body>

</html>