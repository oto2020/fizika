class Circle
{
	constructor (centerPoint, radius, countOfSides, color) {
		this.centerPoint = centerPoint;
		this.radius = radius;
		this.countOfSides = countOfSides;
		this.polygon = new Polygon(color);
		this.V = new Point(0,0);				// Velocity - скорость
		this.A = new Point(0,0);				// Acceleration - ускорение 
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
		var alpha = 360/countOfSides;
		for(var a = 0; a < 360; a+=alpha) { // вращаем угол на значение alpha
			var x = this.centerPoint.X + this.radius * Math.cos(a*3.14/180);
			var y = this.centerPoint.Y + this.radius * Math.sin(a*3.14/180);
			this.polygon.addVertex(new Point(x, y));
		}
	}
	
	// [void] пересчитывает координаты
	calcCoordinates(IS_GRAVITY_ON, MASSA, deltaT) {
		
		if(IS_GRAVITY_ON) {
			//a = F / m
			this.A.X = 3* (500 - this.centerPoint.X)/MASSA;
			this.A.Y = 3* (300 - this.centerPoint.Y)/MASSA;
		}
		else {
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
		if(this.centerPoint.X < 0) {// левая стенка
			this.centerPoint.X -= this.V.X/10;
			this.V.X*=-0.8;
		}
			
		if(this.centerPoint.X > 1000) {// правая стенка
			this.centerPoint.X -= this.V.X/10;
			this.V.X*=-0.8;		
		}

			
		if(this.centerPoint.Y < 0) {// верхняя стенка
			this.centerPoint.Y -= this.V.Y/10;
			this.V.Y*=-0.8;
		}
		
			
		if(this.centerPoint.Y > 600) {// нижняя стенка
			this.centerPoint.Y -= this.V.Y/10;
			this.V.Y*=-0.8;
		}
		
	}
	
	// считает
	calc(IS_GRAVITY_ON, MASSA, countOfSides, radius, deltaT) {
		if(this.isActive) {
			this.calcCoordinates(IS_GRAVITY_ON, MASSA, deltaT);
			this.calcSides(countOfSides, radius);
		}
	}
	
	// рисует
	draw(context) {
		if(this.isActive) {
			myDrawing.drawPolygonFilled(this.polygon, context); 		// РИСУЕМ ПОЛИГОН ЗАНОВО
		}
	}
	
	// [void] задает начальную скорость
	setStartVelocity(MASSA, CENTER_X, CENTER_Y) {
		this.V = new Point(3* (CENTER_Y - this.centerPoint.Y)/MASSA, -3* (CENTER_X - this.centerPoint.X)/MASSA) // вектор начальной скорости перпендикулярен вектору начального ускорения (x = y; y = -x) 
	}
	
	// [void] задает начальное ускорение
	setStartAcceleration(MASSA) {
		this.A.X = 3* (500 - this.centerPoint.X)/MASSA;
		this.A.Y = 3* (300 - this.centerPoint.Y)/MASSA;
	}
	

	
}

























