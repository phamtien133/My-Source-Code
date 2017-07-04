// WinExplorer.cpp : Defines the entry point for the application.
//
#include "stdafx.h"
#include "WinExplorer.h"


#define MAX_LOADSTRING 100
//Define ID
#define IDM_FILE_NEW	1
#define IDM_FILE_OPEN	2
#define IDM_FILE_QUIT	3
#define SIZE_850x250	4
#define MaximunSize		5
#define SIZE_1050x450	6
RECT TreeViewRect;
const int BUFFERSIZE = 260;
int Width;
int Height;
HWND hwnd;
MSG  msg;
WNDCLASS wc;
HINSTANCE hInstance;
int       nCmdShow;
WCHAR bufWidth[BUFFERSIZE];
WCHAR bufHeigth[BUFFERSIZE];
HINSTANCE g_hInst;
WCHAR curPath[BUFFERSIZE];
WCHAR configPath[BUFFERSIZE];

//Menu about me
void AddMenus(HWND);
//Class lấy thông tin
class GetInf
{
private:
	TCHAR **Drive;
	TCHAR **VolName;
	TCHAR **DisplayName;
	int Count;				//Biến đếm số lượng ổ đỉa

public:
	//26*4+1 = 105 => Độ dài chuỗi tối đa
	TCHAR buffer[105];

	//Hàm khởi tạo
	GetInf()
	{
		Drive = NULL;
		VolName = NULL;
		DisplayName = NULL;
		Count = 0;
	}
	//Hàm hủy
	~GetInf()
	{
		for (int i = 0; i < Count; i++)
		{
			delete[] Drive[i];
			delete[] VolName[i];
			delete[] DisplayName[i];
		}
		Count = 0;
		delete[] Drive;
		delete[] VolName;
		delete[] DisplayName;

	}
	//lấy tên Drive
	TCHAR* GetDriveName(const int &i)
	{
		return Drive[i];
	}
	//Lấy tên volume
	TCHAR* GetVolumeName(const int &i)
	{
		return VolName[i];
	}
	//Lấy tên
	TCHAR* GetDisplayName(const int &i)
	{
		return DisplayName[i];
	}
	//Lấy số lượng ổ đỉa
	int GetCount()
	{
		return Count;
	}

	void GetSystemDrives()
	{
		//Loại Drive
		int Type;
		int Temp = 0;
		//Điền vào buffer chuỗi chỉ định ổ đỉa của hệ thống
		GetLogicalDriveStrings(105, buffer);

		//Đếm số lượng ổ đỉa
		for (int i = 0; !((buffer[i] == 0) && (buffer[i + 1] == 0)); i++)
			if (buffer[i] == 0)
				++Count;
		++Count;

		//Cấp phát bộ nhớ 
		Drive = new TCHAR*[Count];
		VolName = new TCHAR*[Count];
		DisplayName = new TCHAR*[Count];

		for (int i = 0; i < Count; ++i)
		{
			Drive[i] = new TCHAR[4];
			VolName[i] = new TCHAR[30];
			DisplayName[i] = new TCHAR[35];
		}
		
		//Lấy kí tự của từng ổ đỉa
		for (int j = 0; j < Count; j++)
		{
			int k = 0;
			while (buffer[Temp] != 0)
				Drive[j][k++] = buffer[Temp++];
			Drive[j][k] = 0;
			Temp++;
		}

		//Lấy volume cho từng ổ đĩa, kết hợp luôn với việc lấy tên hiển thị ứng với từng ổ
		for (int i = 0; i < Count; i++)
		{
			Type = GetDriveType(Drive[i]);
			StrCpy(buffer, _T(""));

			if ((Type == DRIVE_FIXED) || ((i > 1) && (Type == DRIVE_REMOVABLE)))//Nếu là ổ cứng hay USB
			{

				GetVolumeInformation(Drive[i], buffer, 105, NULL, NULL, NULL, NULL, 0);
				StrCpy(VolName[i], buffer);
			}
			else if (((i == 0) || (i == 1)) && (Type == DRIVE_REMOVABLE)) //Nếu là ổ mềm
			{
				StrCpy(VolName[i], _T("3½ Floppy"));
			}
			else if (Type == DRIVE_CDROM)							
			{
				GetVolumeInformation(Drive[i], buffer, 105, NULL, NULL, NULL, NULL, 0);
				if (wcslen(buffer) == 0)
					StrCpy(VolName[i], _T("CD Rom"));
				else
					StrCpy(VolName[i], buffer);

			}

			if (wcslen(VolName[i]) == 0)
				StrCpy(DisplayName[i], _T("Local Disk"));
			else
				StrCpy(DisplayName[i], VolName[i]);
			//Thêm vào phần sau hai dấu mở ngoặc
			StrCat(DisplayName[i], _T(" ("));
			StrNCat(DisplayName[i], Drive[i], 3);
			StrCat(DisplayName[i], _T(")"));
		}

	}
};
//Class treeview
class TreeView
{
private:
	HINSTANCE	Inst;
	HWND		Parent;
	HWND		TTreeView;
	int			TREE_ID;

public:
	TreeView()
	{
		Inst = NULL;
		Parent = NULL;
		TTreeView = NULL;
		TREE_ID = 0;
	}
	~TreeView()
	{
		DestroyWindow(TTreeView);
	}

	//Hàm tạo treeview
	HWND CreateATreeView(HWND hwndParent)
	{
		Parent = hwndParent;
		//Inst = ParentInst;

		RECT rcClient; //Biến lưu tọa độ của cửa sổ cha
		HWND hwndTV;

		InitCommonControls(); //gọi hàm trước khi khởi tạo commen control treeview

		GetClientRect(hwndParent, &rcClient);	//Lấy tọa độ của cửa sổ cha lưu cho biến rcClient
												//Tạo treeview
		hwndTV = CreateWindowEx(0, WC_TREEVIEW, TEXT("Tree View"), WS_VISIBLE | WS_CHILD | WS_BORDER | TVS_HASLINES, 0, 0,
			rcClient.right, rcClient.bottom, hwndParent, (HMENU)ID_TREEVIEW, g_hInst, NULL);
		TREE_ID = ID_TREEVIEW;
		return hwndTV;
	}
	//
	void Create(HWND parentWnd, long ID, HINSTANCE hParentInst, int nWidth, int nHeight,
		long lExtStyle, long lStyle, int x, int y)
	{
		InitCommonControls();
		Parent = parentWnd;
		Inst = hParentInst;
		TTreeView = CreateWindowEx(lExtStyle, WC_TREEVIEW, _T("Tree View"),
			WS_CHILD | WS_VISIBLE | WS_BORDER | WS_SIZEBOX | WS_VSCROLL | WS_TABSTOP | lStyle,
			x, y, nWidth, nHeight, parentWnd,
			(HMENU)ID, hParentInst, NULL);
		TREE_ID = ID;

	}
	//Hàm thêm item vào tree
	HTREEITEM AddItemToTree(HWND hwndTV, LPTSTR lpszItem, int nLevel)
	{
		TVITEM tvi;
		TVINSERTSTRUCT tvins;
		static HTREEITEM hPrev = (HTREEITEM)TVI_FIRST;
		static HTREEITEM hPrevRootItem = NULL;
		static HTREEITEM hPrevLev2Item = NULL;
		HTREEITEM hti;

		tvi.mask = TVIF_TEXT | TVIF_IMAGE | TVIF_SELECTEDIMAGE | TVIF_PARAM;

		//Đặt text của item
		tvi.pszText = lpszItem;
		tvi.cchTextMax = sizeof(tvi.pszText) / sizeof(tvi.pszText[0]);

		// Cung cấp một tài liệu ảnh cho item (giả sử nó k phải là mục cha) 
		tvi.iImage = 0;
		tvi.iSelectedImage = 0;

		//Lưu heading level trong vùng dữ liệu
		tvi.lParam = (LPARAM)nLevel;
		tvins.item = tvi;
		tvins.hInsertAfter = hPrev;

		//Đặt các mục cha dựa trên level
		if (nLevel == 1) //Gốc
			tvins.hParent = TVI_ROOT;
		else if (nLevel == 2)
			tvins.hParent = hPrevRootItem;
		else
			tvins.hParent = hPrevLev2Item;

		//Thêm item vào tree-view
		hPrev = (HTREEITEM)SendMessage(hwndTV, TVM_INSERTITEM,
			0, (LPARAM)(LPTVINSERTSTRUCT)&tvins);
		if (hPrev == NULL)
			return NULL;

		//lưu xử lý cho các item
		if (nLevel == 1)
			hPrevRootItem = hPrev;
		else if (nLevel == 2)
			hPrevLev2Item = hPrev;

		//Item mới là item con. Thư mục cha cần có bitmap để nhận biết nó có thư mục con
		if (nLevel > 1)
		{
			hti = TreeView_GetParent(hwndTV, hPrev);
			tvi.mask = TVIF_IMAGE | TVIF_SELECTEDIMAGE;
			tvi.hItem = hti;
			tvi.iImage = 0;
			tvi.iSelectedImage = 0;
			TreeView_SetItem(hwndTV, &tvi);
		}
		return hPrev;
	}
	
	HWND GetHandle()
	{
		return TTreeView;
	}
	int	GetID()
	{
		return TREE_ID;
	}
	//Lấy đường dẫn
	LPCWSTR	GetPath(HTREEITEM Item)
	{
		TVITEMEX tv;
		tv.mask = TVIF_PARAM;
		tv.hItem = Item;
		TreeView_GetItem(TTreeView, &tv);
		return (LPCWSTR)tv.lParam;
	}
	//Lấy đường dẫn hiện tại
	LPCWSTR	GetCurPath()
	{
		return GetPath(GetCurSel());
	}

	HTREEITEM GetCurSel()
	{
		return TreeView_GetNextItem(TTreeView, NULL, TVGN_CARET);
	}

	LPCWSTR	GetCurSelText()
	{
		TVITEMEX tv;
		TCHAR *buffer = new TCHAR[256];

		tv.mask = TVIF_TEXT;
		tv.hItem = GetCurSel();
		tv.pszText = buffer;
		tv.cchTextMax = 256;
		TreeView_GetItem(TTreeView, &tv);
		return (LPCWSTR)tv.pszText;
	}

	HTREEITEM GetChild(HTREEITEM Item)
	{
		return TreeView_GetChild(TTreeView, Item);
	}

	void GetFocus()
	{
		SetFocus(TTreeView);
	}

	//Thư mục gốc
	HTREEITEM	GetDesktop()
	{
		return TreeView_GetRoot(TTreeView);
	}

	HTREEITEM	GetMyComputer()
	{
		return TreeView_GetChild(TTreeView, GetDesktop());
	}

	void LoadMyComputer(GetInf *Inf)
	{
		TV_INSERTSTRUCT tvInsert;
		tvInsert.item.mask = TVIF_TEXT | TVIF_SELECTEDIMAGE | TVIF_PARAM;

		//Desktop
		tvInsert.hParent = NULL;
		tvInsert.hInsertAfter = TVI_ROOT;
		tvInsert.item.pszText = _T("Desktop");
		tvInsert.item.lParam = (LPARAM)_T("Desktop");
		HTREEITEM hDesktop = TreeView_InsertItem(TTreeView, &tvInsert);

		//My Computer
		tvInsert.hParent = hDesktop;
		tvInsert.hInsertAfter = TVI_LAST;
		tvInsert.item.pszText = _T("This PC");
		tvInsert.item.lParam = (LPARAM)_T("This PC");
		HTREEITEM hMyComputer = TreeView_InsertItem(TTreeView, &tvInsert);

		//Duyệt ổ đỉa từ class getinf đã có đếm số lượng ổ đỉa (Count)
		for (int i = 0; i < Inf->GetCount(); i++)
		{
			tvInsert.hParent = hMyComputer;
			tvInsert.item.pszText = Inf->GetDisplayName(i);
			tvInsert.item.lParam = (LPARAM)Inf->GetDriveName(i);
			HTREEITEM hDrive = TreeView_InsertItem(TTreeView, &tvInsert);
		}
		//Mặc định cho My Computer expand và select luôn
		TreeView_SelectItem(TTreeView, hMyComputer);
	}
	void LoadChild(HTREEITEM &Parent, LPCWSTR path, BOOL ShowHiddenSystem = FALSE)
	{
		TCHAR buffer[10240]; //Độ dài tối đa của đường dẫn
		StrCpy(buffer, path);
		StrCat(buffer, _T("\\*"));
		TV_INSERTSTRUCT tvInsert;
		tvInsert.hParent = Parent;
		tvInsert.hInsertAfter = TVI_LAST;
		tvInsert.item.mask = TVIF_TEXT | TVIF_SELECTEDIMAGE | TVIF_PARAM;

		WIN32_FIND_DATA fd;
		HANDLE File = FindFirstFileW(buffer, &fd);
		BOOL Found = 1;

		if (File == INVALID_HANDLE_VALUE)
			Found = FALSE;

		TCHAR * folderPath;
		while (Found)
		{
			if ((fd.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY)
				&& ((fd.dwFileAttributes & FILE_ATTRIBUTE_HIDDEN) != FILE_ATTRIBUTE_HIDDEN)
				&& ((fd.dwFileAttributes & FILE_ATTRIBUTE_SYSTEM) != FILE_ATTRIBUTE_SYSTEM)
				&& (StrCmp(fd.cFileName, _T(".")) != 0) && (StrCmp(fd.cFileName, _T("..")) != 0))
			{
				tvInsert.item.pszText = fd.cFileName;
				folderPath = new TCHAR[wcslen(path) + wcslen(fd.cFileName) + 2];

				StrCpy(folderPath, path);
				if (wcslen(path) != 3)
					StrCat(folderPath, _T("\\"));
				StrCat(folderPath, fd.cFileName);

				tvInsert.item.lParam = (LPARAM)folderPath;
				HTREEITEM hItem = TreeView_InsertItem(TTreeView, &tvInsert);

				PreLoad(hItem);
			}
			Found = FindNextFileW(File, &fd);
		}
	}

	void PreloadExpanding(HTREEITEM Prev, HTREEITEM CurSel)
	{
		if (CurSel == GetMyComputer())
			return;

		HTREEITEM CurSelChild = TreeView_GetChild(TTreeView, CurSel);

		if (!StrCmp(GetPath(CurSelChild), _T("PreLoad")))
		{
			TreeView_DeleteItem(TTreeView, CurSelChild);
			LoadChild(CurSel, GetPath(CurSel));
		}
	}

	void Expand(HTREEITEM Item)
	{
		TreeView_Expand(TTreeView, Item, TVE_EXPAND);
	}
	//Kích thước treeview
	void Size(int cy)
	{
		RECT treeRC;
		GetWindowRect(TTreeView, &treeRC);
		MoveWindow(TTreeView, 0, 0, treeRC.right - treeRC.left, cy, SWP_SHOWWINDOW);
	}
	void PreLoad(HTREEITEM hItem);
};
//

BOOL Started = FALSE; //Báo hiệu đã khởi tạo xong các điều khiển tránh lỗi trong sự kiện WM_SIZE
GetInf *Inf;
TreeView *TTreeView;

HINSTANCE hInst2;

//
void DoViewChange(LPNMTOOLBAR lpnmToolBar)
{
	HMENU     hPopupMenu = NULL;
	HMENU     hMenuLoaded;
	RECT rc;
	TPMPARAMS tpm;

	SendMessage(lpnmToolBar->hdr.hwndFrom, TB_GETRECT,
		(WPARAM)lpnmToolBar->iItem, (LPARAM)&rc);
	MapWindowPoints(lpnmToolBar->hdr.hwndFrom,
		HWND_DESKTOP, (LPPOINT)&rc, 2);
	tpm.cbSize = sizeof(TPMPARAMS);
	tpm.rcExclude = rc;

	hMenuLoaded = LoadMenu(g_hInst, MAKEINTRESOURCE(IDR_MENU2));

	hPopupMenu = GetSubMenu(hMenuLoaded, 0);


	DestroyMenu(hMenuLoaded);
}

void DoGo()
{
	TCHAR *buffer = new TCHAR[10240];
}
void DoSizeTreeView()
{
	RECT newTreeRC;
	
	GetClientRect(TTreeView->GetHandle(), &newTreeRC);

	if (newTreeRC.right != TreeViewRect.right)
	{
		TreeViewRect = newTreeRC;
	}
}


LRESULT CALLBACK WndProc(HWND hWnd, UINT message, WPARAM wParam, LPARAM lParam);

int APIENTRY wWinMain(_In_ HINSTANCE hInstance,
	_In_opt_ HINSTANCE hPrevInstance,
	_In_ LPWSTR    lpCmdLine,
	_In_ int       nCmdShow);

// Global Variables:
HINSTANCE hInst;                                // current instance
WCHAR szTitle[MAX_LOADSTRING];                  // The title bar text
WCHAR szWindowClass[MAX_LOADSTRING];            // the main window class name

												// Forward declarations of functions included in this code module:
ATOM                MyRegisterClass(HINSTANCE hInstance);
BOOL                InitInstance(HINSTANCE, int);
LRESULT CALLBACK    WndProc(HWND, UINT, WPARAM, LPARAM);
INT_PTR CALLBACK    About(HWND, UINT, WPARAM, LPARAM);

int APIENTRY wWinMain(_In_ HINSTANCE hInstance,
	_In_opt_ HINSTANCE hPrevInstance,
	_In_ LPWSTR    lpCmdLine,
	_In_ int       nCmdShow)
{
	UNREFERENCED_PARAMETER(hPrevInstance);
	UNREFERENCED_PARAMETER(lpCmdLine);
	// Tạo đường dẫn tuyệt đối tới file config
	GetCurrentDirectory(BUFFERSIZE, curPath);
	wsprintf(configPath, L"%s\\config.ini", curPath);


	//Lấy Size WidthxHeight
	GetPrivateProfileString(L"Size", L"width", L"Default value", bufWidth, BUFFERSIZE, configPath);
	Width = _wtoi(bufWidth);

	GetPrivateProfileString(L"Size", L"height", L"Default value", bufHeigth, BUFFERSIZE, configPath);
	Height = _wtoi(bufHeigth);
	//******************** Tạo Form ********************
	wc.style = CS_HREDRAW | CS_VREDRAW; //	Style cửa sổ, CS_HREDRAW và CS_VREDRAW được thiết lập vẽ lại kt cửa sổ
										// Không sử dụng các byte bổ sung (additional bytes). Nên ta đặt chúng bằng 0
	wc.cbClsExtra = 0;
	wc.cbWndExtra = 0;

	wc.lpszClassName = TEXT("WinExplorer"); //Tên class, tên cửa sổ
											   // Đặt màu nền cho cửa sổ
	wc.hInstance = hInstance;
	wc.hbrBackground = GetSysColorBrush(COLOR_3DFACE);
	//Không có menu
	wc.lpszMenuName = NULL;
	//Khai báo thủ tục xử lý message cho class
	wc.lpfnWndProc = WndProc;
	//Đặt biểu tượng con trỏ và icon ứng dụng
	wc.hCursor = LoadCursor(NULL, IDC_ARROW);
	wc.hIcon = LoadIcon(NULL, IDI_APPLICATION);
	//Đăng ký lớp cửa sổ với Windows
	RegisterClass(&wc);
	//Trái sang, trên xuống, rộng, cao
	hwnd = CreateWindow(wc.lpszClassName, TEXT("WinExplorer"), WS_OVERLAPPEDWINDOW | WS_VISIBLE,
		0, 0, Width, Height, NULL, NULL, hInstance, NULL); //Vị trí từ trái, vị trí từ trên, rộng, cao
															   //Hiển thị và update cửa sổ

	ShowWindow(hwnd, 1);
	UpdateWindow(hwnd);
	HACCEL hAccelTable = LoadAccelerators(hInstance, MAKEINTRESOURCE(IDC_WINEXPLORER));
	// Main message loop:	Sử dụng hàm GetMessage() để lấy message từ hàng đợi 
	//						và gửi các message này cho các thủ tục xử lí message bằng hàm DispatchMessage().
	
	while (GetMessage(&msg, nullptr, 0, 0))
	{
		if (!TranslateAccelerator(msg.hwnd, hAccelTable, &msg))
		{
			TranslateMessage(&msg);
			DispatchMessage(&msg);
		}
	}

	return (int)msg.wParam;
}

//Thêm menu about me
void AddMenus(HWND hwnd)
{
	HMENU hMenubarAboutMe;
	HMENU hMenubarSize;
	HMENU hMenuAboutMe;
	hMenubarAboutMe = CreateMenu();
	hMenuAboutMe = CreateMenu();
	hMenubarSize = CreateMenu();
	//Tạo ra từng menu About me
	AppendMenu(hMenuAboutMe, MF_STRING, IDM_FILE_NEW, TEXT("1412544"));
	AppendMenu(hMenuAboutMe, MF_STRING, IDM_FILE_OPEN, TEXT("Phạm Đức Tiên"));
	AppendMenu(hMenuAboutMe, MF_SEPARATOR, 0, NULL); //Đường kẻ giữa
	AppendMenu(hMenuAboutMe, MF_STRING, IDM_FILE_QUIT, TEXT("Thanks for using my program... (Exit)"));
	AppendMenu(hMenubarAboutMe, MF_POPUP, (UINT_PTR)hMenuAboutMe, TEXT("About me"));
	//Tạo ra từng menu Size
	AppendMenu(hMenubarSize, MF_STRING, SIZE_850x250, TEXT("850x250"));
	AppendMenu(hMenubarSize, MF_SEPARATOR, 0, NULL); //Đường kẻ giữa
	AppendMenu(hMenubarSize, MF_STRING, MaximunSize, TEXT("MaximunSize"));
	AppendMenu(hMenubarSize, MF_SEPARATOR, 0, NULL); //Đường kẻ giữa
	AppendMenu(hMenubarSize, MF_STRING, SIZE_1050x450, TEXT("1050x450"));
	AppendMenu(hMenubarAboutMe, MF_POPUP, (UINT_PTR)hMenubarSize, TEXT("Size For Windows"));
	//SetMenu(hwnd, hMenubar2); //Gắn menu item lên menubar
	SetMenu(hwnd, hMenubarAboutMe); //Gắn menu item lên menubar
}



//Hàm thêm tập tin và thư mục vào treeview

ATOM MyRegisterClass(HINSTANCE hInstance)
{
	WNDCLASSEXW wcex;

	wcex.cbSize = sizeof(WNDCLASSEX);

	wcex.style = CS_HREDRAW | CS_VREDRAW;
	wcex.lpfnWndProc = WndProc;
	wcex.cbClsExtra = 0;
	wcex.cbWndExtra = 0;
	wcex.hInstance = hInstance;
	wcex.hIcon = LoadIcon(hInstance, MAKEINTRESOURCE(IDI_WINEXPLORER));
	wcex.hCursor = LoadCursor(nullptr, IDC_ARROW);
	wcex.hbrBackground = (HBRUSH)(COLOR_WINDOW + 1);
	wcex.lpszMenuName = MAKEINTRESOURCEW(IDC_WINEXPLORER);
	wcex.lpszClassName = szWindowClass;
	wcex.hIconSm = LoadIcon(wcex.hInstance, MAKEINTRESOURCE(IDI_SMALL));

	return RegisterClassExW(&wcex);
}

BOOL InitInstance(HINSTANCE hInstance, int nCmdShow)
{
	hInst = hInstance; // Store instance handle in our global variable

	HWND hWnd = CreateWindowW(szWindowClass, szTitle, WS_OVERLAPPEDWINDOW,
		CW_USEDEFAULT, 0, CW_USEDEFAULT, 0, nullptr, nullptr, hInstance, nullptr);

	if (!hWnd)
	{
		return FALSE;
	}
	ShowWindow(hWnd, nCmdShow);
	UpdateWindow(hWnd);

	return TRUE;
}
//Khai báo xứ lý các message

LRESULT CALLBACK WndProc(HWND hWnd, UINT message, WPARAM wParam, LPARAM lParam)
{
	// Tạo đường dẫn tuyệt đối tới file config
	GetCurrentDirectory(BUFFERSIZE, curPath);
	wsprintf(configPath, L"%s\\config.ini", curPath);

	switch (message)
	{
	case WM_CREATE:
	{
		RECT main;
		AddMenus(hWnd);
		
		//Lấy các kích thước của cửa sổ hiện hành

		GetWindowRect(hWnd, &main);
		//Khởi tạo các control của mình
		Inf = new GetInf;
		Inf->GetSystemDrives();
		TTreeView = new TreeView;
		//TTreeView->CreateATreeView(hWnd);
		TTreeView->Create(hWnd, IDC_TREEVIEW, hInst2, main.right / 3, main.bottom, 0, TVS_HASLINES | TVS_LINESATROOT | TVS_HASBUTTONS | TVS_SHOWSELALWAYS, CW_USEDEFAULT, 0);
		TTreeView->LoadMyComputer(Inf);
		TTreeView->GetFocus();

		//Đã khởi tạo xong ứng dụng
		//Started = TRUE;
		return TRUE;
		//break;
	}

	case WM_COMMAND:
	{
		int wmId = LOWORD(wParam);
		//About me
		switch (LOWORD(wParam))
		{
		case IDM_FILE_NEW:
		case IDM_FILE_OPEN:
			Beep(50, 100);
			break;
		case IDM_FILE_QUIT:
			SendMessage(hWnd, WM_CLOSE, 0, 0);
			DestroyWindow(hWnd);
			break;
		case SIZE_850x250:
			//Lấy Size WidthxHeight
			GetPrivateProfileString(L"Size", L"width", L"Default value", bufWidth, BUFFERSIZE, configPath);
			Width = _wtoi(bufWidth);
			GetPrivateProfileString(L"Size", L"height", L"Default value", bufHeigth, BUFFERSIZE, configPath);
			Height = _wtoi(bufHeigth);
			ShowWindow(hWnd, 0);
			UpdateWindow(hWnd);
			hwnd = CreateWindow(wc.lpszClassName, TEXT("SavingInterestApp"), WS_OVERLAPPEDWINDOW | WS_VISIBLE,
				300, 200, Width, Height, NULL, NULL, hInstance, NULL);
			ShowWindow(hwnd, 1);
			UpdateWindow(hwnd);
			break;
		case MaximunSize:
			//Lấy Size WidthxHeight
			GetPrivateProfileString(L"Size2", L"width", L"Default value", bufWidth, BUFFERSIZE, configPath);
			Width = _wtoi(bufWidth);
			GetPrivateProfileString(L"Size2", L"height", L"Default value", bufHeigth, BUFFERSIZE, configPath);
			Height = _wtoi(bufHeigth);
			ShowWindow(hWnd, 0);
			UpdateWindow(hWnd);
			hwnd = CreateWindow(wc.lpszClassName, TEXT("SavingInterestApp"), WS_OVERLAPPEDWINDOW | WS_VISIBLE,
				0, 0, Width, Height, NULL, NULL, hInstance, NULL);
			ShowWindow(hwnd, 3);
			UpdateWindow(hwnd);
			break;
		case SIZE_1050x450:
			//Lấy Size WidthxHeight
			GetPrivateProfileString(L"Size3", L"width", L"Default value", bufWidth, BUFFERSIZE, configPath);
			Width = _wtoi(bufWidth);
			GetPrivateProfileString(L"Size3", L"height", L"Default value", bufHeigth, BUFFERSIZE, configPath);
			Height = _wtoi(bufHeigth);
			ShowWindow(hWnd, 0);
			UpdateWindow(hWnd);
			hwnd = CreateWindow(wc.lpszClassName, TEXT("SavingInterestApp"), WS_OVERLAPPEDWINDOW | WS_VISIBLE,
				300, 200, Width, Height, NULL, NULL, hInstance, NULL);
			ShowWindow(hwnd, 1);
			UpdateWindow(hwnd);
			break;
		
		}

	}
	break;
	case WM_NOTIFY:
	{
		NMHDR *pnm = new NMHDR;
		LPNMTOOLBAR lpnmToolBar = (LPNMTOOLBAR)pnm;

		if (Started) //Để tránh vòng lặp
		{
			LPNMTREEVIEW lpnmTree = (LPNMTREEVIEW)pnm;

			switch (pnm->code)
			{
				case TVN_SELCHANGED:
					TTreeView->Expand(TTreeView->GetCurSel());
					break;
					
				
				case NM_CUSTOMDRAW:
					if (pnm->hwndFrom == TTreeView->GetHandle())
						DoSizeTreeView();
					break;
				case TBN_DROPDOWN:
					if (lpnmToolBar->iItem == IDC_TOOLBAR_VIEW)
						DoViewChange(lpnmToolBar);
					break;
			}
		}
	}
	break;
	case WM_PAINT:
	{
		PAINTSTRUCT ps;
		HDC hdc = BeginPaint(hWnd, &ps);
		// TODO: Add any drawing code that uses hdc here...
		EndPaint(hWnd, &ps);
	}
	break;
	case WM_DESTROY:
	{
		PostQuitMessage(0);
		return 0;
	}
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