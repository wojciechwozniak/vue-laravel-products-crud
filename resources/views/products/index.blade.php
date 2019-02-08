<div id="app">
@foreach ($products as $product)
        <product name="{{$product->name}}" description="{{$product->description}}" :price="{{$product->prices}}"></product>
@endforeach
</div>

<script src="{{ asset('js/app.js') }}"></script>
