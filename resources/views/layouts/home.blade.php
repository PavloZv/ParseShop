@extends('layouts.app')

@section('title', 'Home')

@section('links')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
@endsection

@section('content')

    <h1 class="text-center" style="margin-bottom: 25px;">Content</h1>
    <div style="margin-bottom: 20px;">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Link</th>
                <th>Title</th>
                <th>Code</th>
                <th>Status</th>
                <th>Sizes</th>
                <th>Price</th>
                <th>Price2</th>
                <th>Img</th>
                <th>Colors</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($content as $index => $value)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $value['link'] }}</td>
                    <td>{{ $value['title'] }}</td>
                    <td>{{ $value['code'] }}</td>
                    <td>{{ $value['status'] }}</td>
                    <td>{{ $value['sizes'] }}</td>
                    <td>{{ $value['price'] }}</td>
                    <td>{{ $value['price2'] }}</td>
                    <td>{{ $value['img'] }}</td>
                    <td>{{ $value['colors'] }}</td>
                    <td>{{ $value['description'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
@endsection