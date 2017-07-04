#include "stdafx.h"
#include "Circle.h"


CCircle::CCircle()
{
}


CCircle::~CCircle()
{
}

void CCircle::Draw(HDC hdc) {
	Ellipse(hdc, x1, y1, x2, y2);
}



void CCircle::SetData(int a, int b, int c, int d) {
	x1 = a;
	y1 = b;
	x2 = c;
	y2 = d;
}
