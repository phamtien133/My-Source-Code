#include "stdafx.h"
#include "Shape.h"


CShape::CShape()
{
}


CShape::~CShape()
{
}


void CShape::Push_Shape(CShape* Item)
{
	shapes.push_back(Item);
}

