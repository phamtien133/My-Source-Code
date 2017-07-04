// TipsCalculatorHelper.cpp : Defines the entry point for the application.
//

#include "stdafx.h"
#include "SavingInterest.h"
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

	wc.lpszClassName = TEXT("SavingInterest"); //Tên class, tên cửa sổ
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
	hwnd = CreateWindow(wc.lpszClassName, TEXT("SavingInterestApp"), WS_OVERLAPPEDWINDOW | WS_VISIBLE,
		300, 200, 850, 250, NULL, NULL, hInstance, NULL); //Vị trí từ trái, vị trí từ trên, rộng, cao
														  //Hiển thị và update cửa sổ
	ShowWindow(hwnd, nCmdShow);
	UpdateWindow(hwnd);
	HACCEL hAccelTable = LoadAccelerators(hInstance, MAKEINTRESOURCE(IDC_SAVINGINTEREST));
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
	wcex.hIcon = LoadIcon(hInstance, MAKEINTRESOURCE(IDI_SAVINGINTERESTAPP));
	wcex.hCursor = LoadCursor(nullptr, IDC_ARROW);
	wcex.hbrBackground = (HBRUSH)(COLOR_WINDOW + 1);
	wcex.lpszMenuName = MAKEINTRESOURCEW(IDC_SAVINGINTEREST);
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
HWND txtTien;
HWND txtLai;
HWND txtThang;
HWND txtNam;
HWND txtLaiThang;
HWND txtLaiNam;
HWND txtThongBao;
//HMENU DC_BUTTONS2;

WCHAR* bufTien = NULL;
WCHAR* bufLai = NULL;
WCHAR* bufThang = NULL;
WCHAR* bufNam = NULL;
WCHAR* bufLaiThang = NULL;
WCHAR* bufLaiNam = NULL;
WCHAR* bufThongBao = NULL;

int sizeTien;
int sizeLai;
int sizeThang;
int sizeNam;
int sizeLaiThang;
int sizeLaiNam;
int sizeThongBao;

int Tien;
int Lai;
int Thang;
int Nam;
int LaiThang;
int LaiNam;
int LaiThongBao;


LRESULT CALLBACK WndProc(HWND hWnd, UINT message, WPARAM wParam, LPARAM lParam)
{
	LaiThang = 0;
	LaiNam = 0;
	switch (message)
	{
	case WM_CREATE:
	{
		AddMenus(hWnd);


		//Trái sang, trên xuống, rộng, cao
		//Tiền gửi
		CreateWindowEx(0, L"STATIC", L"Tiền gửi:", WS_CHILD | WS_VISIBLE | SS_LEFT, 20, 30, 100, 20, hWnd, NULL, hInst, NULL);
		txtTien = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER, 170, 30, 200, 20, hWnd, NULL, hInst, NULL);
		//Lãi suất
		CreateWindowEx(0, L"STATIC", L"Lãi suất (%):", WS_CHILD | WS_VISIBLE | SS_LEFT, 20, 60, 150, 20, hWnd, NULL, hInst, NULL);
		txtLai = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER, 170, 60, 200, 20, hWnd, NULL, hInst, NULL);
		//Số tháng
		CreateWindowEx(0, L"STATIC", L"Số tháng gửi:", WS_CHILD | WS_VISIBLE | SS_LEFT, 20, 90, 150, 20, hWnd, NULL, hInst, NULL);
		txtThang = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER, 170, 90, 200, 20, hWnd, NULL, hInst, NULL);
		//Số năm
		CreateWindowEx(0, L"STATIC", L"Số năm gửi:", WS_CHILD | WS_VISIBLE | SS_LEFT, 450, 90, 150, 20, hWnd, NULL, hInst, NULL);
		txtNam = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER, 600, 90, 200, 20, hWnd, NULL, hInst, NULL);
		//Lãi theo tháng
		CreateWindow(TEXT("button"), TEXT("Số tiền theo tháng:"), WS_VISIBLE | WS_CHILD, 10, 117, 135, 25, hWnd, (HMENU)DC_BUTTONS, NULL, NULL);
		txtLaiThang = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER | WS_DISABLED, 170, 120, 200, 20, hWnd, NULL, hInst, NULL);
		//Lãi theo năm
		CreateWindow(TEXT("button"), TEXT("Số tiền theo năm:"), WS_VISIBLE | WS_CHILD, 440, 117, 135, 25, hWnd, (HMENU)DC_BUTTONS2, NULL, NULL);
		txtLaiNam = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER | WS_DISABLED,600, 120, 200, 20, hWnd, NULL, hInst, NULL);
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
		case DC_BUTTONS: //Lãi theo tháng
		{
			int TestTien, TestLai, TestThang, TestNam, TestLaiThang, TestLaiNam;
			TestTien = 1;
			TestLai = 1;
			TestThang = 1;
			TestNam = 1;
			TestLaiThang = 1;
			TestLaiNam = 1;

			sizeTien = GetWindowTextLength(txtTien);
			sizeLai = GetWindowTextLength(txtLai);
			sizeThang = GetWindowTextLength(txtThang);
			sizeNam = GetWindowTextLength(txtNam);
			LaiThang = 0;
			LaiNam = 0;

			bufTien = new WCHAR[sizeTien + 1];
			bufLai = new WCHAR[sizeLai + 1];
			bufThang = new WCHAR[sizeThang + 1];
			bufNam = new WCHAR[sizeNam + 1];
			bufLaiThang = new WCHAR[255];
			bufLaiNam = new WCHAR[255];
			bufThongBao = new WCHAR[255];

			GetWindowText(txtTien, bufTien, sizeTien + 1);
			GetWindowText(txtLai, bufLai, sizeLai + 1);
			GetWindowText(txtThang, bufThang, sizeThang + 1);
			GetWindowText(txtNam, bufNam, sizeNam + 1);
			
			if (sizeTien == 0 || sizeLai == 0)
			{
				wsprintf(bufThongBao, L"Kiểm tra lại mục tiền gửi và lãi suất đã điền chưa");
			}
			else if (sizeThang == 0)
			{
				wsprintf(bufThongBao, L"Vui lòng điền số tháng");
			}
			else
			{
				for (int i = 0; i < sizeTien; i++)
				{
					if ((bufTien[i] < 48 || bufTien[i] > 57))
					{
						TestTien = 0;
					}
				}
				for (int i = 0; i < sizeLai; i++)
				{
					if (bufLai[i] < 48 || bufLai[i] > 57)
					{
						TestLai = 0;
					}
				}
				for (int i = 0; i < sizeThang; i++)
				{
					if (bufThang[i] < 48 || bufThang[i] > 57)
					{
						TestThang = 0;
					}
				}
				if (TestTien == 0 && TestLai == 0 &&TestThang == 0)
				{
					wsprintf(bufThongBao, L"Chỉ điền số integer cho tiền gửi, lãi suất và số tháng");
				}
				else if (TestTien == 0)
				{
					wsprintf(bufThongBao, L"Chỉ điền số integer cho tiền gửi");
				}
				else if (TestLai == 0)
				{
					wsprintf(bufThongBao, L"Chỉ điền số integer cho lãi suất");
				}
				else if (TestThang == 0)
				{
					wsprintf(bufThongBao, L"Chỉ điền số integer cho số tháng");
				}
				else
				{
					Tien = _wtoi(bufTien);
					Lai = _wtoi(bufLai);
					Thang = _wtoi(bufThang);

					if (Tien < 500)
					{
						wsprintf(bufThongBao, L"Tiền gửi phải lớn hơn 500 VNĐ");
					}
					else if (Lai == 0)
					{
						wsprintf(bufThongBao, L"Lãi suất phải khác 0");
					}
					else if (Thang == 0)
					{
						wsprintf(bufThongBao, L"Tháng phải khác 0");
					}
					else
					{
						wsprintf(bufThongBao, L"Thành công");
						LaiThang = Tien + (Tien * (1.0*(1.0*Lai/100)/30) *Thang *30);
						wsprintf(bufLaiThang, L"%d VND", LaiThang);
						SetWindowText(txtLaiThang, bufLaiThang);
					}
				}
			}
			SetWindowText(txtThongBao, bufThongBao);
			
			break;
		}
		case DC_BUTTONS2:	//Lãi theo năm
		{
			int TestTien, TestLai, TestThang, TestNam, TestLaiThang, TestLaiNam;
			TestTien = 1;
			TestLai = 1;
			TestThang = 1;
			TestNam = 1;
			TestLaiThang = 1;
			TestLaiNam = 1;

			sizeTien = GetWindowTextLength(txtTien);
			sizeLai = GetWindowTextLength(txtLai);
			sizeThang = GetWindowTextLength(txtThang);
			sizeNam = GetWindowTextLength(txtNam);
			LaiThang = 0;
			LaiNam = 0;

			bufTien = new WCHAR[sizeTien + 1];
			bufLai = new WCHAR[sizeLai + 1];
			bufThang = new WCHAR[sizeThang + 1];
			bufNam = new WCHAR[sizeNam + 1];
			bufLaiThang = new WCHAR[255];
			bufLaiNam = new WCHAR[255];
			bufThongBao = new WCHAR[255];

			GetWindowText(txtTien, bufTien, sizeTien + 1);
			GetWindowText(txtLai, bufLai, sizeLai + 1);
			GetWindowText(txtThang, bufThang, sizeThang + 1);
			GetWindowText(txtNam, bufNam, sizeNam + 1);

			if (sizeTien == 0 || sizeLai == 0)
			{
				wsprintf(bufThongBao, L"Kiểm tra lại mục tiền gửi và lãi suất đã điền chưa");
			}
			else if (sizeNam == 0)
			{
				wsprintf(bufThongBao, L"Vui lòng điền số năm");
			}
			else
			{
				for (int i = 0; i < sizeTien; i++)
				{
					if ((bufTien[i] < 48 || bufTien[i] > 57))
					{
						TestTien = 0;
					}
				}
				for (int i = 0; i < sizeLai; i++)
				{
					if (bufLai[i] < 48 || bufLai[i] > 57)
					{
						TestLai = 0;
					}
				}
				for (int i = 0; i < sizeNam; i++)
				{
					if (bufNam[i] < 48 || bufNam[i] > 57)
					{
						TestNam = 0;
					}
				}
				if (TestTien == 0 && TestLai == 0 && TestNam == 0)
				{
					wsprintf(bufThongBao, L"Chỉ điền số integer cho tiền gửi, lãi suất và số tháng");
				}
				else if (TestTien == 0)
				{
					wsprintf(bufThongBao, L"Chỉ điền số integer cho tiền gửi");
				}
				else if (TestLai == 0)
				{
					wsprintf(bufThongBao, L"Chỉ điền số integer cho lãi suất");
				}
				else if (TestNam == 0)
				{
					wsprintf(bufThongBao, L"Chỉ điền số integer cho số năm");
				}
				else
				{
					Tien = _wtoi(bufTien);
					Lai = _wtoi(bufLai);
					Nam = _wtoi(bufNam);

					if (Tien < 500)
					{
						wsprintf(bufThongBao, L"Tiền gửi phải lớn hơn 500 VNĐ");
					}
					else if (Lai == 0)
					{
						wsprintf(bufThongBao, L"Lãi suất phải khác 0");
					}
					else if (Nam == 0)
					{
						wsprintf(bufThongBao, L"Năm phải khác 0");
					}
					else
					{
						wsprintf(bufThongBao, L"Thành công");
						LaiNam = Tien + (Tien * (1.0*(1.0*Lai / 100) / 12) *Nam);
						wsprintf(bufLaiNam, L"%d VND", LaiNam);
						SetWindowText(txtLaiNam, bufLaiNam);
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
