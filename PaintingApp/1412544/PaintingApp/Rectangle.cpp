#include "stdafx.h"
#include "Rectangle.h"


CRectangle::CRectangle()
{
}


CRectangle::~CRectangle()
{
}

void CRectangle::Draw(HDC hdc) {
	Rectangle(hdc, x1, y1, x2, y2);
}


void CRectangle::SetData(int a, int b, int c, int d) {
	x1 = a;
	y1 = b;
	x2 = c;
	y2 = d;
}