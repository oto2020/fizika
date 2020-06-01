class myDrawing 
{

	//рисует заданный полигон (ЗАЛИВКА)
	static drawPolygonFilled(polygone, context) { 
		var pointsArr = polygone.getVertexs();
		context.beginPath();
			context.moveTo(pointsArr[0].X, pointsArr[0].Y); //двигаемся к нулевой точке
			for(var i = 1; i < pointsArr.length; i++) {
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