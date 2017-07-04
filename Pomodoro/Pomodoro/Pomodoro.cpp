// TipsCalculatorHelper.cpp : Defines the entry point for the application.
//

#include "stdafx.h"
#include "Pomodoro.h"
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

	wc.lpszClassName = TEXT("Pomodoro"); //Tên class, tên cửa sổ
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
	hwnd = CreateWindow(wc.lpszClassName, TEXT("Pomodoro"), WS_OVERLAPPEDWINDOW | WS_VISIBLE,
		400, 150, 330, 200, NULL, NULL, hInstance, NULL); //Vị trí từ trái, vị trí từ trên, rộng, cao
														  //Hiển thị và update cửa sổ
	ShowWindow(hwnd, nCmdShow);
	UpdateWindow(hwnd);
	HACCEL hAccelTable = LoadAccelerators(hInstance, MAKEINTRESOURCE(IDC_POMODORO));
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
	wcex.hIcon = LoadIcon(hInstance, MAKEINTRESOURCE(IDI_POMODORO));
	wcex.hCursor = LoadCursor(nullptr, IDC_ARROW);
	wcex.hbrBackground = (HBRUSH)(COLOR_WINDOW + 1);
	wcex.lpszMenuName = MAKEINTRESOURCEW(IDC_POMODORO);
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
HWND txtHour;
HWND txtMinute;
HWND txtSeconds;
WCHAR* bufHour = NULL;
WCHAR* bufMinute = NULL;
WCHAR* bufSeconds = NULL;
int size1;
int size2;
int sizeKQ;
int TotalBillCost;
int NumberOfGuests;
int Result;
int h;
int m;
int s;
int Test;
int TimeForRelax; //thời gian nghỉ sau 25p
int CountTimeForRelax; //Đếm số lần nghỉ giữa
int IsBeforeOpen;
int IsBeforeStop;

LRESULT CALLBACK WndProc(HWND hWnd, UINT message, WPARAM wParam, LPARAM lParam)
{
	Result = 0;
	switch (message)
	{
	case WM_CREATE:
	{
		AddMenus(hWnd);

		// Lấy font hệ thống
		LOGFONT lf;
		GetObject(GetStockObject(DEFAULT_GUI_FONT), sizeof(LOGFONT), &lf);
		HFONT hFont = CreateFont(45, lf.lfWidth,
			lf.lfEscapement, lf.lfOrientation, lf.lfWeight,
			lf.lfItalic, lf.lfUnderline, lf.lfStrikeOut, lf.lfCharSet,
			lf.lfOutPrecision, lf.lfClipPrecision, lf.lfQuality,
			lf.lfPitchAndFamily, lf.lfFaceName);

		//Trái sang, trên xuống, rộng, cao
		HWND hwnd;
		//Textbox giờ
		hwnd = txtHour = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER |WS_DISABLED, 20, 30, 50, 50, hWnd, NULL, hInst, NULL);
		SendMessage(hwnd, WM_SETFONT, WPARAM(hFont), TRUE);
		//
		hwnd = CreateWindowEx(0, L"STATIC", L"h", WS_CHILD | WS_VISIBLE | SS_LEFT, 80, 30, 20, 50, hWnd, NULL, hInst, NULL);
		SendMessage(hwnd, WM_SETFONT, WPARAM(hFont), TRUE);
		//Textbox phút
		hwnd = txtMinute = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER | WS_DISABLED, 110, 30, 50, 50, hWnd, NULL, hInst, NULL);
		SendMessage(hwnd, WM_SETFONT, WPARAM(hFont), TRUE);
		//
		hwnd = CreateWindowEx(0, L"STATIC", L"m", WS_CHILD | WS_VISIBLE | SS_LEFT, 170, 30, 50, 50, hWnd, NULL, hInst, NULL);
		SendMessage(hwnd, WM_SETFONT, WPARAM(hFont), TRUE);
		//Textbox giây
		hwnd = txtSeconds = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER | WS_DISABLED, 210, 30, 50, 50, hWnd, NULL, hInst, NULL);
		SendMessage(hwnd, WM_SETFONT, WPARAM(hFont), TRUE);
		hwnd = CreateWindowEx(0, L"STATIC", L"s", WS_CHILD | WS_VISIBLE | SS_LEFT, 270, 30, 50, 50, hWnd, NULL, hInst, NULL);
		SendMessage(hwnd, WM_SETFONT, WPARAM(hFont), TRUE);

		CreateWindow(TEXT("button"), TEXT("Start"), WS_VISIBLE | WS_CHILD, 20, 90, 80, 25, hWnd, (HMENU)DC_BUTTONS, NULL, NULL);
		CreateWindow(TEXT("button"), TEXT("Pause"), WS_VISIBLE | WS_CHILD, 110, 90, 80, 25, hWnd, (HMENU)DC_BUTTONS2, NULL, NULL);
		CreateWindow(TEXT("button"), TEXT("Stop"), WS_VISIBLE | WS_CHILD, 200, 90, 80, 25, hWnd, (HMENU)DC_BUTTONS3, NULL, NULL);
		

		break;
	}
	case WM_TIMER:
		switch (wParam)
		{
		case IDT_TIMER1:
			bufMinute = new WCHAR[255];
			bufHour = new WCHAR[255];
			bufSeconds = new WCHAR[255];
			int z;
			
			
			z = 0;
			s = s + 1;
			if (s > 59)
			{
				s = 0;
				wsprintf(bufSeconds, L"%d", s);
				SetWindowText(txtSeconds, bufSeconds);
				m = m + 1;
				TimeForRelax = TimeForRelax + 1;
				if (m > 59)
				{
					m = 0;
					wsprintf(bufMinute, L"%d", m);
					SetWindowText(txtMinute, bufMinute);
					if (TimeForRelax == 25)
					{
						TimeForRelax = 0;
						CountTimeForRelax = CountTimeForRelax + 1;
						if (CountTimeForRelax != 4)
						{
							z = 1;
							wsprintf(bufMinute, L"%d", m);
							SetWindowText(txtMinute, bufMinute);
							KillTimer(hWnd, IDT_TIMER1);
							if (z == 1)
							{
								SetTimer(hWnd, DC_BUTTONS, 180, (TIMERPROC)NULL); //180000 = 3m
							}
							MessageBox(hWnd, L"You shoule relax for 3 minutes", L"Report", 0);
						}
						else
						{
							CountTimeForRelax = 0;
							z = 1;
							wsprintf(bufMinute, L"%d", m);
							SetWindowText(txtMinute, bufMinute);
							KillTimer(hWnd, IDT_TIMER1);
							if (z == 1)
							{
								SetTimer(hWnd, DC_BUTTONS, 900, (TIMERPROC)NULL); //900000 = 15m
							}
							MessageBox(hWnd, L"You shoule relax for 15 minutes", L"Report", 0);
						}
						
					}
					h = h + 1;
					if (h > 23)
					{
						h = 0;
						wsprintf(bufHour, L"%d", h);
						SetWindowText(txtHour, bufHour);
					}
					wsprintf(bufHour, L"%d", h);
					SetWindowText(txtHour, bufHour);
				}
				else
				{
					wsprintf(bufMinute, L"%d", m);
					SetWindowText(txtMinute, bufMinute);
					if (TimeForRelax == 5)
					{
						TimeForRelax = 0;
						CountTimeForRelax = CountTimeForRelax + 1;
						if (CountTimeForRelax != 4)
						{
							z = 1;
							wsprintf(bufMinute, L"%d", m);
							SetWindowText(txtMinute, bufMinute);
							KillTimer(hWnd, IDT_TIMER1);
							if (z == 1)
							{
								SetTimer(hWnd, DC_BUTTONS, 180, (TIMERPROC)NULL); //180000 = 3m
							}
							MessageBox(hWnd, L"You shoule relax for 3 minutes", L"Report", 0);
						}
						else
						{
							CountTimeForRelax = 0;
							z = 1;
							wsprintf(bufMinute, L"%d", m);
							SetWindowText(txtMinute, bufMinute);
							KillTimer(hWnd, IDT_TIMER1);
							if (z == 1)
							{
								SetTimer(hWnd, DC_BUTTONS, 900, (TIMERPROC)NULL); //900000 = 15m
							}
							MessageBox(hWnd, L"You shoule relax for 15 minutes", L"Report", 0);
						}
					}
				}
			}
			else
			{
				wsprintf(bufSeconds, L"%d", s);
				SetWindowText(txtSeconds, bufSeconds);
			}
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
			case DC_BUTTONS: //Button Start
			{
			
				IsBeforeOpen = 1;
				if (IsBeforeStop)
				{
					s = 0;
					m = 0;
					h = 0;
					IsBeforeStop = 0;
				}
				SetTimer(hWnd, IDT_TIMER1, 1, (TIMERPROC)NULL); //1 = 0.001 s => 1000 = 1s
				break;
			}
			case DC_BUTTONS2: //Button Pause
			{
				if (IsBeforeOpen)
				{
					KillTimer(hWnd, IDT_TIMER1);
					KillTimer(hWnd, DC_BUTTONS);
				}
				else
				{
					MessageBox(hWnd, L"You must press button Start before press button Pause", L"", 0);
				}
				break;
			}
			case DC_BUTTONS3: //Button Stop
			{
				if (IsBeforeOpen)
				{
					IsBeforeStop = 1;
					KillTimer(hWnd, IDT_TIMER1);
					KillTimer(hWnd, DC_BUTTONS);
					s = 0;
					wsprintf(bufSeconds, L"%d", s);
					SetWindowText(txtSeconds, bufSeconds);
					m = 0;
					wsprintf(bufMinute, L"%d", m);
					SetWindowText(txtMinute, bufMinute);
					h = 0;
					wsprintf(bufHour, L"%d", h);
					SetWindowText(txtHour, bufHour);
					TimeForRelax = 0;
					CountTimeForRelax = 0;
				}
				else
				{
					MessageBox(hWnd, L"You must press button Start before press button Stop", L"", 0);
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
