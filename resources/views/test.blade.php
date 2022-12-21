@if (Session::has('order'))
    <script>
        // new swal("Done", "Orders are created", "success");
        alert("done");
    </script>
@endif
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <a href={{ route('testing') }}>rote</a>
</body>

</html>
