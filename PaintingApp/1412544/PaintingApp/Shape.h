#pragma once
#include <vector>
#include <fstream>
#include <iostream>
#include <string>
using namespace std;
class CShape
{
public:
	int x1;
	int y1;
	int x2;
	int y2;
	vector<CShape*> shapes;
public:
	CShape();
	~CShape();
	virtual void Draw(HDC hdc) = 0;
	virtual CShape* Create() = 0;
	virtual void SetData(int a, int b, int c, int d) = 0;
	void ReadFile(char * FileName);
	void Push_Shape(CShape* Item);
	int SizeOfShape()
	{
		return shapes.size();
	}



	CShape* ShapeIndex(int i)
	{
		return shapes.at(i);
	}

	int getX1Index()
	{
		return x1;
	}
	int getY1Index()
	{
		return y1;
	}
	int getX2Index()
	{
		return x2;
	}
	int getY2Index()
	{
		return y2;
	}

};

