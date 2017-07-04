// TipsCalculatorHelper.cpp : Defines the entry point for the application.
//

#include "stdafx.h"
#include "TipsCalculatorHelper.h"
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

	wc.lpszClassName = TEXT("TipsCalculator"); //Tên class, tên cửa sổ
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
	hwnd = CreateWindow(wc.lpszClassName, TEXT("TipsCalculatorHelper"), WS_OVERLAPPEDWINDOW | WS_VISIBLE,
		400, 150, 630, 200, NULL, NULL, hInstance, NULL); //Vị trí từ trái, vị trí từ trên, rộng, cao
		//Hiển thị và update cửa sổ
	ShowWindow(hwnd, nCmdShow);
	UpdateWindow(hwnd);		
	HACCEL hAccelTable = LoadAccelerators(hInstance, MAKEINTRESOURCE(IDC_TIPSCALCULATORHELPER));
	//******************** Tạo Menu ********************
	/*WNDCLASS wc1 = { 0 };
	wc1.lpszClassName = TEXT("Menu");
	wc1.hInstance = hInstance;
	wc1.hbrBackground = GetSysColorBrush(COLOR_3DFACE);
	wc1.lpfnWndProc = WndProc;
	wc1.hCursor = LoadCursor(0, IDC_ARROW);
	RegisterClass(&wc1);
	CreateWindow(wc1.lpszClassName, TEXT("Menu"), WS_OVERLAPPEDWINDOW | WS_VISIBLE, 100, 100, 200, 150, 0, 0, hInstance, 0);
*/
	//******************** Tạo label ********************
	
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

    return (int) msg.wParam;
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

    wcex.style          = CS_HREDRAW | CS_VREDRAW;
    wcex.lpfnWndProc    = WndProc;
    wcex.cbClsExtra     = 0;
    wcex.cbWndExtra     = 0;
    wcex.hInstance      = hInstance;
    wcex.hIcon          = LoadIcon(hInstance, MAKEINTRESOURCE(IDI_TIPSCALCULATORHELPER));
    wcex.hCursor        = LoadCursor(nullptr, IDC_ARROW);
    wcex.hbrBackground  = (HBRUSH)(COLOR_WINDOW+1);
    wcex.lpszMenuName   = MAKEINTRESOURCEW(IDC_TIPSCALCULATORHELPER);
    wcex.lpszClassName  = szWindowClass;
    wcex.hIconSm        = LoadIcon(wcex.hInstance, MAKEINTRESOURCE(IDI_SMALL));

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
HWND txtTotalBillCost;
HWND txtNumberOfGuests;
HWND txtResult;
WCHAR* buffer1 = NULL;
WCHAR* buffer2 = NULL;
WCHAR* bufferKQ = NULL;
int size1;
int size2;
int sizeKQ;
int TotalBillCost;
int NumberOfGuests;
int Result;

LRESULT CALLBACK WndProc(HWND hWnd, UINT message, WPARAM wParam, LPARAM lParam)
{
	Result = 0;
    switch (message)
    {
	case WM_CREATE:
	{
		AddMenus(hWnd);
		
		
		//Trái sang, trên xuống, rộng, cao
		CreateWindowEx(0, L"STATIC", L"Total Bill Cost:", WS_CHILD | WS_VISIBLE | SS_LEFT, 20, 30, 100, 20, hWnd, NULL, hInst, NULL);
		txtTotalBillCost = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER, 150, 30, 430, 20, hWnd, NULL, hInst, NULL);
		CreateWindowEx(0, L"STATIC", L"Number Of Guests:", WS_CHILD | WS_VISIBLE | SS_LEFT, 20, 60, 125, 20, hWnd, NULL, hInst, NULL);
		txtNumberOfGuests = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER , 150, 60, 430, 20, hWnd, NULL, hInst, NULL);
		CreateWindow(TEXT("button"), TEXT("Result"), WS_VISIBLE | WS_CHILD, 20, 90, 80, 25, hWnd, (HMENU)DC_BUTTONS, NULL, NULL);
		txtResult = CreateWindowEx(0, L"EDIT", L"", WS_CHILD | WS_VISIBLE | WS_BORDER | WS_DISABLED, 150, 90, 430, 20, hWnd, NULL, hInst, NULL);
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
				case DC_BUTTONS:
				{
					//int TotalBillCost;
					//int NumberOfGuests;
					int Test1, Test2;
					Test1 = 1;
					Test2 = 1;
					size1 = GetWindowTextLength(txtTotalBillCost);
					size2 = GetWindowTextLength(txtNumberOfGuests);
					Result = 0;
					buffer1 = new WCHAR[size1 + 1];
					buffer2 = new WCHAR[size2 + 1];
					bufferKQ = new WCHAR[255];
					GetWindowText(txtTotalBillCost, buffer1, size1 + 1);
					GetWindowText(txtNumberOfGuests, buffer2, size2 + 1);
					if (size1 == 0 && size2 == 0)
					{
						wsprintf(bufferKQ, L"Please fill integer number for 2 textbox");
					}
					else if(size2 == 0)
					{
						wsprintf(bufferKQ, L"Please fill integer number for Number Of Guests");
					}
					else if (size1 == 0)
					{
						wsprintf(bufferKQ, L"Please fill integer number for Total Bill Cost ");
					}
					else
					{
						for (int i = 0; i < size1; i++)
						{
							if ((buffer1[i] < 48 || buffer1[i] > 57))
							{
								Test1 = 0;
							}
						}
						for (int i = 0; i < size2; i++)
						{
							if (buffer2[i] < 48 || buffer2[i] > 57)
							{
								Test2 = 0;
							}
						}
						if (Test1 == 0 && Test2 == 0)
						{
							wsprintf(bufferKQ, L"Only fill integer number in 2 textbox");
						}
						else if (Test2 == 0)
						{
							wsprintf(bufferKQ, L"Only fill integer number in textbox Number Of Guests");
						}
						else if (Test1 == 0 )
						{
							wsprintf(bufferKQ, L"Only fill number in textbox Total Bill Cost ");
						}
						
						else
						{
							TotalBillCost = _wtoi(buffer1);
							NumberOfGuests = _wtoi(buffer2);
							if (NumberOfGuests == 0)
							{
								wsprintf(bufferKQ, L"Number Of Guests must != 0");
							}
							else
							{
								Result = (TotalBillCost + TotalBillCost * 0.1) / NumberOfGuests;
								wsprintf(bufferKQ, L"%d $/person", Result);
							}
						}
					}
					SetWindowText(txtResult, bufferKQ);
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
