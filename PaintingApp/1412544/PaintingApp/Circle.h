#pragma once
#include "Shape.h"
class CCircle
{
public:
	int x1;
	int y1;
	int x2;
	int y2;
	vector<CCircle> shapes;
public:
	CCircle();
	~CCircle();
	void Draw(HDC hdc);
	CCircle ShapeIndex(int i)
	{
		return shapes.at(i);
	}
	int SizeOfShape()
	{
		return shapes.size();
	}
	void Push_Shape(CCircle Item)
	{
		shapes.push_back(Item);
	}
	void SetData(int a, int b, int c, int d);
	void ReadFile(char* FileName)
	{
		CCircle line;
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
	void WriteFile(char* FileName)
	{
		ofstream myFile(FileName, ios::binary);
		for (int i = 0; i < shapes.size(); i++)
		{

			myFile << to_string(shapes.at(i).x1) << "," << to_string(shapes.at(i).y1) << "," << to_string(shapes.at(i).x2) << "," << to_string(shapes.at(i).y2) << endl;
			//myFile << a << "," << b << "," << c << "," << d << endl;
		}
	}
};

