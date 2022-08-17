<!DOCTYPE html>
<html>
<head>
    <title>Import Export to database</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
</head>
<body>

<div class="container">
    <div class="card mt-4">
        <div class="card-header">

        </div>
        <div class="card-body">
            <form action="{{ route('wallpapers.compareFilePost') }}" method="POST" name="compareFilePost"
                  enctype="multipart/form-data">
                @csrf
                <label>Image check</label>
                <input type="file" name="image_1" class="form-control" required>
                <label>Image compare</label>
                <input type="file" name="image_2" class="form-control" required>

                <label>Khoảng so sánh</label>
                <input type="number" name="distance" class="form-control" value="5">

                <br>
                <button class="btn btn-success">Import File</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
