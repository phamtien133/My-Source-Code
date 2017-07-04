// TipsCalculatorHelper.cpp : Defines the entry point for the application.
//

#include "stdafx.h"
#include "MoneyManagement.h"
#include "windows.h" //header của chương trình, chứa việc gọi các hàm API, các macro và dữ liệu cơ bản
#include <winuser.h>
#include <commctrl.h>
//Thao tác với file
#include <fstream>
#include <iostream>  
#include <string>
#include <vector>
using namespace std;
//
#include <locale>
#include <codecvt>
#include <cstdlib>

#define MAX_LOADSTRING 100
//Define ID
#define IDM_FILE_NEW	1
#define IDM_FILE_OPEN	2
#define IDM_FILE_QUIT	3
#define SIZE_850x250	4
#define SIZE_950x350	5
#define SIZE_1050x450	6

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

WCHAR curPath[BUFFERSIZE];
WCHAR configPath[BUFFERSIZE];

wstring* Line  = new wstring[999999];


//Class data danh sách chi tiêu
class DataOfManagement
{
private:
	wstring _DSCT;
	wstring _LoaiChiTieu;
	wstring _NoiDung;
	wstring _SoTien;
public:
	void setDSCT(wstring DSCT)
	{
		_DSCT = DSCT;
	}

	void setLoaiChiTieu(wstring LoaiChiTieu)
	{
		_LoaiChiTieu = LoaiChiTieu;
	}
	
	void setNoiDung(wstring NoiDung)
	{
		_NoiDung = NoiDung;
	}

	void setSoTien(wstring SoTien)
	{
		_SoTien = SoTien;
	}

	wstring getDSCT()
	{
		return _DSCT;
	}

	wstring getLoaiChiTieu()
	{
		return _LoaiChiTieu;
	}

	wstring getNoiDung()
	{
		return _NoiDung;
	}

	wstring getSoTien()
	{
		return _SoTien;
	}
};

//Class danh sách chi tiêu
class DSCT
{
private:
	vector <DataOfManagement> _dsct;

public:
	
	//Hàm đọc file
	void ReadFile(char * FileName)
	{
		//
		const std::locale empty_locale = std::locale::empty();
		typedef std::codecvt_utf8<wchar_t> converter_type;
		const converter_type* converter = new converter_type;
		const std::locale utf8_locale = std::locale(empty_locale, converter);		
		//
		DataOfManagement Temp;
		wstring Infomation;
		int SoTien;
		wifstream myFile(FileName);
		myFile.imbue(utf8_locale);
		

		while (!myFile.eof())
		{
			//Lấy dòng text
			getline(myFile, Infomation);
			if (Infomation != L"")
			{
				Temp.setDSCT(Infomation);
				//Tìm vị trí của dấu , đầu tiên
				int FirstPost = Infomation.find_first_of(',');
				//Gán cho Loại chi tiêu
				Temp.setLoaiChiTieu(Infomation.substr(0, FirstPost));
				//Tìm dấu , kế tiếp
				int NextPost = Infomation.find_last_of(',');
				//Gán cho nội dung
				Temp.setNoiDung(Infomation.substr(FirstPost + 1, NextPost - FirstPost - 1));
				//Lấy số tiền
				Temp.setSoTien(Infomation.substr(NextPost + 1, Infomation.length() - NextPost));
				//Thêm 1 dòng vào vector danh sách chi tiêu
				_dsct.push_back(Temp);
			}
			
		}
	}
	//Thêm phần tử vào vector
	void Push_Item(DataOfManagement Item)
	{
		_dsct.push_back(Item);
	}
	//Hàm ghi file
	void WriteFile(char * FileName)
	{
		const std::locale empty_locale = std::locale::empty();
		typedef std::codecvt_utf8<wchar_t> converter_type;
		const converter_type* converter = new converter_type;
		const std::locale utf8_locale = std::locale(empty_locale, converter);
		wofstream myFile(FileName);
		myFile.imbue(utf8_locale);
		
		for (int i = 0; i < _dsct.size(); i++)
		{
			
			myFile << _dsct.at(i).getLoaiChiTieu() << "," << _dsct.at(i).getNoiDung() << "," << _dsct.at(i).getSoTien() << endl;
		}
	}

	//Lấy số lượng phần tử của vector
	int getSize()
	{
		return _dsct.size();
	}

	//Lấy danh sách chi tiêu thứ i
	wstring getDSCT(int i)
	{
		return _dsct.at(i).getDSCT();
	}
	//Lấy Loại chi tiêu thứ i
	wstring getLoaiChiTieu(int i)
	{
		return _dsct.at(i).getLoaiChiTieu();
	}

	//Lấy nội dung thứ i
	wstring getNoiDung(int i)
	{
		return _dsct.at(i).getNoiDung();
	}

	//Lấy số tiền thứ i
	wstring getSoTien(int i)
	{
		return _dsct.at(i).getSoTien();
	}
	
};



//Menu about me
void AddMenus(HWND);
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

	// TODO: Place code here.


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

	wc.lpszClassName = TEXT("MoneyManagement"); //Tên class, tên cửa sổ
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
	////Trái sang, trên xuống, rộng, cao
	hwnd = CreateWindow(wc.lpszClassName, TEXT("MoneyManagement"), WS_OVERLAPPEDWINDOW | WS_VISIBLE,
		350, 40, Width, Height, NULL, NULL, hInstance, NULL); //Vị trí từ trái, vị trí từ trên, rộng, cao
															   //Hiển thị và update cửa sổ

	ShowWindow(hwnd, nCmdShow);
	UpdateWindow(hwnd);
	HACCEL hAccelTable = LoadAccelerators(hInstance, MAKEINTRESOURCE(IDC_MONEYMANAGEMENT));
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
	AppendMenu(hMenubarSize, MF_STRING, SIZE_950x350, TEXT("950x350"));
	AppendMenu(hMenubarSize, MF_SEPARATOR, 0, NULL); //Đường kẻ giữa
	AppendMenu(hMenubarSize, MF_STRING, SIZE_1050x450, TEXT("1050x450"));
	AppendMenu(hMenubarAboutMe, MF_POPUP, (UINT_PTR)hMenubarSize, TEXT("Size For Windows"));
	//SetMenu(hwnd, hMenubar2); //Gắn menu item lên menubar
	SetMenu(hwnd, hMenubarAboutMe); //Gắn menu item lên menubar
}

//ListView
//HWND CreateListView(HWND hwndParent)
//{
//	INITCOMMONCONTROLSEX icex;           // Structure for control initialization.
//	icex.dwICC = ICC_LISTVIEW_CLASSES;
//	InitCommonControlsEx(&icex);
//
//	RECT rcClient;                       // The parent window's client area.
//
//	GetClientRect(hwndParent, &rcClient);
//
//	// Create the list-view window in report view with label editing enabled.
//	HWND hWndListView = CreateWindow(WC_LISTVIEW, L"", WS_CHILD | LVS_REPORT | LVS_EDITLABELS,0, 0,	rcClient.right - rcClient.left,
//									 rcClient.bottom - rcClient.top,hwndParent,(HMENU)IDC_LISTVIEW, hInst,NULL);
//
//	return (hWndListView);
//}


//
//  FUNCTION: MyRegisterClass()
//
//  PURPOSE: Registers the window class.
//
ATOM MyRegisterClass(HINSTANCE hInstance)
{
	WNDCLASSEXW wcex;

	wcex.cbSize = sizeof(WNDCLASSEX);

	wcex.style = CS_HREDRAW | CS_VREDRAW;
	wcex.lpfnWndProc = WndProc;
	wcex.cbClsExtra = 0;
	wcex.cbWndExtra = 0;
	wcex.hInstance = hInstance;
	wcex.hIcon = LoadIcon(hInstance, MAKEINTRESOURCE(IDI_MONEYMANAGEMENT));
	wcex.hCursor = LoadCursor(nullptr, IDC_ARROW);
	wcex.hbrBackground = (HBRUSH)(COLOR_WINDOW + 1);
	wcex.lpszMenuName = MAKEINTRESOURCEW(IDC_MONEYMANAGEMENT);
	wcex.lpszClassName = szWindowClass;
	wcex.hIconSm = LoadIcon(wcex.hInstance, MAKEINTRESOURCE(IDI_SMALL));

	return RegisterClassExW(&wcex);
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

//
//  FUNCTION: WndProc(HWND, UINT, WPARAM, LPARAM)
//
//  PURPOSE:  Processes messages for the main window.
//
//  WM_COMMAND  - process the application menu
//  WM_PAINT    - Paint the main window
//  WM_DESTROY  - post a quit message and return
//
//
//Khai báo xứ lý các message
//Khai báo hwnd cho combobox
HWND hwndCombo;

//Khai báo hwnd cho listbox
HWND hwndList;

//Khai báo hwnd cho listview
HWND hwndListView;
HWND hWndListView;
//
HWND txtNoiDung;
HWND txtSoTien;
HWND txtThongBao;
HWND txtTongCong;
HWND txtN1;
HWND txtN2;
HWND txtN3;
HWND txtN4;
HWND txtN5;
HWND txtN6;

//HMENU DC_BUTTONS2;
WCHAR* bufLoaiChiTieu = NULL;
WCHAR* bufNoiDung = NULL;
WCHAR* bufSoTien = NULL;
WCHAR* bufThongBao = NULL;
WCHAR* bufTongCong = NULL;

int sizeLoaiChiTieu;
int sizeNoiDung;
int sizeSoTien;
int sizeThongBao;
int sizeTongCong;

int LoaiChiTieu;
int NoiDung;
int SoTien;
int LaiThongBao;
int TongCong;

int IsAdd = 0;
int index;
int index2;
DSCT _dsct;
DSCT TestSpace;
//
int AnUong, DiChuyen, NhaCua, XeCo, NhuYeuPham, DichVu, Tong;
int n1, n2, n3, n4, n5, n6; //Ghi chú
float x1;
float x2;
float x3;
float x4;
float x5;
float x6;
//Vẽ lại biểu đồ
void rePaint(HWND hWnd)
{

	PAINTSTRUCT ps;
	HDC hdc = BeginPaint(hWnd, &ps);
	//665-20-20 = 625
	//1. xanh da trời
	HRGN anUong = CreateRectRgn(30, 550, 30 + (x1 * 600), 600);
	HBRUSH hbrushAnUong = CreateSolidBrush(RGB(94, 9, 247));
	FillRgn(hdc, anUong, hbrushAnUong);
	//ghi chú ăn uống
	HRGN Note1 = CreateRectRgn(30, 380, 100, 450);
	HBRUSH hbrushNote1 = CreateSolidBrush(RGB(94, 9, 247));
	FillRgn(hdc, Note1, hbrushNote1);
	//2. đỏ
	HRGN diChuyen = CreateRectRgn(30 + (x1 * 600), 550, 30 + (x1 * 600) + x2 * 600, 600);
	HBRUSH hbrushDiChuyen = CreateSolidBrush(RGB(253, 21, 3));
	FillRgn(hdc, diChuyen, hbrushDiChuyen);
	//ghi chú di chuyển
	HRGN Note2 = CreateRectRgn(30, 470, 100, 540);
	HBRUSH hbrushNote2 = CreateSolidBrush(RGB(253, 21, 3));
	FillRgn(hdc, Note2, hbrushNote2);
	//3. lá cây
	HRGN nhaCua = CreateRectRgn(30 + (x1 * 600) + x2 * 600, 550, 30 + (x1 * 600) + x2 * 600 + x3 * 600, 600);
	HBRUSH hbrushnhaCua = CreateSolidBrush(RGB(42, 249, 7));
	FillRgn(hdc, nhaCua, hbrushnhaCua);
	//Note nhà cửa
	HRGN Note3 = CreateRectRgn(220, 380, 290, 450);
	HBRUSH hbrushNote3 = CreateSolidBrush(RGB(42, 249, 7));
	FillRgn(hdc, Note3, hbrushNote3);
	//4. tím
	HRGN xeCo = CreateRectRgn(30 + (x1 * 600) + x2 * 600 + x3 * 600, 550, 30 + (x1 * 600) + x2 * 600 + x3 * 600 + x4 * 600, 600);
	HBRUSH hbrushxeCo = CreateSolidBrush(RGB(215, 41, 103));
	FillRgn(hdc, xeCo, hbrushxeCo);
	//Note xe cộ
	HRGN Note4 = CreateRectRgn(220, 470, 290, 540);
	HBRUSH hbrushNote4 = CreateSolidBrush(RGB(215, 41, 103));
	FillRgn(hdc, Note4, hbrushNote4);
	//5. đà
	HRGN nhuYeuPham = CreateRectRgn(30 + (x1 * 600) + x2 * 600 + x3 * 600 + x4 * 600, 550, 30 + (x1 * 600) + x2 * 600 + x3 * 600 + x4 * 600 + x5 * 600, 600);
	HBRUSH hbrushnhuYeuPham = CreateSolidBrush(RGB(190, 78, 66));
	FillRgn(hdc, nhuYeuPham, hbrushnhuYeuPham);
	//Note nhu yếu phẩm
	HRGN Note5 = CreateRectRgn(410, 380, 480, 450);
	HBRUSH hbrushNote5 = CreateSolidBrush(RGB(190, 78, 66));
	FillRgn(hdc, Note5, hbrushNote5);
	//6. xám
	HRGN dichVu = CreateRectRgn(30 + (x1 * 600) + x2 * 600 + x3 * 600 + x4 * 600 + x5 * 600, 550, 30 + (x1 * 600) + x2 * 600 + x3 * 600 + x4 * 600 + x5 * 600 + x6 * 600, 600);
	HBRUSH hbrushdichVu = CreateSolidBrush(RGB(129, 120, 136));
	FillRgn(hdc, dichVu, hbrushdichVu);
	//Note dịch vụ
	HRGN Note6 = CreateRectRgn(410, 470, 480, 540);
	HBRUSH hbrushNote6 = CreateSolidBrush(RGB(129, 120, 136));
	FillRgn(hdc, Note6, hbrushNote6);



	InvalidateRgn(hWnd, anUong, TRUE);
	InvalidateRgn(hWnd, diChuyen, TRUE);
	InvalidateRgn(hWnd, nhaCua, TRUE);
	InvalidateRgn(hWnd, xeCo, TRUE);
	InvalidateRgn(hWnd, nhuYeuPham, TRUE);
	InvalidateRgn(hWnd, dichVu, TRUE);

	EndPaint(hWnd, &ps);
}
LRESULT CALLBACK WndProc(HWND hWnd, UINT message, WPARAM wParam, LPARAM lParam)
{
	// Tạo đường dẫn tuyệt đối tới file config - file chỉnh size
	GetCurrentDirectory(BUFFERSIZE, curPath);
	wsprintf(configPath, L"%s\\config.ini", curPath);
	
	switch (message)
	{
	case WM_CREATE:
	{
		//Đọc file lên
		
		_dsct.ReadFile("Data.txt");
		TestSpace.ReadFile("Test.txt"); //Để bỏ lỗi hiện dòng trống khi đọc file trống
		WCHAR * Data = new WCHAR[1000000];
		
		//Trái sang, trên xuống, rộng, cao
		

		//Các Loại chi tiêu
		const TCHAR *items[] = { TEXT("Ăn uống"), TEXT("Di chuyển"), TEXT("Nhà cửa"), TEXT("Xe cộ"), TEXT("Nhu yếu phẩm"), TEXT("Dịch vụ") };

		//Menubar
		AddMenus(hWnd);

		//Tạo group box Thêm chi tiêu
		CreateWindow(TEXT("BUTTON"), TEXT("Thêm một loại chi tiêu"), WS_CHILD | WS_VISIBLE | BS_GROUPBOX, 10, 10, 665, 100, hWnd, (HMENU)0, hInst, NULL);
		//Add column cho ListView

		//Tạo combobox Loại chi tiêu
		CreateWindowEx(0, L"STATIC", L"Loại chi tiêu:", WS_CHILD | WS_VISIBLE | SS_LEFT, 20, 35, 100, 20, hWnd, NULL, hInst, NULL);
		hwndCombo = CreateWindow(TEXT("COMBOBOX"), TEXT(""), CBS_DROPDOWN | CBS_HASSTRINGS | WS_CHILD | WS_OVERLAPPED | WS_VISIBLE | LBS_NOTIFY, 20, 55, 120, 150, hWnd, NULL, hInst, NULL);
		//LBS_NOTIFY: Không cho chỉnh text trên ComboBox

		//Thêm item cho combobox loại chi tiêu
		for (int i = 0; i < 6; i++)
		{
			SendMessage(hwndCombo, (UINT)CB_ADDSTRING , (WPARAM) 0, (LPARAM)items[i]);			
		}

		//Hiển thị trên combo box là item thứ 0
		SendMessage(hwndCombo, CB_SETCURSEL, (WPARAM)0, (LPARAM)0);
		
		//Tạo textbox nội dung
		CreateWindowEx(0, L"STATIC", L"Nội dung:", WS_CHILD | WS_VISIBLE | SS_LEFT, 160, 35, 70, 20, hWnd, NULL, hInst, NULL);
		txtNoiDung = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER, 160, 55, 150, 21, hWnd, NULL, hInst, NULL);

		//Tạo textbox số tiền
		CreateWindowEx(0, L"STATIC", L"Số tiền:", WS_CHILD | WS_VISIBLE | SS_LEFT, 330, 35, 70, 20, hWnd, NULL, hInst, NULL);
		txtSoTien = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER, 330, 55, 150, 21, hWnd, NULL, hInst, NULL);

		//Button thêm
		CreateWindow(TEXT("BUTTON"), TEXT("Thêm"), WS_CHILD | WS_VISIBLE | WS_BORDER, 530, 52, 90, 25, hWnd, (HMENU)DC_BUTTONADD, hInst, NULL);
		//GroupBox danh sách chi tiêu
		CreateWindow(TEXT("BUTTON"), TEXT("Danh sách các chi tiêu"), WS_CHILD | WS_VISIBLE | BS_GROUPBOX, 10, 120, 665, 185, hWnd, (HMENU)1, hInst, NULL);

		//Tạo Listview		
		hwndListView = CreateWindowEx(0, WC_LISTVIEW, _T("List View"),	WS_CHILD | WS_VISIBLE | WS_VSCROLL | WS_TABSTOP | WS_BORDER | LVS_REPORT,
			20, 140, 645, 150, hWnd, (HMENU) IDC_LISTVIEW, hInst, NULL);
		//
		
		LVCOLUMN  lvCol1, lvCol2, lvCol3;
		lvCol1.mask = LVCF_FMT | LVCF_TEXT | LVCF_WIDTH;
		lvCol1.fmt = LVCFMT_CENTER;
		lvCol1.cx = 150;
		lvCol1.pszText = _T("Loại chi tiêu");
		ListView_InsertColumn(hwndListView, 1, &lvCol1);
		lvCol2.mask = LVCF_FMT | LVCF_TEXT | LVCF_WIDTH;
		lvCol2.fmt = LVCFMT_CENTER;
		lvCol2.cx = 290;
		lvCol2.pszText = _T("Nội dung");
		ListView_InsertColumn(hwndListView, 2, &lvCol2);
		lvCol3.mask = LVCF_FMT | LVCF_TEXT | LVCF_WIDTH;
		lvCol3.fmt = LVCFMT_RIGHT;
		lvCol3.cx = 185;
		lvCol3.pszText = _T("Số tiền (VNĐ)");
		ListView_InsertColumn(hwndListView, 3, &lvCol3);
		wstring Test = TestSpace.getDSCT(0);
		int TongTien = 0;
		for (int i = 0; i < _dsct.getSize(); i++)
		{
				TongTien += stoi(_dsct.getSoTien(i));
			
		}
		
		//Thêm item cho ListView
		for (index2 = 0;index2 < _dsct.getSize(); index2++)
		{
			//Thêm item
			LV_ITEM lv;
			lv.mask = LVIF_TEXT;
			lv.iItem = index2;
			wstring Tmp;
			Tmp = _dsct.getLoaiChiTieu(index2);
			lv.iSubItem = 0;
			lv.pszText = (LPWSTR)Tmp.c_str();
			ListView_InsertItem(hwndListView, &lv);

			lv.mask = LVIF_TEXT;
			Tmp = _dsct.getNoiDung(index2);
			lv.iSubItem = 1;
			lv.pszText = (LPWSTR)Tmp.c_str();
			ListView_SetItem(hwndListView, &lv);

			lv.mask = LVIF_TEXT;
			Tmp = _dsct.getSoTien(index2);
			lv.iSubItem = 2;
			lv.pszText = (LPWSTR)Tmp.c_str();
			ListView_SetItem(hwndListView, &lv);
		}
		
		//GroupBox Thông tin thống kê
		CreateWindow(TEXT("BUTTON"), TEXT("Thông tin thống kê"), WS_CHILD | WS_VISIBLE | BS_GROUPBOX, 10, 315, 665, 300, hWnd, (HMENU)1, hInst, NULL);
		//Tạo textbox Tổng cộng
		CreateWindowEx(0, L"STATIC", L"Tổng cộng:", WS_CHILD | WS_VISIBLE | SS_LEFT, 20, 337, 100, 20, hWnd, NULL, hInst, NULL);
		CreateWindowEx(0, L"STATIC", L"Ghi chú: Đơn vị ghi giữa các ô màu là %", WS_CHILD | WS_VISIBLE | SS_LEFT, 350, 337, 290, 20, hWnd, NULL, hInst, NULL);
		txtTongCong = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER | WS_DISABLED | SS_RIGHT, 110, 335, 120, 21, hWnd, NULL, hInst, NULL);
		CreateWindowEx(0, L"STATIC", L"VNĐ", WS_CHILD | WS_VISIBLE | SS_LEFT, 240, 337, 50, 20, hWnd, NULL, hInst, NULL);
		wstring Tmp2 = to_wstring(TongTien);
		const WCHAR * bufTong = Tmp2.c_str();
		SetWindowText(txtTongCong, (LPCWSTR) bufTong);
		//Tính phần trăm từng loại chi tiêu
		x1 = 0;
		x2 = 0;
		x3 = 0;
		x3 = 0;
		x4 = 0;
		x5 = 0;
		x6 = 0;

		AnUong = 0;
		DiChuyen = 0;
		NhaCua = 0;
		XeCo = 0;
		NhuYeuPham = 0;
		DichVu = 0;
		Tong = 0;
		wstring WstrAnUong = L"Ăn uống";
		wstring WstrDiChuyen = L"Di Chuyển";
		wstring WstrNhaCua = L"Nhà cửa";
		wstring WstrXeCo = L"Xe cộ";
		wstring WstrNhuYeuPham = L"Nhu yếu phẩm";
		wstring WstrDichVu = L"Dịch vụ";
		for (int i = 0; i < _dsct.getSize(); i++)
		{
			

			if (_dsct.getLoaiChiTieu(i).compare(WstrNhaCua) == 0)
			{
				NhaCua += stoi(_dsct.getSoTien(i));
			}
			else if (_dsct.getLoaiChiTieu(i).compare(WstrXeCo) == 0)
			{
				XeCo += stoi(_dsct.getSoTien(i));
			}
			else if (_dsct.getLoaiChiTieu(i).compare(WstrNhuYeuPham) == 0)
			{
				NhuYeuPham += stoi(_dsct.getSoTien(i));
			}
			else if (_dsct.getLoaiChiTieu(i).compare(WstrAnUong) == 0)
			{
				AnUong += stoi(_dsct.getSoTien(i));
			}
			else if (_dsct.getLoaiChiTieu(i).compare(WstrDichVu) == 0)
			{
				DichVu += stoi(_dsct.getSoTien(i));
			}
			else 
			{
				DiChuyen += stoi(_dsct.getSoTien(i));
			}

		}
		x1 = 1.0*(AnUong) / TongTien;
		x2 = 1.0*(DiChuyen) / TongTien;
		x3 = 1.0*(NhaCua) / TongTien;
		x4 = 1.0*(XeCo) / TongTien;
		x5 = 1.0*(NhuYeuPham) / TongTien;
		x6 = 1.0*(DichVu) / TongTien;
		//Note
		n1 = x1 * 100;
		n2 = x2 * 100;
		n3 = x3 * 100;
		n4 = x4 * 100;
		n5 = x5 * 100;
		n6 = x6 * 100;
		
		// Lấy font hệ thống
		LOGFONT lf;
		GetObject(GetStockObject(DEFAULT_GUI_FONT), sizeof(LOGFONT), &lf);
		HFONT hFont = CreateFont(45, lf.lfWidth,
			lf.lfEscapement, lf.lfOrientation, lf.lfWeight,
			lf.lfItalic, lf.lfUnderline, lf.lfStrikeOut, lf.lfCharSet,
			lf.lfOutPrecision, lf.lfClipPrecision, lf.lfQuality,
			lf.lfPitchAndFamily, lf.lfFaceName);

		CreateWindowEx(0, L"STATIC", L"Ăn uống", WS_CHILD | WS_VISIBLE | SS_LEFT, 110, 405, 100, 20, hWnd, NULL, hInst, NULL);
		txtN1 = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER  | SS_CENTER | WS_DISABLED, 40, 390, 50, 50, hWnd, NULL, hInst, NULL);
		SendMessage(txtN1, WM_SETFONT, WPARAM(hFont), TRUE);
		wstring Tmp3 = to_wstring(n1);
		const WCHAR * bufTmp3 = Tmp3.c_str();
		SetWindowText(txtN1, (LPCWSTR)bufTmp3);

		CreateWindowEx(0, L"STATIC", L"Di chuyển", WS_CHILD | WS_VISIBLE | SS_LEFT, 110, 495, 100, 20, hWnd, NULL, hInst, NULL);
		txtN2 = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER | SS_CENTER | WS_DISABLED, 40, 480, 50, 50, hWnd, NULL, hInst, NULL);
		SendMessage(txtN2, WM_SETFONT, WPARAM(hFont), TRUE);
		wstring Tmp4 = to_wstring(n2);
		const WCHAR * bufTmp4 = Tmp4.c_str();
		SetWindowText(txtN2, (LPCWSTR)bufTmp4);
		
		CreateWindowEx(0, L"STATIC", L"Nhà cửa", WS_CHILD | WS_VISIBLE | SS_LEFT, 300, 405, 100, 20, hWnd, NULL, hInst, NULL);
		txtN3 = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER | SS_CENTER | WS_DISABLED, 230, 390, 50, 50, hWnd, NULL, hInst, NULL);
		SendMessage(txtN3, WM_SETFONT, WPARAM(hFont), TRUE);
		wstring Tmp5 = to_wstring(n3);
		const WCHAR * bufTmp5 = Tmp5.c_str();
		SetWindowText(txtN3, (LPCWSTR)bufTmp5);

		CreateWindowEx(0, L"STATIC", L"Xe cộ", WS_CHILD | WS_VISIBLE | SS_LEFT, 300, 495, 100, 20, hWnd, NULL, hInst, NULL);
		txtN4 = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER | SS_CENTER | WS_DISABLED, 230, 480, 50, 50, hWnd, NULL, hInst, NULL);
		SendMessage(txtN4, WM_SETFONT, WPARAM(hFont), TRUE);
		wstring Tmp6 = to_wstring(n4);
		const WCHAR * bufTmp6 = Tmp6.c_str();
		SetWindowText(txtN4, (LPCWSTR)bufTmp6);

		CreateWindowEx(0, L"STATIC", L"Nhu yếu phẩm", WS_CHILD | WS_VISIBLE | SS_LEFT, 490, 405, 100, 20, hWnd, NULL, hInst, NULL);
		txtN5 = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER | SS_CENTER | WS_DISABLED, 420, 390, 50, 50, hWnd, NULL, hInst, NULL);
		SendMessage(txtN5, WM_SETFONT, WPARAM(hFont), TRUE);
		wstring Tmp7 = to_wstring(n5);
		const WCHAR * bufTmp7 = Tmp7.c_str();
		SetWindowText(txtN5, (LPCWSTR)bufTmp7);

		CreateWindowEx(0, L"STATIC", L"Dịch vụ", WS_CHILD | WS_VISIBLE | SS_LEFT, 490, 495, 100, 20, hWnd, NULL, hInst, NULL);
		txtN6 = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER | SS_CENTER | WS_DISABLED, 420, 480, 50, 50, hWnd, NULL, hInst, NULL);
		SendMessage(txtN6, WM_SETFONT, WPARAM(hFont), TRUE);
		wstring Tmp8 = to_wstring(n6);
		const WCHAR * bufTmp8 = Tmp8.c_str();
		SetWindowText(txtN6, (LPCWSTR)bufTmp8);

		break;
	}
	case WM_RBUTTONUP:
	{
		HMENU hMenu;
		POINT point; //Sử dụng cho popup menu
		point.x = LOWORD(lParam);
		point.y = HIWORD(lParam);
		hMenu = CreatePopupMenu();
		ClientToScreen(hWnd, &point);

		AppendMenu(hMenu, MF_STRING, IDM_FILE_NEW, TEXT("1412544"));
		AppendMenu(hMenu, MF_STRING, IDM_FILE_OPEN, TEXT("Phạm Đức Tiên"));
		AppendMenu(hMenu, MF_SEPARATOR, 0, NULL);
		AppendMenu(hMenu, MF_STRING, IDM_FILE_QUIT, TEXT("&Exit Program"));

		TrackPopupMenu(hMenu, TPM_RIGHTBUTTON, point.x, point.y, 0, hwnd, NULL);
		DestroyMenu(hMenu);
		break;
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
			/*hwnd = CreateWindow(wc.lpszClassName, TEXT("MoneyManagement"), WS_OVERLAPPEDWINDOW | WS_VISIBLE,
			300, 200, Width, Height, NULL, NULL, hInstance, NULL); */

			ShowWindow(hWnd, 0);
			UpdateWindow(hWnd);
			hwnd = CreateWindow(wc.lpszClassName, TEXT("MoneyManagement"), WS_OVERLAPPEDWINDOW | WS_VISIBLE,
				300, 200, Width, Height, NULL, NULL, hInstance, NULL);
			ShowWindow(hwnd, 1);
			UpdateWindow(hwnd);
			break;
		case SIZE_950x350:
			//Lấy Size WidthxHeight
			GetPrivateProfileString(L"Size2", L"width", L"Default value", bufWidth, BUFFERSIZE, configPath);
			Width = _wtoi(bufWidth);
			GetPrivateProfileString(L"Size2", L"height", L"Default value", bufHeigth, BUFFERSIZE, configPath);
			Height = _wtoi(bufHeigth);
			ShowWindow(hWnd, 0);
			UpdateWindow(hWnd);
			hwnd = CreateWindow(wc.lpszClassName, TEXT("MoneyManagement"), WS_OVERLAPPEDWINDOW | WS_VISIBLE,
				300, 200, Width, Height, NULL, NULL, hInstance, NULL);
			ShowWindow(hwnd, 1);
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
			hwnd = CreateWindow(wc.lpszClassName, TEXT("MoneyManagement"), WS_OVERLAPPEDWINDOW | WS_VISIBLE,
				300, 200, Width, Height, NULL, NULL, hInstance, NULL);
			ShowWindow(hwnd, 1);
			UpdateWindow(hwnd);
			break;
		case DC_BUTTONADD:
		{
			int TestSoTien = 1;
			DataOfManagement Temp;
			IsAdd = 1;
			LV_ITEM lv;
			lv.iItem = _dsct.getSize();
			//Lấy dữ liệu Loại chi tiêu
			sizeLoaiChiTieu = GetWindowTextLength(hwndCombo);
			bufLoaiChiTieu = new WCHAR[sizeLoaiChiTieu + 1];
			GetWindowText(hwndCombo, bufLoaiChiTieu, sizeLoaiChiTieu + 1);

			//Lấy dữ liệu Nội dung
			sizeNoiDung = GetWindowTextLength(txtNoiDung);
			bufNoiDung = new WCHAR[sizeNoiDung + 1];
			GetWindowText(txtNoiDung, bufNoiDung, sizeNoiDung + 1);

			//Lấy dữ liệu Số tiền
			sizeSoTien = GetWindowTextLength(txtSoTien);
			bufSoTien = new WCHAR[sizeSoTien + 1];
			GetWindowText(txtSoTien, bufSoTien, sizeSoTien + 1);

			//Test lỗi
			if (sizeNoiDung == 0)
			{
				MessageBox(hWnd, L"Kiểm tra lại mục nội dung đã điền chưa?", L"THÔNG BÁO", 0);
				//wsprintf(bufThongBao, L"Kiểm tra lại mục tiền gửi và lãi suất đã điền chưa");
			}
			else if (sizeSoTien == 0)
			{
				MessageBox(hWnd, L"Kiểm tra lại mục số tiền đã điền chưa?", L"THÔNG BÁO", 0);
			}
			else
			{
				for (int i = 0; i < sizeSoTien; i++)
				{
					if ((bufSoTien[i] < 48 || bufSoTien[i] > 57))
					{
						TestSoTien = 0;
					}
				}
				if (TestSoTien == 0)
				{
					MessageBox(hWnd, L"Chỉ điền integer cho Số tiền", L"THÔNG BÁO", 0);
				}
				else
				{
					lv.mask = LVIF_TEXT;
					lv.iSubItem = 0;
					lv.pszText = (LPWSTR)bufLoaiChiTieu;
					ListView_InsertItem(hwndListView, &lv);
					Temp.setLoaiChiTieu(bufLoaiChiTieu);



					lv.mask = LVIF_TEXT;
					lv.iSubItem = 1;
					lv.pszText = (LPWSTR)bufNoiDung;
					ListView_SetItem(hwndListView, &lv);
					Temp.setNoiDung(bufNoiDung);


					lv.mask = LVIF_TEXT;
					lv.iSubItem = 2;
					lv.pszText = (LPWSTR)bufSoTien;
					ListView_SetItem(hwndListView, &lv);
					Temp.setSoTien(bufSoTien);
					//
					_dsct.Push_Item(Temp);
					SetWindowText(txtNoiDung, NULL);
					SetWindowText(txtSoTien, NULL);
					//
					int TongTien = 0;
					for (int i = 0; i < _dsct.getSize(); i++)
					{
							TongTien += stoi(_dsct.getSoTien(i));
						
					}
					wstring Tmp2 = to_wstring(TongTien);
					const WCHAR * bufTong = Tmp2.c_str();
					SetWindowText(txtTongCong, (LPCWSTR)bufTong);
					//
					//
					//Tính phần trăm từng loại chi tiêu
					x1 = 0;
					x2 = 0;
					x3 = 0;
					x3 = 0;
					x4 = 0;
					x5 = 0;
					x6 = 0;

					AnUong = 0;
					DiChuyen = 0;
					NhaCua = 0;
					XeCo = 0;
					NhuYeuPham = 0;
					DichVu = 0;
					Tong = 0;
					wstring WstrAnUong = L"Ăn uống";
					wstring WstrDiChuyen = L"Di Chuyển";
					wstring WstrNhaCua = L"Nhà cửa";
					wstring WstrXeCo = L"Xe cộ";
					wstring WstrNhuYeuPham = L"Nhu yếu phẩm";
					wstring WstrDichVu = L"Dịch vụ";
					for (int i = 0; i < _dsct.getSize(); i++)
					{
						if (_dsct.getLoaiChiTieu(i).compare(WstrAnUong) == 0)
						{
							AnUong += stoi(_dsct.getSoTien(i));
						}

						else if (_dsct.getLoaiChiTieu(i).compare(WstrNhaCua) == 0)
						{
							NhaCua += stoi(_dsct.getSoTien(i));
						}
						else if (_dsct.getLoaiChiTieu(i).compare(WstrXeCo) == 0)
						{
							XeCo += stoi(_dsct.getSoTien(i));
						}
						else if (_dsct.getLoaiChiTieu(i).compare(WstrNhuYeuPham) == 0)
						{
							NhuYeuPham += stoi(_dsct.getSoTien(i));
						}
						else if (_dsct.getLoaiChiTieu(i).compare(WstrDichVu) == 0)
						{
							DichVu += stoi(_dsct.getSoTien(i));
						}
						else 
						{
							DiChuyen += stoi(_dsct.getSoTien(i));
						}

					}
					x1 = 1.0*(AnUong) / TongTien;
					x2 = 1.0*(DiChuyen) / TongTien;
					x3 = 1.0*(NhaCua) / TongTien;
					x4 = 1.0*(XeCo) / TongTien;
					x5 = 1.0*(NhuYeuPham) / TongTien;
					x6 = 1.0*(DichVu) / TongTien;
					//
					rePaint(hWnd);
					//Note
					n1 = x1 * 100;
					n2 = x2 * 100;
					n3 = x3 * 100;
					n4 = x4 * 100;
					n5 = x5 * 100;
					n6 = x6 * 100;

					// Lấy font hệ thống
					LOGFONT lf;
					GetObject(GetStockObject(DEFAULT_GUI_FONT), sizeof(LOGFONT), &lf);
					HFONT hFont = CreateFont(45, lf.lfWidth,
						lf.lfEscapement, lf.lfOrientation, lf.lfWeight,
						lf.lfItalic, lf.lfUnderline, lf.lfStrikeOut, lf.lfCharSet,
						lf.lfOutPrecision, lf.lfClipPrecision, lf.lfQuality,
						lf.lfPitchAndFamily, lf.lfFaceName);

					CreateWindowEx(0, L"STATIC", L"Ăn uống", WS_CHILD | WS_VISIBLE | SS_LEFT, 110, 405, 100, 20, hWnd, NULL, hInst, NULL);
					txtN1 = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER | SS_CENTER | WS_DISABLED, 40, 390, 50, 50, hWnd, NULL, hInst, NULL);
					SendMessage(txtN1, WM_SETFONT, WPARAM(hFont), TRUE);
					wstring Tmp3 = to_wstring(n1);
					const WCHAR * bufTmp3 = Tmp3.c_str();
					SetWindowText(txtN1, (LPCWSTR)bufTmp3);

					CreateWindowEx(0, L"STATIC", L"Di chuyển", WS_CHILD | WS_VISIBLE | SS_LEFT, 110, 495, 100, 20, hWnd, NULL, hInst, NULL);
					txtN2 = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER | SS_CENTER | WS_DISABLED, 40, 480, 50, 50, hWnd, NULL, hInst, NULL);
					SendMessage(txtN2, WM_SETFONT, WPARAM(hFont), TRUE);
					wstring Tmp4 = to_wstring(n2);
					const WCHAR * bufTmp4 = Tmp4.c_str();
					SetWindowText(txtN2, (LPCWSTR)bufTmp4);

					CreateWindowEx(0, L"STATIC", L"Nhà cửa", WS_CHILD | WS_VISIBLE | SS_LEFT, 300, 405, 100, 20, hWnd, NULL, hInst, NULL);
					txtN3 = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER | SS_CENTER | WS_DISABLED, 230, 390, 50, 50, hWnd, NULL, hInst, NULL);
					SendMessage(txtN3, WM_SETFONT, WPARAM(hFont), TRUE);
					wstring Tmp5 = to_wstring(n3);
					const WCHAR * bufTmp5 = Tmp5.c_str();
					SetWindowText(txtN3, (LPCWSTR)bufTmp5);

					CreateWindowEx(0, L"STATIC", L"Xe cộ", WS_CHILD | WS_VISIBLE | SS_LEFT, 300, 495, 100, 20, hWnd, NULL, hInst, NULL);
					txtN4 = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER | SS_CENTER | WS_DISABLED, 230, 480, 50, 50, hWnd, NULL, hInst, NULL);
					SendMessage(txtN4, WM_SETFONT, WPARAM(hFont), TRUE);
					wstring Tmp6 = to_wstring(n4);
					const WCHAR * bufTmp6 = Tmp6.c_str();
					SetWindowText(txtN4, (LPCWSTR)bufTmp6);

					CreateWindowEx(0, L"STATIC", L"Nhu yếu phẩm", WS_CHILD | WS_VISIBLE | SS_LEFT, 490, 405, 100, 20, hWnd, NULL, hInst, NULL);
					txtN5 = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER | SS_CENTER | WS_DISABLED, 420, 390, 50, 50, hWnd, NULL, hInst, NULL);
					SendMessage(txtN5, WM_SETFONT, WPARAM(hFont), TRUE);
					wstring Tmp7 = to_wstring(n5);
					const WCHAR * bufTmp7 = Tmp7.c_str();
					SetWindowText(txtN5, (LPCWSTR)bufTmp7);

					CreateWindowEx(0, L"STATIC", L"Dịch vụ", WS_CHILD | WS_VISIBLE | SS_LEFT, 490, 495, 100, 20, hWnd, NULL, hInst, NULL);
					txtN6 = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER | SS_CENTER | WS_DISABLED, 420, 480, 50, 50, hWnd, NULL, hInst, NULL);
					SendMessage(txtN6, WM_SETFONT, WPARAM(hFont), TRUE);
					wstring Tmp8 = to_wstring(n6);
					const WCHAR * bufTmp8 = Tmp8.c_str();
					SetWindowText(txtN6, (LPCWSTR)bufTmp8);
					//
					
					//
					MessageBox(hWnd, L"Thêm thành công!", L"THÔNG BÁO", 0);
					
				}
			}
			break;
		}
		}

	}
	break;
	case WM_PAINT:
	{
		
		PAINTSTRUCT ps;
		HDC hdc = BeginPaint(hWnd, &ps);
		//665-20-20 = 625
		//1. xanh da trời
		HRGN anUong = CreateRectRgn(30, 550, 30 +(x1 * 600), 600);
		HBRUSH hbrushAnUong = CreateSolidBrush(RGB(94, 9, 247));
		FillRgn(hdc, anUong, hbrushAnUong);
		//ghi chú ăn uống
		HRGN Note1 = CreateRectRgn(30, 380, 100, 450);
		HBRUSH hbrushNote1 = CreateSolidBrush(RGB(94, 9, 247));
		FillRgn(hdc, Note1, hbrushNote1);
		//2. đỏ
		HRGN diChuyen = CreateRectRgn(30 + (x1 * 600), 550, 30 + (x1 * 600) + x2 * 600, 600);
		HBRUSH hbrushDiChuyen = CreateSolidBrush(RGB(253, 21, 3));
		FillRgn(hdc, diChuyen, hbrushDiChuyen);
		//ghi chú di chuyển
		HRGN Note2 = CreateRectRgn(30, 470, 100, 540);
		HBRUSH hbrushNote2 = CreateSolidBrush(RGB(253, 21, 3));
		FillRgn(hdc, Note2, hbrushNote2);
		//3. lá cây
		HRGN nhaCua = CreateRectRgn(30 + (x1 * 600) + x2 * 600, 550, 30 + (x1 * 600) + x2 * 600 + x3 * 600, 600);
		HBRUSH hbrushnhaCua = CreateSolidBrush(RGB(42, 249, 7));
		FillRgn(hdc, nhaCua, hbrushnhaCua);
		//Note nhà cửa
		HRGN Note3 = CreateRectRgn(220, 380, 290, 450);
		HBRUSH hbrushNote3 = CreateSolidBrush(RGB(42, 249, 7));
		FillRgn(hdc, Note3, hbrushNote3);
		//4. tím
		HRGN xeCo = CreateRectRgn(30 + (x1 * 600) + x2 * 600 + x3 * 600, 550, 30 + (x1 * 600) + x2 * 600 + x3 * 600 + x4 * 600, 600);
		HBRUSH hbrushxeCo = CreateSolidBrush(RGB(215, 41, 103));
		FillRgn(hdc, xeCo, hbrushxeCo);
		//Note xe cộ
		HRGN Note4 = CreateRectRgn(220, 470, 290, 540);
		HBRUSH hbrushNote4 = CreateSolidBrush(RGB(215, 41, 103));
		FillRgn(hdc, Note4, hbrushNote4);
		//5. đà
		HRGN nhuYeuPham = CreateRectRgn(30 + (x1 * 600) + x2 * 600 + x3 * 600 + x4 * 600, 550, 30 + (x1 * 600) + x2 * 600 + x3 * 600 + x4 * 600 + x5 * 600, 600);
		HBRUSH hbrushnhuYeuPham = CreateSolidBrush(RGB(190, 78, 66));
		FillRgn(hdc, nhuYeuPham, hbrushnhuYeuPham);
		//Note nhu yếu phẩm
		HRGN Note5 = CreateRectRgn(410, 380, 480, 450);
		HBRUSH hbrushNote5 = CreateSolidBrush(RGB(190, 78, 66));
		FillRgn(hdc, Note5, hbrushNote5);
		//6. xám
		HRGN dichVu = CreateRectRgn(30 + (x1 * 600) + x2 * 600 + x3 * 600 + x4 * 600 + x5 * 600, 550, 30 + (x1 * 600) + x2 * 600 + x3 * 600 + x4 * 600 + x5 * 600 + x6 * 600, 600);
		HBRUSH hbrushdichVu = CreateSolidBrush(RGB(129, 120, 136));
		FillRgn(hdc, dichVu, hbrushdichVu);
		//Note dịch vụ
		HRGN Note6 = CreateRectRgn(410, 470, 480, 540);
		HBRUSH hbrushNote6 = CreateSolidBrush(RGB(129, 120, 136));
		FillRgn(hdc, Note6, hbrushNote6);
		
		EndPaint(hWnd, &ps);
	}
	break;
	case WM_DESTROY:
	{
		//Ghi file
		if (IsAdd == 1)
		{
			_dsct.WriteFile("Data.txt");
		}
		
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

