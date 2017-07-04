#pragma once
#include "stdafx.h"
class Tags
{
private:
	wstring _Tag;
	int _Count;
public:
	//caìc phýõng thýìc get, set
	void setTag(wstring Tag)
	{
		_Tag = Tag;
	};
	void setCount(int Count)
	{
		_Count = Count;
	};

	wstring getTag()
	{
		return _Tag;
	};
	int getCount()
	{
		return _Count;
	};

};

class DataOfTags
{
private:
	vector<Tags> _ListTag;
	vector<wstring> _AllOfTag;
public:
	DataOfTags();
	~DataOfTags();
	//Thao taìc
	bool isEqual(const std::wstring& first, const std::wstring& second)
	{
		if(first.size() != second.size())
			return false;
		for(std::wstring::size_type i = 0; i < first.size(); i++)
		{
			if(first[i] != second[i] && first[i] != (second[i] ^ 32))
				return false;
		}
		return true;
	};
	wstring seperateTagFromNote(wstring Note)		//Taìch týÌ note danh saìch caìc tag (File: tag1,tag2,tag3,...:Content)
	{
		int Post = Note.find_first_of(':');
		return Note.substr(0, Post);
	};

	wstring ItemAt(int i)
	{
		return _ListTag.at(i).getTag();
	};

	void PushItem(Tags Item)
	{
		_ListTag.push_back(Item);
	};

	int ListSize()
	{
		return _ListTag.size();
	};

	void writeFile()				//Ghi file
	{
		string FileName;
		//xýÒ lyì utf8
		const std::locale empty_locale = std::locale::empty();
		typedef std::codecvt_utf8<wchar_t> converter_type;
		const converter_type* converter = new converter_type;
		const std::locale utf8_locale = std::locale(empty_locale, converter);

		FileName = "LISTTAG.txt";
		std::wofstream myFile(FileName);
		myFile.imbue(utf8_locale);

		for (int i = 0; i < _ListTag.size(); i++)
		{
			//Ghi
			myFile << _ListTag.at(i).getTag() << endl;
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
		wstring Infomation;
		Tags Temp;
		wifstream myFile("LISTTAG.txt");
		myFile.imbue(utf8_locale);
		while (!myFile.eof())
		{
			//Lâìy doÌng text
			getline(myFile, Infomation);
			if (Infomation != L"")
			{			
				Temp.setTag(Infomation);
				Temp.setCount(0);
				_ListTag.push_back(Temp);
			}
		}
	};
	//Xýì lyì võìi tâìt caÒ caìc tag
	wstring AllItemAt(int i)
	{
		return _AllOfTag.at(i);
	};

	void PushAllItem(wstring Item)
	{
		int Post = 0;
		for(int i = 0; i < Item.length(); i++)
		{
			if(Item[i] == ',')
			{
				wstring Temp2;
				Temp2 = Item.substr(Post, i-Post);
				Post = i + 1;
				_AllOfTag.push_back(Temp2);
			}
		}
		_AllOfTag.push_back(Item.substr(Post, Item.length() - Post));
	};

	int AllListSize()
	{
		return _AllOfTag.size();
	};

	void writeAllTag()				//Ghi file
	{
		string FileName;
		//xýÒ lyì utf8
		const std::locale empty_locale = std::locale::empty();
		typedef std::codecvt_utf8<wchar_t> converter_type;
		const converter_type* converter = new converter_type;
		const std::locale utf8_locale = std::locale(empty_locale, converter);

		FileName = "ALLTAG.txt";
		std::wofstream myFile(FileName);
		myFile.imbue(utf8_locale);

		for (int i = 0; i < _AllOfTag.size(); i++)
		{
			//Ghi
			myFile << _AllOfTag.at(i) << endl;
		}
	};
	void ReadAllTag()
	{
		//xýÒ lyì utf8
		const std::locale empty_locale = std::locale::empty();
		typedef std::codecvt_utf8<wchar_t> converter_type;
		const converter_type* converter = new converter_type;
		const std::locale utf8_locale = std::locale(empty_locale, converter);		
		//
		wstring Infomation;
		wifstream myFile("ALLTAG.txt");
		myFile.imbue(utf8_locale);
		

		while (!myFile.eof())
		{
			//Lâìy doÌng text
			getline(myFile, Infomation);
			if (Infomation != L"")
			{				
				_AllOfTag.push_back(Infomation);
			}
		}
	};
	int CountExist(int i)
	{
		int TempCount = 0;
		for(int j = 0; j < _AllOfTag.size(); j++)
		{
			if(isEqual(_ListTag.at(i).getTag(), _AllOfTag.at(j)) == TRUE)
			{
				TempCount++;
			}
		}
		_ListTag.at(i).setCount(TempCount);
		return TempCount;
	};

	void Sort()
	{
		wstring Temp;
		int Count;
		for (int i = 0; i < _ListTag.size() - 1; i++)
		{
			for(int j = i + 1; j < _ListTag.size(); j++)
			{
				if(_ListTag.at(i).getCount() < _ListTag.at(j).getCount())
				{
					//swap Tag
					Temp = _ListTag.at(j).getTag();
					_ListTag.at(j).setTag(_ListTag.at(i).getTag());
					_ListTag.at(i).setTag(Temp);
					//swap Count
					Count = _ListTag.at(j).getCount();
					_ListTag.at(j).setCount(_ListTag.at(i).getCount());
					_ListTag.at(i).setCount(Count);
				}
			}
		}
	}
};

