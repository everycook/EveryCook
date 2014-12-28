@if (@X)==(@Y) @end /* harmless hybrid line that begins a JScrpt comment

::: Batch part ::::
@echo off
cscript //nologo //e:JScript "%~f0" %*
exit /b

*** JScript part ***/
if (WScript.Arguments.Named.Exists("n")) {
  WScript.StdOut.WriteLine(eval(WScript.Arguments.Unnamed(0)));
} else {
  WScript.StdOut.Write(eval(WScript.Arguments.Unnamed(0)));
}