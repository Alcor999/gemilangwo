@extends('layouts.app')

@section('title', 'Skema Pembayaran - Gemilang WO')

@section('content')
@include('admin.payment-schemes._schemes-grid', ['schemes' => $schemes])
@endsection
