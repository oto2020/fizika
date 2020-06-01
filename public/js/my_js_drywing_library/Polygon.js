class Polygon 
{
	constructor (fillColor) {
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
	addVertex (point) {
		this.arrPoints.push(point)
		//console.log("Добавили ещё одну точку. Ура! Теперь ("+this.arrPoints.length+") точек");
	}



}