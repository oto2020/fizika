<head>
    <title>Демо</title>
    <link rel="icon"href="/storage/img/icon_1.ico" type="image/x-icon">
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{asset('/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('/css/main.css')}}" rel="stylesheet" type="text/css">
</head>
выстрел шариком под углом по нажатию пробела
<style type="text/css">canvas {
        border: 1px solid black;
        float: left;
        cursor: cell;
        display: inline-block;
    }

    #mainWindow {
        width: 1200px;
        height: 700px;
    }

    .controlPanel {
        height: 60px;
        width: 1200px;
    }

    .controlElement {
        float: left;
        display: inline-block;
        width: 210px;
        height: 85px;
        border: 1px solid black;
        margin-right: 10px;
        padding: 2px;
    }

    .slider {
        width: 200px;
    }

    #circlesColba {
        border: 1px solid white;
        width: 70px;
        height: 600px;
        display: inline-block;
    }
</style>
<br>
<div id="mainWindow">
    <div class="controlPanel">
        <div class="controlElement">
            <div id="rangeValue1">Количество шаров: 1</div>
            <br>
            <input class="slider" id="rangeOfCirclesCount" max="30" min="1" type="range" value="1"/></div>

        <div class="controlElement">
            <div id="rangeValue2">Радиус шаров: 30</div>
            <br>
            <input class="slider" id="rangeRadiusOfCircles" max="100" min="3" type="range" value="30"/></div>

        <div class="controlElement">
            <div id="rangeValue3">Количество сторон: 10</div>
            <br>
            <input class="slider" id="rangeCountOfSides" max="30" min="3" type="range" value="10"/></div>

        <div class="controlElement" id="buttons">
            <a class="btn btn-default btn-xs" style="width:200px" id="gravityButtonOn">Включить
                гравитацию
            </a>
            <a class="btn btn-default btn-xs" style="width:200px; margin-top:3px" id="gravityButtonOff">Выключить
                гравитацию
            </a>
        </div>
    </div>

    <div>
        <canvas height="600px" id="canvas" width="1000px" style="margin-top:4px;"></canvas>

        <div id="circlesColba" style="margin-top:25px">&nbsp;</div>
    </div>
</div>


<script type="text/javascript">
    class Circle {
        constructor(centerPoint, radius, countOfSides, color) {
            this.centerPoint = centerPoint;
            this.radius = radius;
            this.countOfSides = countOfSides;
            this.polygon = new Polygon(color);
            this.V = new Point(0, 0);				// Velocity - скорость
            this.A = new Point(0, 0);				// Acceleration - ускорение
            this.isActive = true;
        }

        getColor() {
            return this.polygon.fillColor;
        }

        deactivate() {
            this.isActive = false;
        }

        // [void] считает
        calcSides(countOfSides, radius) {
            this.radius = radius;
            this.polygon.arrPoints = []; //обнулили все вершины полигона
            var alpha = 360 / countOfSides;
            for (var a = 0; a < 360; a += alpha) { // вращаем угол на значение alpha
                var x = this.centerPoint.X + this.radius * Math.cos(a * 3.14 / 180);
                var y = this.centerPoint.Y + this.radius * Math.sin(a * 3.14 / 180);
                this.polygon.addVertex(new Point(x, y));
            }
        }

        // [void] пересчитывает координаты
        calcCoordinates(IS_GRAVITY_ON, MASSA, deltaT) {

            if (IS_GRAVITY_ON) {
                //a = F / m
                this.A.X = 3 * (500 - this.centerPoint.X) / MASSA;
                this.A.Y = 3 * (300 - this.centerPoint.Y) / MASSA;
            } else {
                this.A.X = 0;
                this.A.Y = 0;
            }
            //v = v0 + a*t;
            this.V.X = this.V.X + this.A.X * deltaT;
            this.V.Y = this.V.Y + this.A.Y * deltaT;

            //x = x0 + v*t + a*t*t/2;
            this.centerPoint.X = this.centerPoint.X + this.V.X * deltaT + this.A.X * deltaT * deltaT / 2;
            this.centerPoint.Y = this.centerPoint.Y + this.V.Y * deltaT + this.A.Y * deltaT * deltaT / 2;

            /// СТОЛКНОВЕНИЕ СО СТЕНОЙ
            if (this.centerPoint.X < 0) {// левая стенка
                this.centerPoint.X -= this.V.X / 10;
                this.V.X *= -0.8;
            }

            if (this.centerPoint.X > 1000) {// правая стенка
                this.centerPoint.X -= this.V.X / 10;
                this.V.X *= -0.8;
            }


            if (this.centerPoint.Y < 0) {// верхняя стенка
                this.centerPoint.Y -= this.V.Y / 10;
                this.V.Y *= -0.8;
            }


            if (this.centerPoint.Y > 600) {// нижняя стенка
                this.centerPoint.Y -= this.V.Y / 10;
                this.V.Y *= -0.8;
            }

        }

        // считает
        calc(IS_GRAVITY_ON, MASSA, countOfSides, radius, deltaT) {
            if (this.isActive) {
                this.calcCoordinates(IS_GRAVITY_ON, MASSA, deltaT);
                this.calcSides(countOfSides, radius);
            }
        }

        // рисует
        draw(context) {
            if (this.isActive) {
                myDrawing.drawPolygonFilled(this.polygon, context); 		// РИСУЕМ ПОЛИГОН ЗАНОВО
            }
        }

        // [void] задает начальную скорость
        setStartVelocity(MASSA, CENTER_X, CENTER_Y) {
            this.V = new Point(3 * (CENTER_Y - this.centerPoint.Y) / MASSA, -3 * (CENTER_X - this.centerPoint.X) / MASSA) // вектор начальной скорости перпендикулярен вектору начального ускорения (x = y; y = -x)
        }

        // [void] задает начальное ускорение
        setStartAcceleration(MASSA) {
            this.A.X = 3 * (500 - this.centerPoint.X) / MASSA;
            this.A.Y = 3 * (300 - this.centerPoint.Y) / MASSA;
        }


    }

    class Point {
        constructor(X, Y) {
            this.X = X;
            this.Y = Y;
        }
    }

    class Polygon {
        constructor(fillColor) {
            this.arrPoints = [];
            this.fillColor = fillColor;
        }

        // [Point[]] вернет массив вершин
        getVertexs() {
            return this.arrPoints;
        }

        // [number] вернет количество вершин полигона
        getCount() {
            return this.arrPoints.length;
        }

        // [Point] вернет последнюю точку полигона
        getLastVertex() {
            return this.arrPoints[this.arrPoints.length - 1];
        }

        // [Point] вернет первую точку полигона
        getFirstVertex() {
            return this.arrPoints[0];
        }

        // [void] добавляет вершину этому полигону
        addVertex(point) {
            this.arrPoints.push(point)
            //console.log("Добавили ещё одну точку. Ура! Теперь ("+this.arrPoints.length+") точек");
        }
    }

    class myDrawing {

        //рисует заданный полигон (ЗАЛИВКА)
        static drawPolygonFilled(polygone, context) {
            var pointsArr = polygone.getVertexs();
            context.beginPath();
            context.moveTo(pointsArr[0].X, pointsArr[0].Y); //двигаемся к нулевой точке
            for (var i = 1; i < pointsArr.length; i++) {
                context.lineTo(pointsArr[i].X, pointsArr[i].Y); //двигаемся к следующей точке
                /*Настройка отображения*/
                context.fillStyle = polygone.fillColor;  // context.strokeStyle = "rgb(255, 165, 0)";
            }
            context.fill();
        }

        // возвращает случайный цвет
        static randColor() {
            var r = Math.floor(Math.random() * (256)), 	// Math.random() возвращает дробное число в промежутке от 0 до 1
                g = Math.floor(Math.random() * (256)),	// Math.floor() округляет значение до целого
                b = Math.floor(Math.random() * (256));	// таким образом получаем случайное значение от 0 до 255
            return "rgb(" + r + ", " + g + ", " + b + ")"; // три таких значения составляют RGB-цвет
        }

    }

    class MyMath {
        // [boolean] проверяет факт пересечения отрезка "a" c отрезком "b"
        static isIntersection(a1, a2, b1, b2) {
            var v1, v2, v3, v4; // векторные вычисления
            v1 = (b2.X - b1.X) * (a1.Y - b1.Y) - (b2.Y - b1.Y) * (a1.X - b1.X);
            v2 = (b2.X - b1.X) * (a2.Y - b1.Y) - (b2.Y - b1.Y) * (a2.X - b1.X);
            v3 = (a2.X - a1.X) * (b1.Y - a1.Y) - (a2.Y - a1.Y) * (b1.X - a1.X);
            v4 = (a2.X - a1.X) * (b2.Y - a1.Y) - (a2.Y - a1.Y) * (b2.X - a1.X);
            return (v1 * v2 < 0 && v3 * v4 < 0);
        }

        // [boolean] попадание мыши внутрь полигона
        static isMouseInPolygon(polygon1, mouse) {
            var countOfIntersections = 0;

            var pointsArr = polygon1.getVertexs();
            var a1 = new Point(0, 0);
            var a2 = mouse; 		// отрезок "a" - это луч, отрезок, соединяющий точку 0,0 и координату мыши
            for (var i = 1; i < polygon1.getCount(); i++) {
                var b1 = pointsArr[i - 1];
                var b2 = pointsArr[i]; 	// отрезок "b" - это каждая из сторон полигона
                if (this.isIntersection(a1, a2, b1, b2)) countOfIntersections++;
            }
            if (this.isIntersection(a1, a2, polygon1.getFirstVertex(), polygon1.getLastVertex())) countOfIntersections++;
            return (countOfIntersections % 2 == 1)
        }
    }

    // глобальные переменные
    var circles = [];
    var IS_GRAVITY_ON = false;

    // создает HTML-объект колбу и добавляет её к родителю с id = "circlesColba"
    function createColba(N) {
        document.getElementById("circlesColba").innerHTML = ''; // очистка
        for (var i = 0; i < N; i++) {
            var colba = document.createElement('div');
            colba.id = 'part_' + i;
            colba.style.width = '70px';
            colba.style.height = 600 / N - 2 + 'px';
            colba.style.background = 'linear-gradient(4deg, #EEEEEE, #FFFFFF)'; // background
            colba.style.border = '1px solid #DDDDDD';
            colba.style.borderRadius = '100px';
            document.getElementById("circlesColba").appendChild(colba);
        }
    }


    function begin(N) {
        // канвас и элементы управления
        var canvas = document.getElementById('canvas');

        var rangeOfCirclesCount = document.getElementById('rangeOfCirclesCount'); 	// ползунок с выбором количества шаров
        var rangeRadiusOfCircles = document.getElementById('rangeRadiusOfCircles'); // ползунок с выбором радиуса шаров
        var rangeCountOfSides = document.getElementById('rangeCountOfSides'); 		// ползунок с выбором массы шаров

        var gravityButtonOn = document.getElementById("gravityButtonOn"); 		// кнопка "Включить гравитацию"
        var gravityButtonOff = document.getElementById("gravityButtonOff"); 	// кнопка "Выключить гравитацию"
        gravityButtonOn.onclick = function () {
            IS_GRAVITY_ON = true;
            document.getElementById("buttons").style.background = "rgb(100, 200,100)";
        };
        gravityButtonOff.onclick = function () {
            IS_GRAVITY_ON = false;
            document.getElementById("buttons").style.background = "white";
        };


        // Поверхность для отрисовки и колба с шариками
        const context = canvas.getContext('2d');
        createColba(rangeOfCirclesCount.value);

        // константы
        const CENTER_X = canvas.width / 2;  	// 500
        const CENTER_Y = canvas.height / 2; 	// 300
        const DELTA_T = 0.015;            	// шаг (в мс).
        const MASSA = 10;					// масса шаров

        // создание шариков в зависимости от их количества
        circles = [];						// массив с шариками
        var alpha = 360 / N;
        for (var a = 0; a < 360 + 0; a += alpha) { // вращаем угол на значение alpha
            var x = CENTER_X + 250 * Math.cos(a * 3.14 / 180);
            var y = CENTER_Y + 250 * Math.sin(a * 3.14 / 180);

            var circle1 = new Circle(new Point(x, y), rangeRadiusOfCircles.value, rangeCountOfSides.value, myDrawing.randColor()); 		// задали координаты, радиус, кол-во сторон, цвет
            circle1.setStartVelocity(MASSA, CENTER_X, CENTER_Y);	// задали начальную скорость

            circle1.calc(IS_GRAVITY_ON, MASSA, rangeCountOfSides.value, rangeRadiusOfCircles.value, DELTA_T);								// расчитали первый раз
            circles.push(circle1);																											// закинули в массив
        }

        // Вспомогательная функция, возвращает новую точку в координатах мыши
        function getMousePoint(e) {
            return new Point(e.layerX, e.layerY);
        }

        // ИТЕРАЦИЯ ПРОРИСОВКИ И ПЕРЕРАСЧЕТОВ
        function mainIteration() {
            context.fillStyle = "black";
            context.fillRect(0, 0, canvas.width, canvas.height); 	// ОЧИСТКА ЭКРАНА

            for (var i = 0; i < circles.length; i++) {
                circles[i].calc(IS_GRAVITY_ON, MASSA, rangeCountOfSides.value, rangeRadiusOfCircles.value, DELTA_T);
                circles[i].draw(context);
            }
        }

        var mainInterval = setInterval(mainIteration, 1); // пересчет каждую милисекунду

        // Событие MOUSE_UP	(добавление новой точки или завершение построения)
        canvas.addEventListener('mouseup', e => {
            for (var i = 0; i < circles.length; i++) {
                if (circles[i].isActive) {
                    var polygon = circles[i].polygon;
                    var mouse = getMousePoint(e);
                    if (MyMath.isMouseInPolygon(polygon, mouse)) {
                        circles[i].deactivate();
                        var colba = document.getElementById('part_' + i); 		// находим колбу по id
                        colba.style.background = circles[i].getColor();	// задаем ей цвет шара, по которому попали мышкой
                        console.log(i);
                        //console.log(document.getElementById('part'+i));
                    }
                }
            }
        });

        // ОБНОВЛЕНИЕ ЛЕЙБЛОВ ПОЛЗУНКОВ ПРИ ИХ ИЗМЕНЕНИИ
        rangeOfCirclesCount.oninput = function () {
            document.getElementById('rangeValue1').textContent = "Количество шаров: " + rangeOfCirclesCount.value;

            // если задели ползунок с количеством шаров - всё начинется заново
            clearInterval(mainInterval); 		// снимаем с образобки предыдущий обработчик для старых шаров
            begin(rangeOfCirclesCount.value);	// начали всё сначала с новым количеством шаров
        };
        rangeRadiusOfCircles.oninput = function () {
            document.getElementById('rangeValue2').textContent = "Радиус шаров: " + rangeRadiusOfCircles.value;
        };
        rangeCountOfSides.oninput = function () {
            document.getElementById('rangeValue3').textContent = "Количество сторон: " + rangeCountOfSides.value;
        };
    }

    // стартуем
    begin(rangeOfCirclesCount.value);
</script>






