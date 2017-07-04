#include "stdafx.h"
HWND hWnd;
HHOOK hMyHook;
HINSTANCE hInstance;

bool isDoing;
bool GetActive();
BOOL APIENTRY DllMain(HMODULE hModule,
	DWORD  ul_reason_for_call,
	LPVOID lpReserved
)
{
	hInstance = hModule;
	switch (ul_reason_for_call)
	{
	case DLL_PROCESS_ATTACH:
	case DLL_THREAD_ATTACH:
	case DLL_THREAD_DETACH:
	case DLL_PROCESS_DETACH:
		break;
	}
	return TRUE;
}

LRESULT CALLBACK KeyboardProc(int nCode, WPARAM wParam, LPARAM lParam);

INT InitKeyboardHook(HWND hwndYourWindow)
{
	hWnd = hwndYourWindow;
	isDoing = TRUE;
	GetActive();
	return 1;
}

LRESULT CALLBACK KeyboardProc(int nCode, WPARAM wParam, LPARAM lParam)
{
	MSG *msg = NULL;
	msg = (MSG*)lParam;
	if (nCode == HC_ACTION)
	{
		switch (wParam)
		{
			case VK_CONTROL:
			{
				if (GetAsyncKeyState(VK_MENU) != 0 && GetAsyncKeyState(VK_SHIFT) != 0 && GetAsyncKeyState(0x53) != 0) //0x53 = s key - virtual-key code
				{
					isDoing = TRUE;
					GetActive();
				}
			}
		}
	}
	return CallNextHookEx(0, nCode, wParam, lParam);
}

bool GetActive()
{
	if (isDoing == FALSE)
	{
		return FALSE;
	}
	else
	{
		return TRUE;
	}
		
}
INT UninstallKeyboardHook()
{
	//UnhookWindowsHookEx(hMyHook);
	isDoing = FALSE;
	GetActive();
	return 0;
}


