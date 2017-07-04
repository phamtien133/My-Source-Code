// PaintingApp.cpp : Defines the entry point for the application.
//

#include "stdafx.h"
#include "PaintingApp.h"
#include "Shape.h"
#include "Line.h"
#include "Rectangle.h"
#include "Circle.h"
#include <iostream>
#include <vector>
#include <windowsx.h>
#include "MathLibraryAndClient.h"
//typedef void(__cdecl *MYPROC)(HDC, int, int, int ,int);
using namespace std;
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
    LoadStringW(hInstance, IDC_PAINTINGAPP, szWindowClass, MAX_LOADSTRING);
    MyRegisterClass(hInstance);

    // Perform application initialization:
    if (!InitInstance (hInstance, nCmdShow))
    {
        return FALSE;
    }

    HACCEL hAccelTable = LoadAccelerators(hInstance, MAKEINTRESOURCE(IDC_PAINTINGAPP));

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
    wcex.hIcon          = LoadIcon(hInstance, MAKEINTRESOURCE(IDI_PAINTINGAPP));
    wcex.hCursor        = LoadCursor(nullptr, IDC_ARROW);
    wcex.hbrBackground  = (HBRUSH)(COLOR_WINDOW+1);
    wcex.lpszMenuName   = MAKEINTRESOURCEW(IDC_PAINTINGAPP);
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
//Menubar
void AddMenus(HWND hwnd)
{
	HMENU hMenubarAboutMe;
	HMENU hMenubarSize;
	HMENU hMenuAboutMe;
	HMENU hMenuDraw;
	HMENU hMenuDllDraw;

	hMenubarAboutMe = CreateMenu();
	hMenuAboutMe = CreateMenu();
	hMenubarSize = CreateMenu();
	hMenuDraw = CreateMenu();
	hMenuDllDraw = CreateMenu();

	//Tạo ra từng menu About me
	AppendMenu(hMenuAboutMe, MF_STRING, IDM_MSSV, TEXT("1412544"));
	AppendMenu(hMenuAboutMe, MF_STRING, IDM_NAME, TEXT("Phạm Đức Tiên"));
	AppendMenu(hMenuAboutMe, MF_SEPARATOR, 0, NULL); //Đường kẻ giữa
	AppendMenu(hMenuAboutMe, MF_STRING, IDM_FILE_QUIT, TEXT("Thanks for using my program... (Exit)"));
	AppendMenu(hMenubarAboutMe, MF_POPUP, (UINT_PTR)hMenuAboutMe, TEXT("About me"));
	//Tạo ra từng menu Size
	AppendMenu(hMenubarSize, MF_STRING, SIZE_850x250, TEXT("850x250"));
	AppendMenu(hMenubarSize, MF_STRING, SIZE_950x350, TEXT("950x350"));
	AppendMenu(hMenubarSize, MF_STRING, SIZE_1050x450, TEXT("1050x450"));
	AppendMenu(hMenubarAboutMe, MF_POPUP, (UINT_PTR)hMenubarSize, TEXT("Size For Windows"));
	//Tạo ra từng menu Draw
	AppendMenu(hMenuDraw, MF_STRING, ID_DRAW_LINE, TEXT("DRAW LINE"));
	AppendMenu(hMenuDraw, MF_STRING, ID_DRAW_RECTANGLE, TEXT("DRAW RECTANGLE"));
	AppendMenu(hMenuDraw, MF_STRING, ID_DRAW_CIRCLE, TEXT("DRAW CIRCLE"));
	AppendMenu(hMenuDraw, MF_SEPARATOR, 0, NULL); //Đường kẻ giữa
	AppendMenu(hMenuDraw, MF_STRING, ID_DESTROY_DRAW, TEXT("CANCEL DRAW"));
	AppendMenu(hMenubarAboutMe, MF_POPUP, (UINT_PTR)hMenuDraw, TEXT("DRAW..."));
	//Menu Dll Draw
	AppendMenu(hMenuDllDraw, MF_STRING, ID_DLL_DRAW_LINE, TEXT("DLL DRAW LINE"));
	AppendMenu(hMenuDllDraw, MF_STRING, ID_DLL_DRAW_RECTANGLE, TEXT("DLL DRAW RECTANGLE"));
	AppendMenu(hMenuDllDraw, MF_STRING, ID_DLL_DRAW_CIRCLE, TEXT("DLL DRAW CIRCLE"));
	AppendMenu(hMenuDllDraw, MF_SEPARATOR, 0, NULL); //Đường kẻ giữa
	AppendMenu(hMenuDllDraw, MF_STRING, ID_DESTROY_DRAW, TEXT("CANCEL DLL DRAW"));
	AppendMenu(hMenubarAboutMe, MF_POPUP, (UINT_PTR)hMenuDllDraw, TEXT("DLL DRAW..."));

	//SetMenu(hwnd, hMenubar2); //Gắn menu item lên menubar
	SetMenu(hwnd, hMenubarAboutMe); //Gắn menu item lên menubar
}



vector<int> vx1;
vector<int> vy1;
vector<int> vx2;
vector<int> vy2;
vector<int> types;







bool isDrawing = FALSE;
bool isLine = FALSE;
bool isRectangle = FALSE;
bool isCircle = FALSE;
//bool isClear = FALSE; //idea

bool isWriteLine = FALSE;
bool isWriteRectangle = FALSE;
bool isWriteCircle = FALSE;
bool isDllLine = FALSE;
bool isDllRectangle = FALSE;
bool isDllCircle = FALSE;

int currentX;
int currentY;
int lastX;
int lastY;
CLine LineShape;
CRectangle RectangleShape;
CCircle CircleShape;
LRESULT CALLBACK WndProc(HWND hWnd, UINT message, WPARAM wParam, LPARAM lParam)
{
	int x;
	int y;
	
	switch (message)
	{
	case WM_CREATE:
		
		AddMenus(hWnd);
		
		LineShape.ReadFile("1.bin");
		RectangleShape.ReadFile("2.bin");
		CircleShape.ReadFile("3.bin");
		break;
	case WM_RBUTTONUP:
	{
		HMENU hMenu;
		POINT point; //Sử dụng cho popup menu
		point.x = LOWORD(lParam);
		point.y = HIWORD(lParam);
		hMenu = CreatePopupMenu();
		ClientToScreen(hWnd, &point);

		
		
		AppendMenu(hMenu, MF_STRING, ID_DRAW_LINE, TEXT("DRAW LINE"));
		AppendMenu(hMenu, MF_STRING, ID_DRAW_RECTANGLE, TEXT("DRAW RECTANGLE"));
		AppendMenu(hMenu, MF_STRING, ID_DRAW_CIRCLE, TEXT("DRAW CIRCLE"));
		AppendMenu(hMenu, MF_SEPARATOR, 0, NULL); //Đường kẻ giữa
		//Menu Dll Draw
		AppendMenu(hMenu, MF_STRING, ID_DLL_DRAW_LINE, TEXT("DLL DRAW LINE"));
		AppendMenu(hMenu, MF_STRING, ID_DLL_DRAW_RECTANGLE, TEXT("DLL DRAW RECTANGLE"));
		AppendMenu(hMenu, MF_STRING, ID_DLL_DRAW_CIRCLE, TEXT("DLL DRAW CIRCLE"));
		AppendMenu(hMenu, MF_SEPARATOR, 0, NULL); //Đường kẻ giữa
		AppendMenu(hMenu, MF_STRING, ID_DESTROY_DRAW, TEXT("CANCEL ALL DRAW"));
		AppendMenu(hMenu, MF_STRING, IDM_FILE_QUIT, TEXT("&Exit Program"));

		TrackPopupMenu(hMenu, TPM_RIGHTBUTTON, point.x, point.y, 0, hWnd, NULL);
		DestroyMenu(hMenu);
		break;
	}
	case WM_LBUTTONDOWN:
		x = GET_X_LPARAM(lParam);
		y = GET_Y_LPARAM(lParam);
		if (isLine || isRectangle || isCircle || isDllLine || isDllRectangle || isDllCircle)
		{
			if (!isDrawing) {
				isDrawing = TRUE;
				currentX = x;
				currentY = y;
			}
		}
		break;

	case WM_MOUSEMOVE:
	{
		x = GET_X_LPARAM(lParam);
		y = GET_Y_LPARAM(lParam);

		if (isLine || isRectangle || isCircle || isDllLine || isDllRectangle || isDllCircle)
		{
			if (isDrawing) {
				lastX = x;
				lastY = y;
				WCHAR buffer[200];
				wsprintf(buffer, L"%d %d", x, y);
				SetWindowText(hWnd, buffer);
			}
			InvalidateRect(hWnd, NULL, TRUE);
		}
		
	} break;

	case WM_LBUTTONUP: {
		x = GET_X_LPARAM(lParam);
		y = GET_Y_LPARAM(lParam);

		if (isLine || isDllLine)
		{
			isWriteLine = TRUE;
			CLine line;
			line.SetData(currentX, currentY, x, y);
			LineShape.Push_Shape(line);
			
			isDrawing = FALSE;

			InvalidateRect(hWnd, NULL, TRUE);
		}
		if (isRectangle || isDllRectangle)
		{
			isWriteRectangle = TRUE;
			CRectangle rectangle;
			rectangle.SetData(currentX, currentY, x, y);
			RectangleShape.Push_Shape(rectangle);
			
			isDrawing = FALSE;

			InvalidateRect(hWnd, NULL, TRUE);
		}
		if (isCircle || isDllCircle)
		{
			isWriteCircle = TRUE;
			CCircle circle;
			circle.SetData(currentX, currentY, x, y);
			CircleShape.Push_Shape(circle);
			
			isDrawing = FALSE;

			InvalidateRect(hWnd, NULL, TRUE);
		}
		/*if (isClear)
		{
			CRectangle* clear = new CRectangle;
			clear->SetData(currentX, currentY, x, y);
			shapes.push_back(clear);

			isDrawing = FALSE;

			InvalidateRect(hWnd, NULL, TRUE);
		}*/
		

	}break;
	case WM_COMMAND:
	{
		int wmId = LOWORD(wParam);
		HDC hdc;

		CShape* shape;

		CLine* line;
		CRectangle* rect;
		CRectangle* cclear;
		// Parse the menu selections:
		switch (wmId)
		{
		case ID_DRAW_LINE:
			isLine = TRUE;
			isRectangle = FALSE;
			isCircle = FALSE;
			isDllRectangle = FALSE;
			isDllCircle = FALSE;
			isDllLine = FALSE;
			break;
		case ID_DRAW_RECTANGLE:
			isLine = FALSE;
			isRectangle = TRUE;
			isCircle = FALSE;
			isDllRectangle = FALSE;
			isDllCircle = FALSE;
			isDllLine = FALSE;
			break;
		case ID_DRAW_CIRCLE:
			isLine = FALSE;
			isRectangle = FALSE;
			isCircle = TRUE;
			isDllRectangle = FALSE;
			isDllCircle = FALSE;
			isDllLine = FALSE;
			break;
		case ID_DLL_DRAW_LINE:
			isLine = FALSE;
			isRectangle = FALSE;
			isCircle = FALSE;
			isDllRectangle = FALSE;
			isDllCircle = FALSE;
			isDllLine = TRUE;
			break;
		case ID_DLL_DRAW_RECTANGLE:
			isLine = FALSE;
			isRectangle = FALSE;
			isCircle = FALSE;
			isDllRectangle = TRUE;
			isDllCircle = FALSE;
			isDllLine = FALSE;
			break;
		case ID_DLL_DRAW_CIRCLE:
			isLine = FALSE;
			isRectangle = FALSE;
			isCircle = FALSE;
			isDllRectangle = FALSE;
			isDllCircle = TRUE;
			isDllLine = FALSE;
			break;
		/*case ID_CLEAR: //Ý tưởng xóa các hình trên window nhưng chưa kịp xử lý cho việc đọc ghi file...
			isLine = FALSE;
			isRectangle = FALSE;
			isCircle = FALSE;
			isClear = TRUE;
			break;*/
		case ID_DESTROY_DRAW:
			isLine = FALSE;
			isRectangle = FALSE;
			isCircle = FALSE;
			isDllRectangle = FALSE;
			isDllCircle = FALSE;
			isDllLine = FALSE;
			break;
		case IDM_FILE_QUIT:
			DestroyWindow(hWnd);
			break;
		default:
			return DefWindowProc(hWnd, message, wParam, lParam);
		}
	}
	break;
	case WM_PAINT:
	{
		/*HINSTANCE hinstLib;
		MYPROC ProcAdd;
		hinstLib = LoadLibrary(L"MathLibrary.dll");*/
		
		PAINTSTRUCT ps;
		HDC hdc = BeginPaint(hWnd, &ps);
		for (int i = 0; i < LineShape.SizeOfShape(); i++) {
			LineShape.ShapeIndex(i).Draw(hdc);
		}
		for (int i = 0; i < RectangleShape.SizeOfShape(); i++) {
			RectangleShape.ShapeIndex(i).Draw(hdc);
		}
		for (int i = 0; i < CircleShape.SizeOfShape(); i++) {
			CircleShape.ShapeIndex(i).Draw(hdc);
		}

		if (isLine)
		{
			if (isDrawing) {
				MoveToEx(hdc, currentX, currentY, NULL);
				LineTo(hdc, lastX, lastY);
			}
		}
		if (isDllLine)
		{
			if (isDrawing)
			{
				/*if (hinstLib) {
					ProcAdd = (MYPROC)GetProcAddress(hinstLib, "Functions_DrawLine");
					if (ProcAdd != NULL) {
						MathLibrary::Functions::DrawLine(hdc, currentX, currentY, lastX, lastY);
					}
				}*/
				MathLibrary::Functions::DrawLine(hdc, currentX, currentY, lastX, lastY);
			}
		}
		if (isRectangle)
		{
			if (isDrawing) {
				Rectangle(hdc, currentX, currentY, lastX, lastY);
			}
		}
		if (isDllRectangle)
		{
			if (isDrawing) {
				MathLibrary::Functions::DrawRectangle(hdc, currentX, currentY, lastX, lastY);
			}
		}
		if (isCircle)
		{
			if (isDrawing) {
				Ellipse(hdc, currentX, currentY, lastX, lastY);
			}
		}
		if (isDllCircle)
		{
			if (isDrawing) {
				MathLibrary::Functions::DrawCircle(hdc, currentX, currentY, lastX, lastY);
			}
		}
		EndPaint(hWnd, &ps);
	}
	break;
	case WM_DESTROY:
		if (isWriteLine)
		{
			LineShape.WriteFile("1.bin");
		}
		if (isWriteRectangle)
		{
			RectangleShape.WriteFile("2.bin");
		}
		if (isWriteCircle)
		{
			CircleShape.WriteFile("3.bin");
		}
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

