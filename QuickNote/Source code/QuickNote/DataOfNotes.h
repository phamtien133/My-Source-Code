#pragma once
#pragma once
#include "resource.h"

class DataOfNotes
{
private:
	std::wstring _Tag;						//Tag cu�a note
	std::wstring _Content;					//N��i dung cu�a note
	int _NoteCount;							//D�� ti�nh se� du�ng ��� ���m s�� l��n xu��t hi��n
public:
	DataOfNotes();
	~DataOfNotes();
	//Ca�c ph��ng th��c set, get
	void setTag(std::wstring Tag)
	{
		_Tag = Tag;
	};
	void setContent(std::wstring Content)
	{
		_Content = Content;
	};

	std::wstring getTag()
	{
		return _Tag;
	}
	std::wstring getContent()
	{
		return _Content;
	};
	
	//L��y Tag, Content t�� ma�n hi�nh
	LPWSTR getWindowTag(HWND hWnd) //L��y Tag
	{
		LPWSTR Temp = new WCHAR[10000];
		GetDlgItemTextW(hWnd, AN_TB_TAG, Temp, 10000);
		return Temp;
	}
	LPWSTR getWindowContent(HWND hWnd) //L��y Content
	{
		LPWSTR Temp = new WCHAR[10000];
		GetDlgItemTextW(hWnd, AN_TB_CONTENT, Temp, 10000);
		return Temp;
	}
	
};


