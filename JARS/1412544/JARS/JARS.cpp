// TipsCalculatorHelper.cpp : Defines the entry point for the application.
//

#include "stdafx.h"
#include "JARS.h"
#include "windows.h" //header của chương trình, chứa việc gọi các hàm API, các macro và dữ liệu cơ bản
#include <winuser.h>
#include <commctrl.h>

#define MAX_LOADSTRING 100
//Define ID
#define IDM_FILE_NEW 1
#define IDM_FILE_OPEN 2
#define IDM_FILE_QUIT 3
void AddMenus(HWND);
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

	// TODO: Place code here.
	MSG  msg;
	HWND hwnd;
	WNDCLASS wc;
	//******************** Tạo Form ********************
	wc.style = CS_HREDRAW | CS_VREDRAW; //	Style cửa sổ, CS_HREDRAW và CS_VREDRAW được thiết lập vẽ lại kt cửa sổ
										// Không sử dụng các byte bổ sung (additional bytes). Nên ta đặt chúng bằng 0
	wc.cbClsExtra = 0;
	wc.cbWndExtra = 0;

	wc.lpszClassName = TEXT("JARS"); //Tên class, tên cửa sổ
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
	hwnd = CreateWindow(wc.lpszClassName, TEXT("JARSapp"), WS_OVERLAPPEDWINDOW | WS_VISIBLE,
		300, 200, 850, 250, NULL, NULL, hInstance, NULL); //Vị trí từ trái, vị trí từ trên, rộng, cao
														  //Hiển thị và update cửa sổ
	ShowWindow(hwnd, nCmdShow);
	UpdateWindow(hwnd);
	HACCEL hAccelTable = LoadAccelerators(hInstance, MAKEINTRESOURCE(IDC_JARS));
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
	HMENU hMenubar;
	HMENU hMenu;
	hMenubar = CreateMenu();
	hMenu = CreateMenu();
	//Tạo ra từng menu
	AppendMenu(hMenu, MF_STRING, IDM_FILE_NEW, TEXT("1412544"));
	AppendMenu(hMenu, MF_STRING, IDM_FILE_OPEN, TEXT("Phạm Đức Tiên"));
	AppendMenu(hMenu, MF_SEPARATOR, 0, NULL); //Đường kẻ giữa
	AppendMenu(hMenu, MF_STRING, IDM_FILE_QUIT, TEXT("Thanks for using my program... (Exit)"));
	AppendMenu(hMenubar, MF_POPUP, (UINT_PTR)hMenu, TEXT("About me"));
	SetMenu(hwnd, hMenubar); //Gắn menu item lên menubar
}

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
	wcex.hIcon = LoadIcon(hInstance, MAKEINTRESOURCE(IDI_JARSAPP));
	wcex.hCursor = LoadCursor(nullptr, IDC_ARROW);
	wcex.hbrBackground = (HBRUSH)(COLOR_WINDOW + 1);
	wcex.lpszMenuName = MAKEINTRESOURCEW(IDC_JARS);
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
HWND txtTongTien;						//Tổng tiền có
HWND txtChiTieuThietYeu;				//Chi tiêu thiết yếu 55%
HWND txtTuDoTaiChinh;					//Tự do tài chính 10%
HWND txtGiaoDuc;						//Giáo dục 10%
HWND txtChiTieuDaiHan;					//Chi tiêu dài hạn 10%
HWND txtTanHuong;						//Tận hưởng 10%
HWND txtChoDi;							//Cho đi 5%
HWND txtThongBao;						//Thông báo: kết quả của sự kiện


WCHAR* bufTongTien = NULL;
WCHAR* bufChiTieuThietYeu = NULL;
WCHAR* bufTuDoTaiChinh = NULL;
WCHAR* bufGiaoDuc = NULL;
WCHAR* bufChiTieuDaiHan = NULL;
WCHAR* bufTanHuong = NULL;
WCHAR* bufChoDi = NULL;
WCHAR* bufThongBao = NULL;

int sizeTongTien;
int sizeChiTieuThietYeu;
int sizeTuDoTaiChinh;
int sizeGiaoDuc;
int sizeChiTieuDaiHan;
int sizeTanHuong;
int sizeChoDi;
int sizeThongBao;

int TongTien;
int ChiTieuThietYeu;
int TuDoTaiChinh;
int GiaoDuc;
int ChiTieuDaiHan;
int TanHuong;
int ChoDi;
int LaiThongBao;


LRESULT CALLBACK WndProc(HWND hWnd, UINT message, WPARAM wParam, LPARAM lParam)
{
	//Gán bằng 0 để chờ kiểm tra dữ liệu
	ChiTieuThietYeu = 0;
	TuDoTaiChinh = 0;
	GiaoDuc = 0;
	ChiTieuDaiHan = 0;
	TanHuong = 0;
	ChoDi = 0;

	switch (message)
	{
	case WM_CREATE:
	{
		AddMenus(hWnd);
		//Trái sang, trên xuống, rộng, cao
		//Tổng tiền
		CreateWindowEx(0, L"STATIC", L"Tổng tiền:", WS_CHILD | WS_VISIBLE | SS_LEFT, 20, 30, 100, 20, hWnd, NULL, hInst, NULL);
		txtTongTien = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER, 170, 30, 340, 20, hWnd, NULL, hInst, NULL);
		//Chi tiêu thiết yếu
		CreateWindowEx(0, L"STATIC", L"Chi tiêu thiết yếu:", WS_CHILD | WS_VISIBLE | SS_LEFT, 20, 60, 150, 20, hWnd, NULL, hInst, NULL);
		txtChiTieuThietYeu = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_DISABLED, 170, 60, 200, 20, hWnd, NULL, hInst, NULL);
		//Tự do tài chính
		CreateWindowEx(0, L"STATIC", L"Tự do tài chính:", WS_CHILD | WS_VISIBLE | SS_LEFT, 20, 90, 150, 20, hWnd, NULL, hInst, NULL);
		txtTuDoTaiChinh = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_DISABLED, 170, 90, 200, 20, hWnd, NULL, hInst, NULL);
		//Giáo dục
		CreateWindowEx(0, L"STATIC", L"Giáo dục:", WS_CHILD | WS_VISIBLE | SS_LEFT, 450, 60, 150, 20, hWnd, NULL, hInst, NULL);
		txtGiaoDuc = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_DISABLED, 600, 60, 200, 20, hWnd, NULL, hInst, NULL);
		//Chi tiêu dài hạn
		CreateWindowEx(0, L"STATIC", L"Chi tiêu dài hạn:", WS_CHILD | WS_VISIBLE | SS_LEFT, 450, 90, 150, 20, hWnd, NULL, hInst, NULL);
		txtChiTieuDaiHan = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_DISABLED, 600, 90, 200, 20, hWnd, NULL, hInst, NULL);
		//Tận hưởng
		CreateWindowEx(0, L"STATIC", L"Tận hưởng:", WS_CHILD | WS_VISIBLE | SS_LEFT, 450, 120, 150, 20, hWnd, NULL, hInst, NULL);
		txtTanHuong = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_DISABLED, 600, 120, 200, 20, hWnd, NULL, hInst, NULL);
		//Cho đi
		CreateWindowEx(0, L"STATIC", L"Cho đi:", WS_CHILD | WS_VISIBLE | SS_LEFT, 20, 120, 150, 20, hWnd, NULL, hInst, NULL);
		txtChoDi = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_DISABLED, 170, 120, 200, 20, hWnd, NULL, hInst, NULL);
		//Button chia theo JARS
		CreateWindow(TEXT("button"), TEXT("Chia tiền theo JARS"), WS_VISIBLE | WS_CHILD, 550, 23, 180, 30, hWnd, (HMENU)DC_BUTTONS, NULL, NULL);
		//Thông báo
		CreateWindowEx(0, L"STATIC", L"Thông báo:", WS_CHILD | WS_VISIBLE | SS_LEFT, 20, 150, 150, 20, hWnd, NULL, hInst, NULL);
		txtThongBao = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER | WS_DISABLED, 170, 150, 630, 20, hWnd, NULL, hInst, NULL);
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
		case DC_BUTTONS: //Chia tiền theo JARS
		{
			int TestTongTien; //Kiểm tra tổng tiền có hợp lệ
			TestTongTien = 1;

			sizeTongTien = GetWindowTextLength(txtTongTien);

			bufTongTien = new WCHAR[sizeTongTien + 1];
			bufChiTieuThietYeu = new WCHAR[255];
			bufTuDoTaiChinh = new WCHAR[255];
			bufGiaoDuc = new WCHAR[255];
			bufChiTieuDaiHan = new WCHAR[255];
			bufTanHuong = new WCHAR[255];
			bufChoDi = new WCHAR[255];
			bufThongBao = new WCHAR[255];

			GetWindowText(txtTongTien, bufTongTien, sizeTongTien + 1);

			if (sizeTongTien == 0)
			{
				wsprintf(bufThongBao, L"Kiểm tra lại mục tổng tiền đã điền chưa");
			}
			else
			{
				for (int i = 0; i < sizeTongTien; i++)
				{
					if ((bufTongTien[i] < 48 || bufTongTien[i] > 57))
					{
						TestTongTien = 0;
					}
				}
				if (TestTongTien == 0)
				{
					wsprintf(bufThongBao, L"Chỉ điền số integer cho tổng tiền");
				}
				else
				{
					TongTien = _wtoi(bufTongTien);
					
					if (TongTien < 10000)
					{
						wsprintf(bufThongBao, L"Tổng tiền phải lớn hơn 10000 VNĐ");
					}
					else
					{
						wsprintf(bufThongBao, L"Thành công");
						//Chi tiêu thiết yếu 55%
						ChiTieuThietYeu = 0.55 * TongTien;
						wsprintf(bufChiTieuThietYeu, L"%d VND", ChiTieuThietYeu);
						SetWindowText(txtChiTieuThietYeu, bufChiTieuThietYeu);
						//Số để chia %
						int SoTien = 0.1 * TongTien;
						//Tự do tài chính 10%
						TuDoTaiChinh = SoTien;
						wsprintf(bufTuDoTaiChinh, L"%d VND", TuDoTaiChinh);
						SetWindowText(txtTuDoTaiChinh, bufTuDoTaiChinh);
						//Giáo dục 10%
						GiaoDuc = SoTien;
						wsprintf(bufGiaoDuc, L"%d VND", GiaoDuc);
						SetWindowText(txtGiaoDuc, bufGiaoDuc);
						//Chi tiêu dài hạn 10%
						ChiTieuDaiHan = SoTien;
						wsprintf(bufChiTieuDaiHan, L"%d VND", ChiTieuDaiHan);
						SetWindowText(txtChiTieuDaiHan, bufChiTieuDaiHan);
						//Tận hưởng 10%
						TanHuong = SoTien;
						wsprintf(bufTanHuong, L"%d VND", TanHuong);
						SetWindowText(txtTanHuong, bufTanHuong);
						//Cho đi 5%
						ChoDi = 0.5 * SoTien;
						wsprintf(bufChoDi, L"%d VND", ChoDi);
						SetWindowText(txtChoDi, bufChoDi);
					}
				}
			}
			SetWindowText(txtThongBao, bufThongBao);

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
