#pragma once
class PieChart
{
private:
	int _x;				//Toòa ðôò tâm
	int _y;				//Toòa ðôò tâm
	DWORD _radius;		//Baìn kiình
	float _startAngle;	//goìc ban ðâÌu
	float _sweepAngle;	//goìc quay cho quaòt

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

