<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Documents</title>
</head>
<body>

<div>
    <form action="{{ route('document.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>
</div>

<table>
    <tr>
        <th>document</th>
    </tr>
    @foreach($documents as $document)
        <tr>
            <td><a href="{{ asset('docs/'.$document->file_path) }}">Show</a></td>
        </tr>
    @endforeach
</table>


</body>
</html>
