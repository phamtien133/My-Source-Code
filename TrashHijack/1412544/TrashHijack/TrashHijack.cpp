// TrashHijack.cpp : Defines the entry point for the application.
//

#include "stdafx.h"
#include "TrashHijack.h"
//Thao tác với file
#include <fstream>
#include <iostream>  
#include <string>
#include <vector>
using namespace std;
//Unicode
#include <locale>
#include <codecvt>
#include <cstdlib>
//Hàm ghi file.txt
void WriteFile(LPCWSTR buffer);
void WINAPI SetAutoView(HWND hwnd);
//Lấy file ảnh
void GetPicture(HWND hWnd);
//Hàm lấy txt từ clipboard
void GetTxt(HWND hWnd);
//
#define MAX_SIZE 10240
HINSTANCE hinst;
UINT uFormat = (UINT)(-1);
BOOL fAuto = TRUE;
HWND title;
PBITMAPINFO CreateBitmapInfoStruct(HWND hwnd, HBITMAP hBmp);
void CreateBMPFile(HWND hwnd, LPTSTR pszFile, PBITMAPINFO pbi,	HBITMAP hBMP, HDC hDC);

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
    LoadStringW(hInstance, IDC_TRASHHIJACK, szWindowClass, MAX_LOADSTRING);
    MyRegisterClass(hInstance);

    // Perform application initialization:
    if (!InitInstance (hInstance, nCmdShow))
    {
        return FALSE;
    }

    HACCEL hAccelTable = LoadAccelerators(hInstance, MAKEINTRESOURCE(IDC_TRASHHIJACK));

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
    wcex.hIcon          = LoadIcon(hInstance, MAKEINTRESOURCE(IDI_TRASHHIJACK));
    wcex.hCursor        = LoadCursor(nullptr, IDC_ARROW);
    wcex.hbrBackground  = (HBRUSH)(COLOR_WINDOW+1);
    wcex.lpszMenuName   = MAKEINTRESOURCEW(IDC_TRASHHIJACK);
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
bool isDoing = FALSE;
LRESULT CALLBACK WndProc(HWND hWnd, UINT message, WPARAM wParam, LPARAM lParam)
{
	static HWND hwndNextViewer;
    switch (message)
    {
	case WM_CREATE:
		hwndNextViewer = SetClipboardViewer(hWnd);
		break;
	/*case WM_TIMER:
	{
		switch (wParam)
		{
		case IDT_GetTxt:
			GetTxt(hWnd);
			break;
		case IDT_GetPicture:
			GetPicture(hWnd);
			break;
		}
	}*/
	break;
	case WM_KEYDOWN:
	{
		if (GetAsyncKeyState(VK_CONTROL) != 0 && GetAsyncKeyState(VK_MENU) != 0 && GetAsyncKeyState(VK_SHIFT) != 0 && GetAsyncKeyState(0x43) != 0) //0x43 = c key - virtual-key code
		{
			isDoing = TRUE;
			MessageBox(0, L"Start is success...! Ctrl + Alt + Shilft + V to Stop", L"Report", 0);
			/*MessageBox(0, L"OK1", 0, 0);
			SetTimer(hWnd, IDT_GetTxt, 1, (TIMERPROC)NULL);
			SetTimer(hWnd, IDT_GetPicture, 1, (TIMERPROC)NULL);*/
		}
		if (GetAsyncKeyState(VK_CONTROL) != 0 && GetAsyncKeyState(VK_MENU) != 0 && GetAsyncKeyState(VK_SHIFT) != 0 && GetAsyncKeyState(0x56) != 0) //0x43 = v key - virtual-key code
		{
			MessageBox(0, L"Stop is success...! Ctrl + Alt + Shilft + C to Start", L"Report", 0);
			isDoing = FALSE;
			/*MessageBox(0, L"OK1", 0, 0);
			SetTimer(hWnd, IDT_GetTxt, 1, (TIMERPROC)NULL);
			SetTimer(hWnd, IDT_GetPicture, 1, (TIMERPROC)NULL);*/
		}
	}
	break;
	case WM_CHANGECBCHAIN:
	{
		if ((HWND)wParam == hwndNextViewer)
			hwndNextViewer = (HWND)lParam;

		else if (hwndNextViewer != NULL)
			SendMessage(hwndNextViewer, message, wParam, lParam);
	}
	break;
	case WM_DRAWCLIPBOARD:
	{

		SetAutoView(hWnd);

		SendMessage(hwndNextViewer, message, wParam, lParam);
	}
	break;
    case WM_COMMAND:
        {
            int wmId = LOWORD(wParam);
            // Parse the menu selections:
            switch (wmId)
            {
            case IDM_ABOUT:
                DialogBox(hInst, MAKEINTRESOURCE(IDD_ABOUTBOX), hWnd, About);
                break;
            case IDM_EXIT:
                DestroyWindow(hWnd);
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
		if (isDoing == TRUE)
		{
			switch (uFormat)
			{
			case CF_BITMAP:
			{
				GetPicture(hWnd);
			}
			break;
			case CF_TEXT:
			{
				GetTxt(hWnd);
			}
			break;
			default:
				break;
			}
		}
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


//Hàm ghi file.txt
void WriteFile(LPCWSTR buffer)
{
	const std::locale empty_locale = std::locale::empty();
	typedef std::codecvt_utf8<wchar_t> converter_type;
	const converter_type* converter = new converter_type;
	const std::locale utf8_locale = std::locale(empty_locale, converter);
	SYSTEMTIME time;
	WCHAR* path = new WCHAR[50];
	GetLocalTime(&time);
	swprintf(path, 50, L"Data\\Text\\Text_%d-%d-%d_%dh%dm%ds.txt", time.wYear, time.wMonth, time.wDay, time.wHour, time.wMinute, time.wSecond);

	wofstream myFile(path);
	myFile.imbue(utf8_locale);

	myFile << buffer << endl;
}
//Hàm lấy txt từ clipboard
void GetTxt(HWND hWnd)
{
	if (OpenClipboard(hWnd))
	{
		if (IsClipboardFormatAvailable(CF_UNICODETEXT))
		{

			HANDLE hClipboardData = GetClipboardData(CF_UNICODETEXT);
			LPCWSTR buffer = (LPCWSTR)GlobalLock(hClipboardData);
			WriteFile(buffer);
			GlobalUnlock(hClipboardData);
		}
		CloseClipboard();
	}
}
//Lưu file ảnh
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
//Hàm lấy hình ảnh từ clipboard
void GetPicture(HWND hWnd)
{
	if (OpenClipboard(hWnd))
	{
		HBITMAP hBitmap = (HBITMAP)GetClipboardData(CF_BITMAP);
		BITMAP bitmap;
		GetObject(hBitmap, sizeof(BITMAP), &bitmap);

		HDC hdc = GetDC(hWnd); // Tạo DC ảo chứa bitmap
		HDC memDC = CreateCompatibleDC(hdc);
		SelectObject(memDC, hBitmap);

		BitBlt(hdc, 0, 0, bitmap.bmWidth, bitmap.bmHeight, memDC, 0, 0, SRCCOPY);


		SYSTEMTIME time;

		WCHAR* path = new WCHAR[50];

		GetLocalTime(&time);

		swprintf(path, 50, L"Data\\Images\\Screen_%d-%d-%d_%dh%dm%ds.bmp", time.wYear, time.wMonth, time.wDay, time.wHour, time.wMinute, time.wSecond);

		CreateBMPFile(hWnd, path, CreateBitmapInfoStruct(hWnd, hBitmap), hBitmap, memDC);
		// Sử dụng đoạn code comment bên dưới để zoom ảnh
		RECT rect;
		GetClientRect(hWnd, &rect);
		int height = rect.bottom - rect.top;
		int width = rect.right - rect.left; // Duy trì tỉ lệ 16:9
		StretchBlt(hdc, 0, 0, width, height,
			memDC, 0, 0, bitmap.bmWidth, bitmap.bmHeight, SRCCOPY);

		DeleteDC(memDC);
		ReleaseDC(hWnd, hdc);
		CloseClipboard();
	}
}



void WINAPI SetAutoView(HWND hwnd)
{
	static UINT auPriorityList[] = {
		CF_OWNERDISPLAY,
		CF_TEXT,
		CF_ENHMETAFILE,
		CF_BITMAP
	};

	uFormat = GetPriorityClipboardFormat(auPriorityList, 4);
	fAuto = TRUE;

	InvalidateRect(hwnd, NULL, TRUE);
	UpdateWindow(hwnd);
}