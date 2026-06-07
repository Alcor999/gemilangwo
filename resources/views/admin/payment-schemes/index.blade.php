@extends('layouts.app')

@section('title', 'Skema Pembayaran - Admin')

@section('content')
@include('admin.payment-schemes._schemes-grid', ['schemes' => $schemes])
@endsection
