<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتیجه تولید تصویر</title>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
            background-color: #202123;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #343541;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 600px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #0A84FF;
        }
        img {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .btn-download {
            display: inline-block;
            padding: 10px 20px;
            background-color: #0A84FF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        .btn-download:hover {
            background-color: #0077E6;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>تصاویر تولید شده</h2>
    @foreach($images as $image)
        <div class="image-container">
            <img src="data:image/png;base64,{{ $image['base64'] }}" alt="Generated Image">
            <a href="data:image/png;base64,{{ $image['base64'] }}" download="image.png" class="btn-download">دانلود</a>
        </div>
    @endforeach
</div>
</body>
</html>
