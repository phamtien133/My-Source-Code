#pragma once
#pragma once
#include "resource.h"

class DataOfNotes
{
private:
	std::wstring _Tag;						//Tag cuÒa note
	std::wstring _Content;					//Nôòi dung cuÒa note
	int _NoteCount;							//Dıò tiình seŞ duÌng ğêÒ ğêìm sôì lâÌn xuâìt hiêòn
public:
	DataOfNotes();
	~DataOfNotes();
	//Caìc phıõng thıìc set, get
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
	
	//Lâìy Tag, Content tıÌ maÌn hiÌnh
	LPWSTR getWindowTag(HWND hWnd) //Lâìy Tag
	{
		LPWSTR Temp = new WCHAR[10000];
		GetDlgItemTextW(hWnd, AN_TB_TAG, Temp, 10000);
		return Temp;
	}
	LPWSTR getWindowContent(HWND hWnd) //Lâìy Content
	{
		LPWSTR Temp = new WCHAR[10000];
		GetDlgItemTextW(hWnd, AN_TB_CONTENT, Temp, 10000);
		return Temp;
	}
	
};


