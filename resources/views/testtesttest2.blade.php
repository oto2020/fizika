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
        width: 1000px;
    }

    .controlElement {
        float: left;
        display: inline-block;
        width: 210px;
        height: 85px;
        border: 1px solid black;
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
            <div id="rangeValue2">Радиус шаров: 12</div>
            <br>
            <input class="slider" id="rangeRadiusOfCircles" max="100" min="3" type="range" value="12"/></div>

        <div class="controlElement" style="margin-left: 10px;">
            <div id="rangeValue3">Количество сторон: 10</div>
            <br>
            <input class="slider" id="rangeCountOfSides" max="30" min="3" type="range" value="10"/></div>

        <div class="controlElement" id="buttons" style="float:right">
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

    <div class="progress" style="width:1000px">
        <div id="progress_bar" style="background-color: red; width: 0%;"> </div>
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
        setStartVelocity(MASSA, V_X, V_Y) {
            //this.V = new Point(3 * (CENTER_Y - this.centerPoint.Y) / MASSA, -3 * (CENTER_X - this.centerPoint.X) / MASSA) // вектор начальной скорости перпендикулярен вектору начального ускорения (x = y; y = -x)
            this.V = new Point (V_X, V_Y);
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

        static drawGun(gun, context) {
            context.beginPath();
            context.moveTo(gun.p1.X, gun.p1.Y);
            context.lineTo(gun.p2.X, gun.p2.Y);
            context.lineWidth = 15;
            context.strokeStyle = "#ff0000"; // цвет линии
            context.stroke();
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

    class Gun {
        constructor(canvas_width, canvas_height, len) {
            this.canvas_width = canvas_width;
            this.canvas_height = canvas_height;
            this.len = len;
            this.angle = 300;
            this.p1 = new Point(0, 0 + canvas_height);
            this.p2 = new Point(Math.cos(this.angle), Math.sin(this.angle) + this.canvas_height);
        }
        // получает угол, изменяет координаты p2
        setAngle(angle) {
            this.angle = angle;
            this.p2.X = this.len * Math.cos(angle*0.017) + this.p1.X;
            this.p2.Y = this.len * Math.sin(angle*0.017) + this.canvas_height;
           // console.log(angle);
           // console.log(this.p2);
        }
    }
    // глобальные переменные
    var circles = [];
    var IS_GRAVITY_ON = false;

    // создает HTML-объект колбу и добавляет её к родителю с id = "circlesColba"
    // function createColba(N) {
    //     document.getElementById("circlesColba").innerHTML = ''; // очистка
    //     for (var i = 0; i < N; i++) {
    //         var colba = document.createElement('div');
    //         colba.id = 'part_' + i;
    //         colba.style.width = '70px';
    //         colba.style.height = 600 / N - 2 + 'px';
    //         colba.style.background = 'linear-gradient(4deg, #EEEEEE, #FFFFFF)'; // background
    //         colba.style.border = '1px solid #DDDDDD';
    //         colba.style.borderRadius = '100px';
    //         document.getElementById("circlesColba").appendChild(colba);
    //     }
    // }

    // ПУШКА
    let gun = new Gun(canvas.width, canvas.height, 100);
    gun.setAngle(315);
    let progress = 0;
    function begin() {
        // канвас и элементы управления
        var canvas = document.getElementById('canvas');

        // var rangeOfCirclesCount = document.getElementById('rangeOfCirclesCount'); 	// ползунок с выбором количества шаров
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
        // createColba(rangeOfCirclesCount.value);
        //
        // константы
        const CENTER_X = canvas.width / 2;  	// 500
        const CENTER_Y = canvas.height / 2; 	// 300
        const DELTA_T = 0.015;            	// шаг (в мс).
        const MASSA = 10;					// масса шаров




        // создание шариков в зависимости от их количества
        circles = [];						// массив с шариками
        // var alpha = 360 / N;
        // for (var a = 0; a < 360 + 0; a += alpha) { // вращаем угол на значение alpha
        //     var x = CENTER_X + 250 * Math.cos(a * 3.14 / 180);
        //     var y = CENTER_Y + 250 * Math.sin(a * 3.14 / 180);
        //
        //     var circle1 = new Circle(new Point(x, y), rangeRadiusOfCircles.value, rangeCountOfSides.value, myDrawing.randColor()); 		// задали координаты, радиус, кол-во сторон, цвет
        //     circle1.setStartVelocity(MASSA, CENTER_X, CENTER_Y);	// задали начальную скорость
        //
        //     circle1.calc(IS_GRAVITY_ON, MASSA, rangeCountOfSides.value, rangeRadiusOfCircles.value, DELTA_T);								// расчитали первый раз
        //     circles.push(circle1);																											// закинули в массив
        // }

        // Вспомогательная функция, возвращает новую точку в координатах мыши
        function getMousePoint(e) {
            return new Point(e.layerX, e.layerY);
        }

        // ИТЕРАЦИЯ ПРОРИСОВКИ И ПЕРЕРАСЧЕТОВ
        function mainIteration() {

            // выбор силы выстрела
            if(isMousePressed) {
                progress += 0.9;
                if (progress >= 100) progress = 100;
                document.getElementById('progress_bar').style.width = progress + '%';
            }



            context.fillStyle = "black";
            context.fillRect(0, 0, canvas.width, canvas.height); 	// ОЧИСТКА ЭКРАНА
            // нарисовали пушку
            myDrawing.drawGun(gun, context);
            for (let i = 0; i < circles.length; i++) {
                circles[i].calc(IS_GRAVITY_ON, MASSA, rangeCountOfSides.value, rangeRadiusOfCircles.value, DELTA_T);
                circles[i].draw(context);
            }
        }

        let mainInterval = setInterval(mainIteration, 1); // пересчет каждую милисекунду

        rangeRadiusOfCircles.oninput = function () {
            document.getElementById('rangeValue2').textContent = "Радиус шаров: " + rangeRadiusOfCircles.value;
        };
        rangeCountOfSides.oninput = function () {
            document.getElementById('rangeValue3').textContent = "Количество сторон: " + rangeCountOfSides.value;
        };

        // дуло вверх-вниз, влево-вправо
        document.onkeydown = function (e) {
            if (e.code === 'KeyW') {
                // console.log('была нажата KeyW');
                let angle = gun.angle;
                angle+=3;
                gun.setAngle(angle);
            }
            if (e.code === 'KeyS') {
                //console.log('была нажата KeyS');
                let angle = gun.angle;
                angle-=3;
                gun.setAngle(angle);
            }

            if (e.code === 'KeyA') {
                // console.log('была нажата KeyW');
                let angle = gun.angle;
                gun.p1.X -=9;
                if (gun.p1.X <= 0) gun.p1.X = 0;
                gun.setAngle(angle);
            }
            if (e.code === 'KeyD') {
                //console.log('была нажата KeyS');
                let angle = gun.angle;
                gun.p1.X +=9;
                if (gun.p1.X >= canvas.width) gun.p1.X = canvas.width;
                gun.setAngle(angle);
            }
        };

        canvas.onmousemove = function(e) {
         //   console.log(e);

            let a = new Point(e.layerX, e.layerY);
            let b = new Point(gun.p1.X, gun.p1.Y);
            let c = new Point(gun.p1.X + 100, gun.p1.Y);

            // узнаем угол между ab и cb
            let x1 = a.X - b.X;
            let x2 = c.X - b.X;
            let y1 = a.Y - b.Y;
            let y2 = c.Y - b.Y;
            let d1 = Math.sqrt (x1 * x1 + y1 * y1);
            let d2 = Math.sqrt (x2 * x2 + y2 * y2);
            let angle =  370 - Math.acos ((x1 * x2 + y1 * y2) / (d1 * d2)) / 0.017;

            gun.setAngle(angle);
        };
        let isMousePressed = false;
        // выбор силы выстрела
        canvas.onmousedown = function(e) {
            isMousePressed = true;
        };
        // выстрел
        canvas.onmouseup = function(e) {
            isMousePressed = false;
            console.log(gun.angle);

            let V_X = progress * 3 *  Math.cos((gun.angle)*0.017);
            let V_Y = progress * 3 *  Math.sin((gun.angle)*0.017);
            console.log(V_X, V_Y);

            progress = 0;
            document.getElementById('progress_bar').style.width = progress + '%';
            let circle1 = new Circle(new Point(gun.p2.X, gun.p2.Y), rangeRadiusOfCircles.value, rangeCountOfSides.value, myDrawing.randColor()); 		// задали координаты, радиус, кол-во сторон, цвет
            circle1.setStartVelocity(MASSA, V_X, V_Y);	// задали начальную скорость
            circle1.calc(IS_GRAVITY_ON, MASSA, rangeCountOfSides.value, rangeRadiusOfCircles.value, DELTA_T);								// расчитали первый раз
            circles.push(circle1);

        };

    }

    // стартуем
    begin();
</script>






