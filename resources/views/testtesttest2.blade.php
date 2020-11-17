<html>
<head>
    <title>Задание 5. Библиотека GD2</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
</head>

<body style="width:800px">
<center><h1>Задание 5. Графическая библиотека GD2</h1></center>
<h2>Сгенерированное изображение в виде картинки: </h2>
<img id='image' src='/avatar_generator.php?name=Игорь&sizeX=300&sizeY=300&colorR=200&colorG=140&colorB=100' alt='упс, не вышло. не видать мне зачёта' />
<label id = 'label_x'></label>
<label id = 'label_y'></label>
<script>
    var image = document.getElementById('image');
    var labelX = document.getElementById('label_x');
    var labelY = document.getElementById('label_y');
    image.addEventListener('mousemove', e => {
        console.clear();
        console.log(e.offsetX, e.offsetY);
        labelX.innerText = e.offsetX;
        labelY.innerText = e.offsetY;
    });
</script>
</body>
</html>
