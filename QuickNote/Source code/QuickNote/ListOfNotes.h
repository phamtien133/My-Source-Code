#pragma once
#include "stdafx.h"
class ListOfNotes
{
private:
	std::vector <DataOfNotes> _ListNote;
public:
	ListOfNotes();
	~ListOfNotes();

	//Th�m m��t ph��n t�� va�o danh sa�ch note
	void PushItem(DataOfNotes Item)
	{
		_ListNote.push_back(Item);
	};
	//xo�a
	void ClearItem()
	{
		_ListNote.clear();
	};
	//L��y s�� l���ng ph��n t�� cu�a danh sa�ch note
	int getSize()
	{
		return _ListNote.size();
	};

	//L��y Tag th�� i
	std::wstring getListAt(int i)
	{
		return _ListNote.at(i).getTag();
	};

	//L��y Content th�� i
	std::wstring getNoteAt(int i)
	{
		return _ListNote.at(i).getContent();
	};

	//Ghi file
	void writeFile()
	{
		std::string FileName;
		int ID = _ListNote.size();

		//x�� ly� utf8
		const std::locale empty_locale = std::locale::empty();
		typedef std::codecvt_utf8<wchar_t> converter_type;
		const converter_type* converter = new converter_type;
		const std::locale utf8_locale = std::locale(empty_locale, converter);	

		FileName = "ALLOFNOTE.txt";
		std::wofstream myFile(FileName);
		myFile.imbue(utf8_locale);

		for (int i = 0; i < _ListNote.size(); i++)
		{
			myFile << _ListNote.at(i).getTag() << ": "<< _ListNote.at(i).getContent() << endl;
		}
	};

	//�o�c file
	void ReadFile()
	{
		//x�� ly� utf8
		const std::locale empty_locale = std::locale::empty();
		typedef std::codecvt_utf8<wchar_t> converter_type;
		const converter_type* converter = new converter_type;
		const std::locale utf8_locale = std::locale(empty_locale, converter);		
		//
		DataOfNotes Temp;
		wstring Infomation;
		wifstream myFile("ALLOFNOTE.txt");
		myFile.imbue(utf8_locale);
		

		while (!myFile.eof())
		{
			//L��y do�ng text
			getline(myFile, Infomation);
			if (Infomation != L"")
			{
				
				//Ti�m vi� tri� cu�a d��u , ���u ti�n
				int FirstPost = Infomation.find_first_of(':');
				//Ga�n cho Loa�i chi ti�u
				Temp.setTag(Infomation.substr(0, FirstPost));
				//Ga�n cho n��i dung
				Temp.setContent(Infomation.substr(FirstPost + 2, Infomation.length()));
				
				_ListNote.push_back(Temp);
			}
		}
	};
};