﻿#include "stdafx.h"
#include "Line.h"


CLine::CLine()
{
}


CLine::~CLine()
{
}

void CLine::Draw(HDC hdc) {
	MoveToEx(hdc, x1, y1, NULL);
	LineTo(hdc, x2, y2);
}



void CLine::SetData(int a, int b, int c, int d) {
	x1 = a;
	y1 = b;
	x2 = c;
	y2 = d;
}
void CLine::Push_Shape(CLine Item)
{
	shapes.push_back(Item);
}
void CLine::ReadFile(char* FileName)
{
	CLine line;
	char Temp = NULL;
	string Infomation;
	ifstream myFile(FileName, ios::binary);
	if (myFile.is_open())
	{
		while (!myFile.eof())
		{
			getline(myFile, Infomation);
			//string Infomation = to_string(Temp);
			if (Infomation != "")
			{

				//Tìm vị trí của dấu , đầu tiên
				int FirstPost = Infomation.find_first_of(',');
				//Gán x1

				x1 = stoi(Infomation.substr(0, FirstPost));

				//Tìm dấu , kế tiếp
				int NextPost = Infomation.find(',', FirstPost + 1);
				//Gán y1
				y1 = stoi(Infomation.substr(FirstPost + 1, NextPost - FirstPost - 1));

				//Tìm dấu , cuối cùng
				int LastPost = Infomation.find_last_of(',');
				//Gán cho x2
				x2 = stoi(Infomation.substr(NextPost + 1, LastPost - NextPost - 1));

				//Gán cho y2
				y2 = stoi(Infomation.substr(LastPost + 1, Infomation.length() - LastPost));
				//Thêm 1 dòng vào vector danh sách hình vẽ
				line.SetData(x1, y1, x2, y2);
				shapes.push_back(line);
			}

		}
	}
	else
	{
		return;
	}
}
void CLine::WriteFile(char* FileName)
{
	ofstream myFile(FileName, ios::binary);
	for (int i = 0; i < shapes.size(); i++)
	{		
		
		myFile << to_string(shapes.at(i).x1) << "," << to_string(shapes.at(i).y1) << "," << to_string(shapes.at(i).x2) <<  "," << to_string(shapes.at(i).y2) << endl;
		//myFile << a << "," << b << "," << c << "," << d << endl;
	}
}