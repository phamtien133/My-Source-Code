#pragma once
class PieChart
{
private:
	int _x;				//To�a ��� t�m
	int _y;				//To�a ��� t�m
	DWORD _radius;		//Ba�n ki�nh
	float _startAngle;	//go�c ban ���u
	float _sweepAngle;	//go�c quay cho qua�t

public:
	PieChart()
	{
		_x = 600;
		_y = 10;
		_radius = 80;
		_startAngle = 0;
		_sweepAngle = 0;
	};
	PieChart(int x, int y)
	{
		_x = x;
		_y = y;
		_radius = 80;
		_startAngle = 0;
		_sweepAngle = 0;
	};

	int getX()
	{
		return _x;
	};

	int getY()
	{
		return _y;
	};

	DWORD getR()
	{
		return _radius;
	};
	float getStartAngle()
	{
		return _startAngle;
	};
	float getSweepAngle()
	{
		return _sweepAngle;
	};

	void setX(int x)
	{
		_x = x;
	};

	void setY(int y)
	{
		_y = y;
	};

	void setR(DWORD radius)
	{
		_radius = radius;
	};

	void setStartAngle(float startAngle)
	{
		_startAngle = startAngle;
	};

	void setSweepAngle(float sweepAngle)
	{
		_sweepAngle = sweepAngle;
	};


};

