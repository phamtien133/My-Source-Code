#include "stdafx.h"

#pragma once

#ifdef MATHLIBRARY_EXPORTS
#define MATHLIBRARY_API __declspec(dllexport) 
#else
#define MATHLIBRARY_API __declspec(dllimport) 
#endif

namespace MathLibrary
{
	// This class is exported from the MathLibrary.dll
	class Functions
	{
	public:
		// Returns Draw a line
		static MATHLIBRARY_API void DrawLine(HDC hdc, int x1, int y1, int x2, int y2);

		// Returns Draw a Rectangle
		static MATHLIBRARY_API void DrawRectangle(HDC hdc, int x1, int y1, int x2, int y2);

		// Returns a + (a * b)
		static MATHLIBRARY_API void DrawCircle(HDC hdc, int x1, int y1, int x2, int y2);
	};
}

extern "C" MATHLIBRARY_API void Calc1(HDC hdc, int x1, int y1, int x2, int y2);