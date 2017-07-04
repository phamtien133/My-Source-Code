#pragma once
#include "stdafx.h"
class ListOfNotes
{
private:
	std::vector <DataOfNotes> _ListNote;
public:
	ListOfNotes();
	~ListOfNotes();

	//Thêm môòt phâÌn týÒ vaÌo danh saìch note
	void PushItem(DataOfNotes Item)
	{
		_ListNote.push_back(Item);
	};
	//xoìa
	void ClearItem()
	{
		_ListNote.clear();
	};
	//Lâìy sôì lýõòng phâÌn týÒ cuÒa danh saìch note
	int getSize()
	{
		return _ListNote.size();
	};

	//Lâìy Tag thýì i
	std::wstring getListAt(int i)
	{
		return _ListNote.at(i).getTag();
	};

	//Lâìy Content thýì i
	std::wstring getNoteAt(int i)
	{
		return _ListNote.at(i).getContent();
	};

	//Ghi file
	void writeFile()
	{
		std::string FileName;
		int ID = _ListNote.size();

		//xýÒ lyì utf8
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

	//Ðoòc file
	void ReadFile()
	{
		//xýÒ lyì utf8
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
			//Lâìy doÌng text
			getline(myFile, Infomation);
			if (Infomation != L"")
			{
				
				//TiÌm viò triì cuÒa dâìu , ðâÌu tiên
				int FirstPost = Infomation.find_first_of(':');
				//Gaìn cho Loaòi chi tiêu
				Temp.setTag(Infomation.substr(0, FirstPost));
				//Gaìn cho nôòi dung
				Temp.setContent(Infomation.substr(FirstPost + 2, Infomation.length()));
				
				_ListNote.push_back(Temp);
			}
		}
	};
};