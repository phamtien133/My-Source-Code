// MathLibrary.cpp : Defines the exported functions for the DLL application.
//

#include "stdafx.h"
#include "MathLibraryAndClient.h"

namespace MathLibrary
{
	void Functions::DrawLine(HDC hdc, int x1, int y1, int x2, int y2)
	{
		MoveToEx(hdc, x1, y1, NULL);
		LineTo(hdc, x2, y2);
	}

	void Functions::DrawRectangle(HDC hdc, int x1, int y1, int x2, int y2)
	{
		Rectangle(hdc, x1, y1, x2, y2);
	}

	void Functions::DrawCircle(HDC hdc, int x1, int y1, int x2, int y2)
	{
		Ellipse(hdc, x1, y1, x2, y2);
	}
}

void Calc1(HDC hdc, int x1, int y1, int x2, int y2)
{
	MoveToEx(hdc, x1, y1, NULL);
	LineTo(hdc, x2, y2);
}