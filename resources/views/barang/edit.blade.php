@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
<h4 class="mb-3">Edit Barang</h4>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('barang.update', $barang) }}" method="POST">
            @csrf
            @method('PUT')
            @include('barang._form', ['barang' => $barang])

            <div class="mt-4">
                <button class="btn btn-primary">Perbarui</button>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection