#pragma once
#include "Shape.h"
class CLine
{
public:
	int x1;
	int y1;
	int x2;
	int y2;
	vector<CLine> shapes;
public:
	CLine();
	~CLine();
	void Draw(HDC hdc);

	

	void SetData(int a, int b, int c, int d);	
	int getX1()
	{
		return x1;
	}
	void ReadFile(char* FileName);
	void WriteFile(char* FileName);
	void Push_Shape(CLine Item);
	int SizeOfShape()
	{
		return shapes.size();
	}

	CLine ShapeIndex(int i)
	{
		return shapes.at(i);
	}

	int getX1Index(int i)
	{
		return x1;
	}
	int getY1Index(int i)
	{
		return y1;
	}
	int getX2Index(int i)
	{
		return x2;
	}
	int getY2Index(int i)
	{
		return y2;
	}
};

