<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>AssalamuAlaikum {{$name}}</h1>
    <div id="apires"></div>
    <div>
        <form enctype="multipart/form-data" id="frm">
            @csrf
            <input type="file" id="fu1" name="file1">
            <input type="text" id="txt" name="txt">
            <input type="submit" id="btn" value="Upload">
        </form>
    </div>
    <script>
        window.addEventListener('load', async function() {
            var res = await fetch('/api/test/1');
            var json = await res.json();
            document.getElementById('apires').innerHTML = JSON.stringify(json);
            document.getElementById('frm').addEventListener('submit', fileUpload);
        })
        async function fileUpload($event) {
            $event.preventDefault();
            document.getElementById('apires').innerHTML = 'Uploading...';
            var formData = new FormData(document.getElementById('frm'));
            var res = await fetch('/fine-test/fileUpload', {
                method: 'POST',
                body: formData
            });
            var json = await res.text();
            document.getElementById('apires').innerHTML = json;
        }
    </script>
</body>

</html>