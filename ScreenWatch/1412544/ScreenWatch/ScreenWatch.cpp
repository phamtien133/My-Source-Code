// ScreenWatch.cpp : Defines the entry point for the application.
//

#include "stdafx.h"
#include "ScreenWatch.h"
HINSTANCE hDLL;
PBITMAPINFO CreateBitmapInfoStruct(HWND hwnd, HBITMAP hBmp);
void CreateBMPFile(HWND hwnd, LPTSTR pszFile, PBITMAPINFO pbi, HBITMAP hBMP, HDC hDC);
void GetScreenShot(HWND hWnd);
#define MAX_LOADSTRING 100

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

    // Initialize global strings
    LoadStringW(hInstance, IDS_APP_TITLE, szTitle, MAX_LOADSTRING);
    LoadStringW(hInstance, IDC_SCREENWATCH, szWindowClass, MAX_LOADSTRING);
    MyRegisterClass(hInstance);

    // Perform application initialization:
    if (!InitInstance (hInstance, nCmdShow))
    {
        return FALSE;
    }

    HACCEL hAccelTable = LoadAccelerators(hInstance, MAKEINTRESOURCE(IDC_SCREENWATCH));

    MSG msg;

    // Main message loop:
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
    wcex.hIcon          = LoadIcon(hInstance, MAKEINTRESOURCE(IDI_SCREENWATCH));
    wcex.hCursor        = LoadCursor(nullptr, IDC_ARROW);
    wcex.hbrBackground  = (HBRUSH)(COLOR_WINDOW+1);
    wcex.lpszMenuName   = MAKEINTRESOURCEW(IDC_SCREENWATCH);
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

//Thêm menu bar
void AddMenus(HWND hwnd)
{
	HMENU hMenubarAboutMe;
	HMENU hMenubarSize;
	HMENU hMenuAboutMe;
	hMenubarAboutMe = CreateMenu();
	hMenuAboutMe = CreateMenu();
	hMenubarSize = CreateMenu();
	//Tạo ra từng menu About me
	AppendMenu(hMenuAboutMe, MF_STRING, IDM_ID, TEXT("1412544"));
	AppendMenu(hMenuAboutMe, MF_STRING, IDM_NAME, TEXT("Phạm Đức Tiên"));
	AppendMenu(hMenuAboutMe, MF_SEPARATOR, 0, NULL); //Đường kẻ giữa
	AppendMenu(hMenuAboutMe, MF_STRING, IDM_FILE_QUIT, TEXT("Thanks for using my program... (Exit)"));
	AppendMenu(hMenubarAboutMe, MF_POPUP, (UINT_PTR)hMenuAboutMe, TEXT("About me"));
	//Tạo ra từng menu Size
	AppendMenu(hMenubarSize, MF_STRING, ID_HOOK_INSTALLHOOK, TEXT("Install Hook"));
	AppendMenu(hMenubarSize, MF_STRING, ID_HOOK_UNINSTALLHOOK, TEXT("Uninstall Hook"));
	AppendMenu(hMenubarAboutMe, MF_POPUP, (UINT_PTR)hMenubarSize, TEXT("HOOK"));
	//SetMenu(hwnd, hMenubar2); //Gắn menu item lên menubar
	SetMenu(hwnd, hMenubarAboutMe); //Gắn menu item lên menubar
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
LRESULT CALLBACK WndProc(HWND hWnd, UINT message, WPARAM wParam, LPARAM lParam)
{
    switch (message)
    {
	case WM_CREATE:
	{
		AddMenus(hWnd);
	}
	case WM_TIMER:
	{
		switch (wParam)
		{
		case IDT_GetScreenShot:
			GetScreenShot(hWnd);
			break;\
		}
	}
	break;
	case WM_KEYDOWN:
	{
		hDLL = LoadLibrary(_T("DLL_ScreenWatch.dll"));
		typedef  int(*proc1) (HWND);
		if (GetAsyncKeyState(VK_CONTROL) != 0 && GetAsyncKeyState(VK_MENU) != 0 && GetAsyncKeyState(VK_SHIFT) != 0 && GetAsyncKeyState(0x53) != 0) //0x53 = s key - virtual-key code
		{
			proc1 getActive = (proc1)GetProcAddress(hDLL, "GetActive");
			bool isActive = getActive(hWnd);
			if (isActive == TRUE)
			{
				SetTimer(hWnd, IDT_GetScreenShot, 2000, (TIMERPROC)NULL); //1 = 0.001 s => 1000 = 1s
			}
		}
	}
    case WM_COMMAND:
        {
            int wmId = LOWORD(wParam);
            // Parse the menu selections:
            switch (wmId)
            {
			
			break;
            case IDM_FILE_QUIT:
				MessageBox(0, L"Thanks for using my program...!", L"Report", 0);
                DestroyWindow(hWnd);
                break;
			case ID_HOOK_INSTALLHOOK:
			{
				hDLL = LoadLibrary(_T("DLL_ScreenWatch.dll"));
				typedef  int(*proc1) (HWND);
				if (hDLL != NULL)
				{
					hDLL = LoadLibrary(_T("DLL_ScreenWatch.dll"));
					typedef  int(*proc1) (HWND);
					proc1 installHookproc = (proc1)GetProcAddress(hDLL, "InitKeyboardHook");
					if (installHookproc != NULL)
					{
						int rs = installHookproc(hWnd);
						if (rs == 1)
						{
							MessageBox(hWnd, _T("Install success... Press Ctrl + Alt + Shilft + S to Start"), _T("Info"), MB_OK);
						}
						else
						{
							MessageBox(hWnd, _T("Install hook failed"), _T("Error"), MB_OK);
						}
					}
					else
					{
						MessageBox(hWnd, _T("Get proc failed"), _T("eError"), MB_OK);
					}
				}
				else
				{
					MessageBox(hWnd, _T("Load DLL failed"), _T("Error"), MB_OK);
				}
			}
			break;
			case ID_HOOK_UNINSTALLHOOK:
			{
				if (hDLL != NULL)
				{
					KillTimer(hWnd, IDT_GetScreenShot);
					typedef void(*proc)();
					proc uninstallHook = (proc)GetProcAddress(hDLL, "UninstallKeyboardHook");
					if (uninstallHook != NULL)
					{
						uninstallHook();
					}
					FreeLibrary(hDLL);
					MessageBox(hWnd, _T("Stop success...!"), _T("Info"), MB_OK);
				}
			}
			break;
            default:
                return DefWindowProc(hWnd, message, wParam, lParam);
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


PBITMAPINFO CreateBitmapInfoStruct(HWND hwnd, HBITMAP hBmp)
{
	BITMAP bmp;
	PBITMAPINFO pbmi;
	WORD    cClrBits;

	// Retrieve the bitmap color format, width, and height.  
	if (!GetObject(hBmp, sizeof(BITMAP), (LPSTR)&bmp))
	{
	}


	// Convert the color format to a count of bits.  
	cClrBits = (WORD)(bmp.bmPlanes * bmp.bmBitsPixel);
	if (cClrBits == 1)
		cClrBits = 1;
	else if (cClrBits <= 4)
		cClrBits = 4;
	else if (cClrBits <= 8)
		cClrBits = 8;
	else if (cClrBits <= 16)
		cClrBits = 16;
	else if (cClrBits <= 24)
		cClrBits = 24;
	else cClrBits = 32;

	// Allocate memory for the BITMAPINFO structure. (This structure  
	// contains a BITMAPINFOHEADER structure and an array of RGBQUAD  
	// data structures.)  

	if (cClrBits < 24)
		pbmi = (PBITMAPINFO)LocalAlloc(LPTR,
			sizeof(BITMAPINFOHEADER) +
			sizeof(RGBQUAD) * (1 << cClrBits));

	// There is no RGBQUAD array for these formats: 24-bit-per-pixel or 32-bit-per-pixel 

	else
		pbmi = (PBITMAPINFO)LocalAlloc(LPTR,
			sizeof(BITMAPINFOHEADER));

	// Initialize the fields in the BITMAPINFO structure.  

	pbmi->bmiHeader.biSize = sizeof(BITMAPINFOHEADER);
	pbmi->bmiHeader.biWidth = bmp.bmWidth;
	pbmi->bmiHeader.biHeight = bmp.bmHeight;
	pbmi->bmiHeader.biPlanes = bmp.bmPlanes;
	pbmi->bmiHeader.biBitCount = bmp.bmBitsPixel;
	if (cClrBits < 24)
		pbmi->bmiHeader.biClrUsed = (1 << cClrBits);

	// If the bitmap is not compressed, set the BI_RGB flag.  
	pbmi->bmiHeader.biCompression = BI_RGB;

	// Compute the number of bytes in the array of color  
	// indices and store the result in biSizeImage.  
	// The width must be DWORD aligned unless the bitmap is RLE 
	// compressed. 
	pbmi->bmiHeader.biSizeImage = ((pbmi->bmiHeader.biWidth * cClrBits + 31) & ~31) / 8
		* pbmi->bmiHeader.biHeight;
	// Set biClrImportant to 0, indicating that all of the  
	// device colors are important.  
	pbmi->bmiHeader.biClrImportant = 0;
	return pbmi;
}


void CreateBMPFile(HWND hwnd, LPTSTR pszFile, PBITMAPINFO pbi,
	HBITMAP hBMP, HDC hDC)
{
	HANDLE hf;                 // file handle  
	BITMAPFILEHEADER hdr;       // bitmap file-header  
	PBITMAPINFOHEADER pbih;     // bitmap info-header  
	LPBYTE lpBits;              // memory pointer  
	DWORD dwTotal;              // total count of bytes  
	DWORD cb;                   // incremental count of bytes  
	BYTE *hp;                   // byte pointer  
	DWORD dwTmp;

	pbih = (PBITMAPINFOHEADER)pbi;
	lpBits = (LPBYTE)GlobalAlloc(GMEM_FIXED, pbih->biSizeImage);

	if (!lpBits)
	{
	}

	// Retrieve the color table (RGBQUAD array) and the bits  
	// (array of palette indices) from the DIB.  
	if (!GetDIBits(hDC, hBMP, 0, (WORD)pbih->biHeight, lpBits, pbi,
		DIB_RGB_COLORS))
	{

	}

	// Create the .BMP file.  
	hf = CreateFile(pszFile,
		GENERIC_READ | GENERIC_WRITE,
		(DWORD)0,
		NULL,
		CREATE_ALWAYS,
		FILE_ATTRIBUTE_NORMAL,
		(HANDLE)NULL);
	if (hf == INVALID_HANDLE_VALUE)
	{
	}
	hdr.bfType = 0x4d42;        // 0x42 = "B" 0x4d = "M"  
								// Compute the size of the entire file.  
	hdr.bfSize = (DWORD)(sizeof(BITMAPFILEHEADER) +
		pbih->biSize + pbih->biClrUsed
		* sizeof(RGBQUAD) + pbih->biSizeImage);
	hdr.bfReserved1 = 0;
	hdr.bfReserved2 = 0;

	// Compute the offset to the array of color indices.  
	hdr.bfOffBits = (DWORD) sizeof(BITMAPFILEHEADER) +
		pbih->biSize + pbih->biClrUsed
		* sizeof(RGBQUAD);

	// Copy the BITMAPFILEHEADER into the .BMP file.  
	if (!WriteFile(hf, (LPVOID)&hdr, sizeof(BITMAPFILEHEADER),
		(LPDWORD)&dwTmp, NULL))
	{

	}

	// Copy the BITMAPINFOHEADER and RGBQUAD array into the file.  
	if (!WriteFile(hf, (LPVOID)pbih, sizeof(BITMAPINFOHEADER)
		+ pbih->biClrUsed * sizeof(RGBQUAD),
		(LPDWORD)&dwTmp, (NULL)))
	{
	}

	// Copy the array of color indices into the .BMP file.  
	dwTotal = cb = pbih->biSizeImage;
	hp = lpBits;
	if (!WriteFile(hf, (LPSTR)hp, (int)cb, (LPDWORD)&dwTmp, NULL))
	{
	}

	// Close the .BMP file.  
	if (!CloseHandle(hf))
	{
	}

	// Free memory.  
	GlobalFree((HGLOBAL)lpBits);
}

void GetScreenShot(HWND hWnd)

{

	int x1, y1, x2, y2, w, h;



	// get screen dimensions

	x1 = GetSystemMetrics(SM_XVIRTUALSCREEN);

	y1 = GetSystemMetrics(SM_YVIRTUALSCREEN);

	x2 = GetSystemMetrics(SM_CXVIRTUALSCREEN);

	y2 = GetSystemMetrics(SM_CYVIRTUALSCREEN);

	w = x2 - x1;

	h = y2 - y1;



	// copy screen to bitmap

	HDC     hScreen = GetDC(NULL);

	HDC     hDC = CreateCompatibleDC(hScreen);

	HBITMAP hBitmap = CreateCompatibleBitmap(hScreen, w, h);

	HGDIOBJ old_obj = SelectObject(hDC, hBitmap);

	BOOL    bRet = BitBlt(hDC, 0, 0, w, h, hScreen, x1, y1, SRCCOPY);



	SYSTEMTIME time;

	WCHAR* path = new WCHAR[50];

	GetLocalTime(&time);

	swprintf(path, 50, L"Images\\Screen_%d-%d-%d_%dh%dm%ds.bmp", time.wYear, time.wMonth, time.wDay, time.wHour, time.wMinute, time.wSecond);

	CreateBMPFile(hWnd, path, CreateBitmapInfoStruct(hWnd, hBitmap), hBitmap, hDC);



	//in ảnh ra màn hình

	BITMAP bitmap;

	GetObject(hBitmap, sizeof(BITMAP), &bitmap);

	RECT rect;

	GetClientRect(hWnd, &rect);

	int height = rect.bottom - rect.top;

	int width = height * 16 / 9; // Duy trì tỉ lệ 16:9

	HDC hdc = GetDC(hWnd);

	StretchBlt(hdc, 0, 0, width, height,

		hDC, 0, 0, bitmap.bmWidth, bitmap.bmHeight, SRCCOPY);



	// save bitmap to clipboard

	OpenClipboard(NULL);

	EmptyClipboard();

	SetClipboardData(CF_BITMAP, hBitmap);

	CloseClipboard();



	// clean up

	SelectObject(hDC, old_obj);

	DeleteDC(hDC);

	ReleaseDC(NULL, hScreen);

	DeleteObject(hBitmap);

	return;

}


