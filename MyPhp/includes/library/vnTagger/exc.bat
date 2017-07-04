@echo off
cd /d %0\..
java -mx500m -jar vn.hus.nlp.tagger-4.2.0.jar %*
if NOT "%COMSPEC%" == "%SystemRoot%\system32\cmd.exe" goto end
if %errorlevel% == 9009 (
	set errorlevel = 0
	"C:\Program Files (x86)\Java\jre7\bin\java" -mx500m -jar vn.hus.nlp.tagger-4.2.0.jar %*
	if %errorlevel% == 9009 echo Java is not in your PATH. Cannot run program.
	if errorlevel 1 pause
)

:end
