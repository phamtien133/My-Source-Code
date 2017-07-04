// QuickNote.cpp : Defines the entry point for the application.
//

#include "stdafx.h"
#include "QuickNote.h"

#define MAX_LOADSTRING 100
//define color
#define Red (RGB(255,0,0))
#define Green (RGB(0,255,0))
#define Blue (RGB(0,0,255))
#define Yellow (RGB(255,255,0))
#define Orange (RGB(255,125,0))
#define Cyan (RGB(0,255,255))
// Global Variables:
HINSTANCE hInst;											// current instance
TCHAR szTitle[MAX_LOADSTRING];								// The title bar text
TCHAR szWindowClass[MAX_LOADSTRING];						// the main window class name
NOTIFYICONDATA	nData;										// notify icon data - Library: shellapi.h
HWND hwnd;													//HWND global for main window
HWND hwndAddNote;											//HWND for add note window
HWND hwndViewNote;											//HWND for view note window
HWND hwndTagAndContent;										//HWND for tag and content window
HWND hwndListView;											//ListView on View Note Window
HWND hwndListTagAndContent;									//Listview on tag and content window
HWND hwndInfoContent;										//Listview on info content window
HWND hwndViewStatistics;									//HWND for view statistics window
HWND hwndCombo;												//HWND for combo box tag suggest
int postChoose;
int postChoose2;
ListOfNotes Handling;
DataOfTags ListTag;			
Tags seperateTag;
PieChart piePart;

DataOfNotes TempTagAndContent;
ListOfNotes showTagAndContent;

// Forward declarations of functions included in this code module:
ATOM				MyRegisterClass(HINSTANCE hInstance);
BOOL				InitInstance(HINSTANCE, int);
LRESULT CALLBACK	WndProc(HWND, UINT, WPARAM, LPARAM);
INT_PTR CALLBACK	About(HWND, UINT, WPARAM, LPARAM);

void ShowMenuNotifyIcon(HWND hWnd);							//Menu in Notify icon							
int OpenAddNote(HINSTANCE hInstance, int nCmdShow);			//Open Dialog Add Note	
int drawPieChart(HINSTANCE hInstance, int nCmdShow, HWND hWnd, HDC &hdc, int x, int y); ////Draw pie chart
void rePaint(HWND hWnd);//VeÞ laòi biêÒu ðôÌ
//So saình 2 wstring
bool isequal(const std::wstring& first, const std::wstring& second)
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
INT_PTR CALLBACK AddNote(HWND hDlg, UINT message, WPARAM wParam, LPARAM lParam);//Dialog Add Note
INT_PTR CALLBACK ViewNote(HWND hDlg, UINT message, WPARAM wParam, LPARAM lParam);////Dialog View Note
INT_PTR CALLBACK TagAndContent(HWND hDlg, UINT message, WPARAM wParam, LPARAM lParam);//Dialog tag and content
INT_PTR CALLBACK InfoContent(HWND hDlg, UINT message, WPARAM wParam, LPARAM lParam);//Dialog infomation content
INT_PTR CALLBACK ViewStatistics(HWND hDlg, UINT message, WPARAM wParam, LPARAM lParam);//Dialog View Statistics
int APIENTRY _tWinMain(_In_ HINSTANCE hInstance,
                     _In_opt_ HINSTANCE hPrevInstance,
                     _In_ LPTSTR    lpCmdLine,
                     _In_ int       nCmdShow)
{
	UNREFERENCED_PARAMETER(hPrevInstance);
	UNREFERENCED_PARAMETER(lpCmdLine);

 	// TODO: Place code here.
	MSG msg;
	HACCEL hAccelTable;

	// Initialize global strings
	LoadString(hInstance, IDS_APP_TITLE, szTitle, MAX_LOADSTRING);
	LoadString(hInstance, IDC_QUICKNOTE, szWindowClass, MAX_LOADSTRING);
	MyRegisterClass(hInstance);

	//Hook key ALT + Space
	BOOL KeyHook = RegisterHotKey(NULL, 544, MOD_CONTROL | MOD_NOREPEAT, VK_SPACE);
	//Ðoòc file
	Handling.ReadFile();
	ListTag.ReadFile();
	ListTag.ReadAllTag();
	// Perform application initialization:
	if (!InitInstance (hInstance, nCmdShow))
	{
		return FALSE;
	}

	hAccelTable = LoadAccelerators(hInstance, MAKEINTRESOURCE(IDC_QUICKNOTE));

	// Main message loop:
	while (GetMessage(&msg, NULL, 0, 0))
	{
		if (!TranslateAccelerator(msg.hwnd, hAccelTable, &msg))
		{
			TranslateMessage(&msg);
			DispatchMessage(&msg);
		}
		if (msg.message == WM_HOTKEY)
		{
			OpenAddNote(hInst, SW_RESTORE);
		}
	}

	return (int) msg.wParam;
}



//
//  FUNCTION: MyRegisterClass()
//
//  PURPOSE: Registers the window class.
//
ATOM MyRegisterClass(HINSTANCE hInstance)
{
	WNDCLASSEX wcex;

	wcex.cbSize = sizeof(WNDCLASSEX);

	wcex.style			= CS_HREDRAW | CS_VREDRAW;
	wcex.lpfnWndProc	= WndProc;
	wcex.cbClsExtra		= 0;
	wcex.cbWndExtra		= 0;
	wcex.hInstance		= hInstance;
	wcex.hIcon			= LoadIcon(hInstance, MAKEINTRESOURCE(IDI_QUICKNOTE));
	wcex.hCursor		= LoadCursor(NULL, IDC_ARROW);
	wcex.hbrBackground	= (HBRUSH)(COLOR_WINDOW+1);
	wcex.lpszMenuName	= MAKEINTRESOURCE(IDC_QUICKNOTE);
	wcex.lpszClassName	= szWindowClass;
	wcex.hIconSm		= LoadIcon(wcex.hInstance, MAKEINTRESOURCE(IDI_SMALL));

	return RegisterClassEx(&wcex);
}

//
//   FUNCTION: InitInstance(HINSTANCE, int)
//
//   PURPOSE: Saves instance handle and creates main window
//
//   COMMENTS:
//
//        In this function, we save the instance handle in a global variable and
//        create and display the main program window.
//
BOOL InitInstance(HINSTANCE hInstance, int nCmdShow)
{
   HWND hWnd;

   hInst = hInstance; // Store instance handle in our global variable

   hWnd = CreateWindow(szWindowClass, szTitle, WS_OVERLAPPEDWINDOW,
      CW_USEDEFAULT, 0, CW_USEDEFAULT, 0, NULL, NULL, hInstance, NULL);

   if (!hWnd)
   {
      return FALSE;
   }

   //Notify Icon
   ZeroMemory(&nData, sizeof(NOTIFYICONDATA)); //nData = NULL
   nData.cbSize = sizeof(NOTIFYICONDATA);

   // set ID number for the tray icon
   nData.uID = TRAYICONID;
   // state which structure members are valid
   nData.uFlags = NIF_ICON | NIF_MESSAGE | NIF_TIP;
   // load the icon
   nData.hIcon = (HICON)LoadImage(hInstance, MAKEINTRESOURCE(IDI_QUICKNOTE),
	   IMAGE_ICON, GetSystemMetrics(SM_CXSMICON), GetSystemMetrics(SM_CYSMICON),
	   LR_DEFAULTCOLOR);

   // the window to send messages to and the message to send
   //		note:	the message value should be in the
   //				range of WM_APP through 0xBFFF
   nData.hWnd = hWnd;
   nData.uCallbackMessage = WM_NOTIFYICON;
   // tooltip message
   Shell_NotifyIcon(NIM_ADD, &nData);

   // free icon handle
   if (nData.hIcon && DestroyIcon(nData.hIcon))
   {
	   nData.hIcon = NULL;
   }
	
   ShowWindow(hWnd, 0);
   UpdateWindow(hWnd);

   return TRUE;
}

//
//  FUNCTION: WndProc(HWND, UINT, WPARAM, LPARAM)
//
//  PURPOSE:  Processes messages for the main window.
//
//  WM_COMMAND	- process the application menu
//  WM_PAINT	- Paint the main window
//  WM_DESTROY	- post a quit message and return
//
//
LRESULT CALLBACK WndProc(HWND hWnd, UINT message, WPARAM wParam, LPARAM lParam)
{
	int wmId, wmEvent;
	PAINTSTRUCT ps;
	HDC hdc;

	switch (message)
	{
	case WM_NOTIFY:
		  {
		  }
		break;
	case WM_NOTIFYICON:
	{
		switch (lParam)
		{
		case WM_RBUTTONDOWN:
		case WM_CONTEXTMENU:
			ShowMenuNotifyIcon(hWnd);
		}
	}
	break;
	case WM_COMMAND:
		wmId    = LOWORD(wParam);
		wmEvent = HIWORD(wParam);
		// Parse the menu selections:
		switch (wmId)
		{
		case NI_EXIT:		//Case Exit on notify icon
		{
			int MBAN = MessageBox(hwnd, L"Baòn coì thâòt sýò muôìn thoaìt?", L"Thông baìo", MB_YESNO | MB_ICONQUESTION);
			switch (MBAN)
			{
			case IDYES:
				DestroyWindow(hWnd);
				break;
			case IDNO:
				//do nothing
				break;
			}
		}
		break;
		case NI_VIEW_NOTES:		//case Open view note on notify icon
		{
			DialogBox(hInst, MAKEINTRESOURCE(IDD_DIALOGVIEWNOTE), hWnd, ViewNote);
		}
		break;
		case NI_VIEW_STATISTICS:		//case Open view statistics on notify icon
		{
			DialogBox(hInst, MAKEINTRESOURCE(IDD_VIEWSTATISTICS), hWnd, ViewStatistics);
		}
		break;
		case IDM_ABOUT:
			DialogBox(hInst, MAKEINTRESOURCE(IDD_ABOUTBOX), hWnd, About);
			break;
		case IDM_EXIT:
			DestroyWindow(hWnd);
			break;
		default:
			return DefWindowProc(hWnd, message, wParam, lParam);
		}
		break;
	case WM_PAINT:
		hdc = BeginPaint(hWnd, &ps);
		// TODO: Add any drawing code here...
		EndPaint(hWnd, &ps);
		break;
	case WM_DESTROY:
		PostQuitMessage(0);
		break;
	default:
		return DefWindowProc(hWnd, message, wParam, lParam);
	}
	return 0;
}

// Message handler for about box.
INT_PTR CALLBACK About(HWND hDlg, UINT message, WPARAM wParam, LPARAM lParam)
{
	UNREFERENCED_PARAMETER(lParam);
	switch (message)
	{
	case WM_INITDIALOG:
		return (INT_PTR)TRUE;

	case WM_COMMAND:
		if (LOWORD(wParam) == IDOK || LOWORD(wParam) == IDCANCEL)
		{
			EndDialog(hDlg, LOWORD(wParam));
			return (INT_PTR)TRUE;
		}
		break;
	}
	return (INT_PTR)FALSE;
}

//Menu in Notify icon
void ShowMenuNotifyIcon(HWND hWnd)
{
	POINT pt;
	GetCursorPos(&pt);
	HMENU hMenu = CreatePopupMenu();
	if (hMenu)
	{
		InsertMenu(hMenu, -1, MF_BYPOSITION, NI_VIEW_NOTES, _T("View Notes"));
		InsertMenu(hMenu, -1, MF_BYPOSITION, NI_VIEW_STATISTICS, _T("View Statitics"));
		InsertMenu(hMenu, -1, MF_BYPOSITION, NI_EXIT, _T("Exit"));
		SetForegroundWindow(hWnd);
		TrackPopupMenu(hMenu, TPM_BOTTOMALIGN,
			pt.x, pt.y, 0, hWnd, NULL);
		DestroyMenu(hMenu);
	}
}

//Open Dialog Add Note
int OpenAddNote(HINSTANCE hInstance, int nCmdShow)
{
	DialogBox(hInst, MAKEINTRESOURCE(IDD_DIALOGADDNOTE), hwnd, AddNote);
	return 1;
}

//Dialog Add Note
INT_PTR CALLBACK AddNote(HWND hDlg, UINT message, WPARAM wParam, LPARAM lParam)
{
	hwndAddNote = hDlg;
	UNREFERENCED_PARAMETER(lParam);
	switch (message)
	{
	case WM_INITDIALOG:
	{
		//Taòo combobox Loaòi chi tiêu
		CreateWindowEx(0, L"STATIC", L"Tag gõòi yì", WS_CHILD | WS_VISIBLE | SS_LEFT, 67, 60, 100, 20, hwndAddNote, NULL, hInst, NULL);
		hwndCombo = CreateWindow(TEXT("COMBOBOX"), TEXT(""), CBS_DROPDOWN | CBS_HASSTRINGS | WS_CHILD | WS_OVERLAPPED | WS_VISIBLE | LBS_NOTIFY, 147, 60, 120, 150, hwndAddNote, NULL, hInst, NULL);
		//LBS_NOTIFY: Không cho chiÒnh text trên ComboBox
		//Thêm item cho combobox loaòi chi tiêu
		for (int i = 0; i < ListTag.ListSize(); i++)
		{
			SendMessage(hwndCombo, (UINT)CB_ADDSTRING , (WPARAM) 0, (LPARAM)ListTag.ItemAt(i).c_str());			
		}

		//HiêÒn thiò trên combo box laÌ item thýì 0
		SendMessage(hwndCombo, CB_SETCURSEL, (WPARAM)0, (LPARAM)0);
	}
	return (INT_PTR)TRUE;

	case WM_COMMAND:
		if (LOWORD(wParam) == IDANCANCEL)
		{
			int MBAN = MessageBox(hwnd, L"Baòn coì thâòt sýò muôìn thoaìt?", L"Thông baìo", MB_YESNO | MB_ICONQUESTION);
			switch (MBAN)
			{
			case IDYES:
				EndDialog(hDlg, LOWORD(wParam));
				break;
			case IDNO:
				//do nothing
				break;
			}
			return (INT_PTR)TRUE;
		}
		if (LOWORD(wParam) == IDANADDTAG)
		{
			DataOfNotes Temp;
			WCHAR* bufTagSuggest = NULL;
			int sizeTagSuggest;
			//Lâìy dýÞ liêòu Loaòi chi tiêu
			sizeTagSuggest = GetWindowTextLength(hwndCombo);
			bufTagSuggest = new WCHAR[sizeTagSuggest + 1];
			GetWindowText(hwndCombo, bufTagSuggest, sizeTagSuggest + 1);
			wstring TagSuggest = bufTagSuggest;
			if(isequal(TagSuggest, L"") == 1)
			{
				MessageBox(hwndAddNote, L"Không coì Tag gõòi yì", L"Thông baìo", 1);
			}
			else
			{
				TagSuggest = Temp.getWindowTag(hwndAddNote) + TagSuggest + L",";
				SetDlgItemTextW(hwndAddNote, AN_TB_TAG, TagSuggest.c_str());
			}
			break;
			return (INT_PTR)TRUE;
		}
		if (LOWORD(wParam) == IDANSAVE)
		{
			////Save tag and note
			DataOfNotes Temp;
			wstring Tag;
			wstring AllTag;
			Tags TempTag;
			int isSave = 0;
			//Temp.getDataNote(hwndAddNote);
			vector<wstring> seperateTag;
			//KiêÒm tra hõòp lêò
			if(isequal(Temp.getWindowTag(hwndAddNote), L"") == 1)
			{
				MessageBox(hwndAddNote, L"Không ðýõòc ðêÒ trôìng Tag", L"Thông baìo", 1);
			}
			else if(isequal(Temp.getWindowContent(hwndAddNote), L"") == 1)
			{
				MessageBox(hwndAddNote, L"Không ðýõòc ðêÒ trôìng Content", L"Thông baìo", 1);
			}
			else
			{
				Temp.setTag(Temp.getWindowTag(hwndAddNote));
				Temp.setContent(Temp.getWindowContent(hwndAddNote));
				Tag = Temp.getWindowTag(hwndAddNote);
				AllTag = Temp.getWindowTag(hwndAddNote);
				ListTag.PushAllItem(AllTag);
			
				//So saình			
				int Post = 0;
				for(int i = 0; i < Tag.length(); i++)
				{
					if(Tag[i] == ',')
					{
						bool isExist = FALSE;		//Biêìn kiêÒm tra truÌng
						//wstring Temp2;
						TempTag.setTag(Tag.substr(Post, i-Post));
						Post = i + 1;
						for(int j = 0; j < ListTag.ListSize(); j++)
						{
							if(isequal(TempTag.getTag(), ListTag.ItemAt(j)) == TRUE)
							{
								isExist = TRUE;
							}
						}
						if(isExist == FALSE)
						{
							ListTag.PushItem(TempTag);
						}
					}
				}
				bool isExist2 = FALSE;
				for(int j = 0; j < ListTag.ListSize(); j++)
				{
					if(isequal(Tag.substr(Post, Tag.length() - Post), ListTag.ItemAt(j)) == TRUE)
					{
						isExist2 = TRUE;
					}
				}
				if(isExist2 == FALSE)
				{
					TempTag.setTag(Tag.substr(Post, Tag.length() - Post));
					ListTag.PushItem(TempTag);
				}

				//ListTag.PushItem(Tag);
				for(int i = 0; i < ListTag.ListSize(); i++)
				{
					ListTag.CountExist(i);
				}
				ListTag.Sort();
				ListTag.writeFile();
				ListTag.writeAllTag();

				Handling.PushItem(Temp);
				Handling.writeFile();
				MessageBox(hwndAddNote, L"Lýu thaÌnh công!", L"Thông baìo", 1);
				InvalidateRect(hwndAddNote, NULL, TRUE);
				UpdateWindow(hwndAddNote);
			}
			return (INT_PTR)TRUE;
		}
		break;
	}
	return (INT_PTR)FALSE;
}

//Dialog View Note
INT_PTR CALLBACK ViewNote(HWND hDlg, UINT message, WPARAM wParam, LPARAM lParam)
{
	hwndViewNote = hDlg;
	UNREFERENCED_PARAMETER(lParam);
	switch (message)
	{
		case WM_NOTIFY:
		  {

			switch (((LPNMHDR)lParam)->code)
			{
			case NM_DBLCLK:
				{
					int lbItem = ListView_GetNextItem(hwndListView, -1, LVNI_SELECTED);
					postChoose = lbItem;
					EndDialog(hDlg,LOWORD(wParam));
					DialogBox(hInst, MAKEINTRESOURCE(IDD_DIALOGVIEWCONTENT), hwnd, TagAndContent);
				}
			  break;

			}
		  }
		break;
	case WM_INITDIALOG:
	{
		NMHDR MessageStructure;
		hwndListView = CreateWindowEx(0, WC_LISTVIEW, L"List of notes", WS_CHILD | WS_VISIBLE | WS_VSCROLL |WS_BORDER | LVS_REPORT,20,20, 250, 200,hDlg, NULL,hInst,NULL);
		ListView_SetExtendedListViewStyle(hwndListView,LVS_EX_GRIDLINES | LVS_EX_FULLROWSELECT);
		SetWindowLong(hwndListView, GWL_STYLE, GetWindowLong(hwndListView, GWL_STYLE) | LVS_REPORT);
		
		LVCOLUMN  lvCol1, lvCol2, lvCol3;
		lvCol1.mask = LVCF_FMT | LVCF_TEXT | LVCF_WIDTH;
		lvCol1.fmt = LVCFMT_CENTER;
		//
		LPNMLVCUSTOMDRAW  lplvcd = (LPNMLVCUSTOMDRAW)lParam;
		//
		
		lvCol1.cx = 50;
		lvCol1.pszText = _T("STT");
		ListView_InsertColumn(hwndListView, 0, &lvCol1);
		lvCol2.mask = LVCF_FMT | LVCF_TEXT | LVCF_WIDTH;
		lvCol2.fmt = LVCFMT_LEFT;
		lvCol2.cx = 100;
		lvCol2.pszText = _T("Tag");
		ListView_InsertColumn(hwndListView, 1, &lvCol2);
		lvCol3.mask = LVCF_FMT | LVCF_TEXT | LVCF_WIDTH;
		lvCol3.fmt = LVCFMT_CENTER;
		lvCol3.cx = 100;
		lvCol3.pszText = _T("Sôì ghi chuì");
		ListView_InsertColumn(hwndListView, 2, &lvCol3);

		//Thêm item cho ListView
		for (int index2 = 0;index2 < ListTag.ListSize(); index2++)
		{
			//Thêm item
			LV_ITEM lv;
			lv.mask = LVIF_TEXT;
			lv.iItem = index2;
			wstring Tmp;
			Tmp = to_wstring(index2 + 1) ;
			lv.iSubItem = 0;
			lv.pszText = (LPWSTR)Tmp.c_str();
			ListView_InsertItem(hwndListView, &lv);

			lv.mask = LVIF_TEXT;
			Tmp = ListTag.ItemAt(index2);
			lv.iSubItem = 1;
			lv.pszText = (LPWSTR)Tmp.c_str();
			ListView_SetItem(hwndListView, &lv);

			lv.mask = LVIF_TEXT;
			Tmp = to_wstring(ListTag.CountExist(index2));
			lv.iSubItem = 2;
			lv.pszText = (LPWSTR)Tmp.c_str();
			ListView_SetItem(hwndListView, &lv);
		}
		 MessageStructure.hwndFrom = hwndListView;
		 MessageStructure.idFrom = LISTVIEWCONTROLEID;
		 MessageStructure.code = NM_DBLCLK;

		 SendMessage(hwndListView, WM_NOTIFY, LISTVIEWCONTROLEID, (LPARAM)&MessageStructure);

		
	}
	return (INT_PTR)TRUE;
	
	case WM_COMMAND:
		if (LOWORD(wParam) == IDVNCANCEL)
		{
			int MBAN = MessageBox(hwnd, L"Baòn coì thâòt sýò muôìn thoaìt?", L"Thông baìo", MB_YESNO | MB_ICONQUESTION);
			switch (MBAN)
			{
			case IDYES:
				EndDialog(hDlg, LOWORD(wParam));
				break;
			case IDNO:
				//do nothing
				break;
			}
			return (INT_PTR)TRUE;
		}
		break;
		
	}
	return (INT_PTR)FALSE;
}

//Dialog tag and content
INT_PTR CALLBACK TagAndContent(HWND hDlg, UINT message, WPARAM wParam, LPARAM lParam)
{
	hwndTagAndContent = hDlg;
	LV_ITEM lv;
	UNREFERENCED_PARAMETER(lParam);
	switch (message)
	{
		case WM_NOTIFY:
		  {
			switch (((LPNMHDR)lParam)->code)
			{
			  case NM_DBLCLK:
				{
					int lbItem = ListView_GetNextItem(hwndListTagAndContent, -1, LVNI_SELECTED);
					postChoose2 = lbItem;
					EndDialog(hDlg,LOWORD(wParam));
					DialogBox(hInst, MAKEINTRESOURCE(IDD_DIALOGVIEWINFOCONTENT), hwnd, InfoContent);
				}
			  break;
			}
		  }
		break;
	case WM_INITDIALOG:
	{
		
		showTagAndContent.ClearItem();
		NMHDR MessageStructure;
		bool isExist = FALSE;
		bool isContinue = TRUE;
		int index = 0;
		int post = 0;
		hwndListTagAndContent = CreateWindowEx(0, WC_LISTVIEW, L"List of notes", WS_CHILD | WS_VISIBLE | WS_VSCROLL |WS_BORDER | LVS_REPORT,20,20, 300, 200,hDlg, NULL,hInst,NULL);
		ListView_SetExtendedListViewStyle(hwndListTagAndContent,LVS_EX_GRIDLINES | LVS_EX_FULLROWSELECT);
		SetWindowLong(hwndListTagAndContent, GWL_STYLE, GetWindowLong(hwndListTagAndContent, GWL_STYLE) | LVS_REPORT);
		
		LVCOLUMN  lvCol1, lvCol2;
		lvCol1.mask = LVCF_FMT | LVCF_TEXT | LVCF_WIDTH;
		lvCol1.fmt = LVCFMT_CENTER;
		lvCol1.cx = 100;
		lvCol1.pszText = _T("Tag");
		ListView_InsertColumn(hwndListTagAndContent, 0, &lvCol1);
		
		lvCol2.mask = LVCF_FMT | LVCF_TEXT | LVCF_WIDTH;
		lvCol2.fmt = LVCFMT_LEFT;
		lvCol2.cx = 200;
		lvCol2.pszText = _T("Nôòi dung");
		ListView_InsertColumn(hwndListTagAndContent, 1, &lvCol2);

		
		
		wstring Tag = ListTag.ItemAt(postChoose);
		wstring clTag;
		wstring clContent;
		wstring TempTag;
		for(int i = 0; i < Handling.getSize(); i++)
		{
			int Post = 0;
			for(int j = 0; j < Handling.getListAt(i).length(); j++)  //getListAt(i) lâìy tag
			{
				if(Handling.getListAt(i).at(j) == ',')
				{
					TempTag = Handling.getListAt(i).substr(Post, j-Post);
					Post = j + 1;
					if(isequal(TempTag, Tag) == TRUE)
					{
						isExist = TRUE;
					}
				}
			}
			TempTag = Handling.getListAt(i).substr(Post, Handling.getListAt(i).length() - Post);
			if(isequal(TempTag,Tag) == TRUE)
			{
				isExist = TRUE;
			}
			if(isExist == TRUE)
			{
				isExist = FALSE;
				TempTagAndContent.setTag(Handling.getListAt(i));
				TempTagAndContent.setContent(Handling.getNoteAt(i));
				showTagAndContent.PushItem(TempTagAndContent);
				post = showTagAndContent.getSize();
			}
		}
		for(int j = showTagAndContent.getSize() - 1; j > -1; j--)
		{
			
			lv.mask = LVIF_TEXT;
			lv.iItem = index;
			wstring Tmp;
			Tmp = showTagAndContent.getListAt(j);
			lv.iSubItem = 0;
			lv.pszText = (LPWSTR)Tmp.c_str();
			
			ListView_InsertItem(hwndListTagAndContent, &lv);

			lv.mask = LVIF_TEXT;
			Tmp = showTagAndContent.getNoteAt(j);
			lv.iSubItem = 1;
			lv.pszText = (LPWSTR)Tmp.c_str();
			ListView_SetItem(hwndListTagAndContent, &lv);
		}
		 MessageStructure.hwndFrom = hwndListTagAndContent;
		 MessageStructure.idFrom = LISTVIEWCONTROLEID;
		 MessageStructure.code = NM_DBLCLK;
		 
		 SendMessage(hwndListTagAndContent, WM_NOTIFY, LISTVIEWCONTROLEID, (LPARAM)&MessageStructure);

		
	}
	return (INT_PTR)TRUE;
	
	case WM_COMMAND:
		if (LOWORD(wParam) == IDTAC_CANCEL)
		{
			int MBAN = MessageBox(hwnd, L"Baòn coì thâòt sýò muôìn thoaìt?", L"Thông baìo", MB_YESNO | MB_ICONQUESTION);
			switch (MBAN)
			{
			case IDYES:
				EndDialog(hDlg, LOWORD(wParam));
				break;
			case IDNO:
				//do nothing
				break;
			}
			return (INT_PTR)TRUE;
		}
		if (LOWORD(wParam) == IDVN_BACK)
		{
			{
				EndDialog(hDlg,LOWORD(wParam));
				DialogBox(hInst, MAKEINTRESOURCE(IDD_DIALOGVIEWNOTE), hwnd, ViewNote);
				break;
			}
			return (INT_PTR)TRUE;
		}
		break;
	}
	return (INT_PTR)FALSE;
}

//Dialog infomation content
INT_PTR CALLBACK InfoContent(HWND hDlg, UINT message, WPARAM wParam, LPARAM lParam)
{
	hwndInfoContent = hDlg;
	LV_ITEM lv;
	UNREFERENCED_PARAMETER(lParam);
	switch (message)
	{
		case WM_NOTIFY:
		  {
		  }
		break;
	case WM_INITDIALOG:
	{
		//showTagAndContent.ClearItem();
		LPWSTR Temp = new WCHAR[10000];
		wstring Content = showTagAndContent.getNoteAt(postChoose2);
		wstring Tag = showTagAndContent.getListAt(postChoose2);
		SetDlgItemTextW(hDlg, ID_Content, Content.c_str());
		SetDlgItemTextW(hDlg, ID_Tag, Tag.c_str());
		
	}
	return (INT_PTR)TRUE;
	
	case WM_COMMAND:
		if (LOWORD(wParam) == IDVICCANCEL)
		{
			int MBAN = MessageBox(hwnd, L"Baòn coì thâòt sýò muôìn thoaìt?", L"Thông baìo", MB_YESNO | MB_ICONQUESTION);
			switch (MBAN)
			{
			case IDYES:
				EndDialog(hDlg, LOWORD(wParam));
				break;
			case IDNO:
				//do nothing
				break;
			}
			return (INT_PTR)TRUE;
		}
		if (LOWORD(wParam) == IDVIC_BACK)
		{
			{
				EndDialog(hDlg,LOWORD(wParam));
				DialogBox(hInst, MAKEINTRESOURCE(IDD_DIALOGVIEWCONTENT), hwnd, TagAndContent);
				break;
			}
			return (INT_PTR)TRUE;
		}
		break;
	}
	return (INT_PTR)FALSE;
}

//Draw pie chart
int drawPieChart(HINSTANCE hInstance, int nCmdShow, HWND hWnd, HDC &hdc, int x, int y)
{

	COLORREF Color[6] = {Red, Blue, Green, Yellow, Orange, Cyan}; 
	PieChart *pie;
	pie = new PieChart[ListTag.ListSize()];
	int sumTag = 0;
	float starAngle = 0;
	int CountExist = 0;
	int yNote = 30;

	HRGN Note1 = CreateRectRgn(200, 30, 220, 50);
	HBRUSH hbrushNote1 = CreateSolidBrush(Color[0]);
	//FillRgn(hdc, Note1, hbrushNote1);

	HRGN Note2 = CreateRectRgn(200, 70, 220, 90);
	HBRUSH hbrushNote2 = CreateSolidBrush(Color[1]);
	//FillRgn(hdc, Note2, hbrushNote2);

	HRGN Note3 = CreateRectRgn(200, 110, 220, 130);
	HBRUSH hbrushNote3 = CreateSolidBrush(Color[2]);
	//FillRgn(hdc, Note3, hbrushNote3);

	HRGN Note4 = CreateRectRgn(200, 150, 220, 170);
	HBRUSH hbrushNote4 = CreateSolidBrush(Color[3]);
	//FillRgn(hdc, Note4, hbrushNote4);

	HRGN Note5 = CreateRectRgn(200, 190, 220, 210);
	HBRUSH hbrushNote5 = CreateSolidBrush(Color[4]);
	//FillRgn(hdc, Note5, hbrushNote5);

	HRGN Note6 = CreateRectRgn(200, 230, 220, 250);
	HBRUSH hbrushNote6 = CreateSolidBrush(Color[45]);
	//FillRgn(hdc, Note6, hbrushNote6);
	HRGN Note[6] = {Note1, Note2, Note3, Note4, Note5,Note6};
	HBRUSH hbrushNote[6] = {hbrushNote1, hbrushNote2,hbrushNote3,hbrushNote4,hbrushNote5,hbrushNote6};
	
	for (int i = 0; i < ListTag.ListSize(); i++)
	{
		sumTag = sumTag+ ListTag.CountExist(i);
	}
	
	if(ListTag.ListSize() < 7)
	{
		for (int i = 0; i < ListTag.ListSize(); i++)
		{
			wstring nameTag = ListTag.ItemAt(i) + L" (" + to_wstring(ListTag.CountExist(i)) + L" Note)" ;
			CreateWindowEx(0, L"STATIC", nameTag.c_str(), WS_CHILD | WS_VISIBLE | SS_LEFT, 240, yNote, 200, 100, hWnd, NULL, hInst, NULL);
			pie[i].setStartAngle(starAngle);
			pie[i].setSweepAngle(360*ListTag.CountExist(i)/sumTag);
			FillRgn(hdc, Note[i], hbrushNote[i]);
			starAngle = starAngle + 360*ListTag.CountExist(i)/sumTag;
			yNote+=40;
		}
	}
	else if(ListTag.ListSize() >= 7)
	{
		for (int i = 0; i < 5; i++)
		{
			
			wstring nameTag = ListTag.ItemAt(i) + L" (" + to_wstring(ListTag.CountExist(i)) + L" Note)" ;
			CreateWindowEx(0, L"STATIC", nameTag.c_str(), WS_CHILD | WS_VISIBLE | SS_LEFT, 240, yNote, 200, 100, hWnd, NULL, hInst, NULL);
			pie[i].setStartAngle(starAngle);
			pie[i].setSweepAngle(360*ListTag.CountExist(i)/sumTag);
			FillRgn(hdc, Note[i], hbrushNote[i]);
			starAngle = starAngle + 360*ListTag.CountExist(i)/sumTag;
			yNote+=40;
		}
		for(int i = 5; i <ListTag.ListSize(); i++)
		{
			CountExist = CountExist + ListTag.CountExist(i);
		}
		pie[5].setStartAngle(starAngle);
		pie[5].setSweepAngle(360*CountExist/sumTag);
		FillRgn(hdc, Note[5], hbrushNote[5]);
		wstring nameTag =  + L"CoÌn laòi (" + to_wstring(CountExist) + L" Note)" ;
		CreateWindowEx(0, L"STATIC", nameTag.c_str(), WS_CHILD | WS_VISIBLE | SS_LEFT, 240, yNote, 200, 100, hWnd, NULL, hInst, NULL);
	}
	
	for (int i = 0; i < ListTag.ListSize(); i++)
	{	
		pie[i].setX(x);
		pie[i].setY(y);
		hdc = GetDC(hWnd);
		BeginPath(hdc);
		SelectObject(hdc, CreateSolidBrush(Color[i]));
		MoveToEx(hdc, pie[i].getX(), pie[i].getY(), (LPPOINT)NULL);
		AngleArc(hdc, pie[i].getX(), pie[i].getY(), pie[i].getR(),
			pie[i].getStartAngle(), pie[i].getSweepAngle());
		LineTo(hdc, pie[i].getX(), pie[i].getY());
		EndPath(hdc);
		StrokeAndFillPath(hdc);
		ReleaseDC(hWnd, hdc);
	}
	
	return 0;
}

//Dialog View Statistics
INT_PTR CALLBACK ViewStatistics(HWND hDlg, UINT message, WPARAM wParam, LPARAM lParam)
{
	hwndViewStatistics = hDlg;
	UNREFERENCED_PARAMETER(lParam);
	switch (message)
	{
		case WM_NOTIFY:
		  {
		  }
		break;
	case WM_INITDIALOG:
	{
		
	}
	return (INT_PTR)TRUE;
	case WM_PAINT:
	{
		COLORREF Color[6] = {Red, Blue, Green, Yellow, Orange, Cyan};
		PAINTSTRUCT ps;
		HDC hdc = BeginPaint(hwndViewStatistics, &ps);
		HDC hdc2 = BeginPaint(hwndViewStatistics, &ps);
		// TODO: Add any drawing code that uses hdc here...
		drawPieChart(hInst, NULL, hwndViewStatistics, hdc, 100, 100);
		EndPaint(hwndViewStatistics, &ps);
	}
	break;
	return (INT_PTR)TRUE;
	case WM_COMMAND:
		if (LOWORD(wParam) == IDVS_CCANCEL)
		{
			int MBAN = MessageBox(hwnd, L"Baòn coì thâòt sýò muôìn thoaìt?", L"Thông baìo", MB_YESNO | MB_ICONQUESTION);
			switch (MBAN)
			{
			case IDYES:
				EndDialog(hDlg, LOWORD(wParam));
				break;
			case IDNO:
				//do nothing
				break;
			}
			return (INT_PTR)TRUE;
		}	
	}
	return (INT_PTR)FALSE;
}