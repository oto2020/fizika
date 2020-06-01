class MyMath
{
	// [boolean] проверяет факт пересечения отрезка "a" c отрезком "b"
	static isIntersection(a1, a2, b1, b2) {
		var v1, v2, v3, v4; // векторные вычисления
		v1=(b2.X-b1.X)*(a1.Y-b1.Y)-(b2.Y-b1.Y)*(a1.X-b1.X);
		v2=(b2.X-b1.X)*(a2.Y-b1.Y)-(b2.Y-b1.Y)*(a2.X-b1.X);
		v3=(a2.X-a1.X)*(b1.Y-a1.Y)-(a2.Y-a1.Y)*(b1.X-a1.X);
		v4=(a2.X-a1.X)*(b2.Y-a1.Y)-(a2.Y-a1.Y)*(b2.X-a1.X);
		return (v1*v2<0 && v3*v4<0);
	}

	// [boolean] попадание мыши внутрь полигона
	static isMouseInPolygon(polygon1, mouse) {
		var countOfIntersections = 0;

		var pointsArr = polygon1.getVertexs();
		var a1 = new Point(0, 0);
		var a2 = mouse; 		// отрезок "a" - это луч, отрезок, соединяющий точку 0,0 и координату мыши
		for(var i = 1; i < polygon1.getCount(); i++) {
			var b1 = pointsArr[i-1];
			var b2 = pointsArr[i]; 	// отрезок "b" - это каждая из сторон полигона
			if(this.isIntersection(a1, a2, b1, b2)) countOfIntersections++;
		}
		if(this.isIntersection(a1, a2, polygon1.getFirstVertex(), polygon1.getLastVertex())) countOfIntersections++;
		return (countOfIntersections%2 == 1)
	}
}
