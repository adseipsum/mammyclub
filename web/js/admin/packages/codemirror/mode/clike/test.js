(function(){var c=CodeMirror.getMode({indentUnit:2},"text/x-c");function b(e){test.mode(e,c,Array.prototype.slice.call(arguments,1))}b("indent","[variable-3 void] [def foo]([variable-3 void*] [variable a], [variable-3 int] [variable b]) {","  [variable-3 int] [variable c] [operator =] [variable b] [operator +]","    [number 1];","  [keyword return] [operator *][variable a];","}");b("indent_switch","[keyword switch] ([variable x]) {","  [keyword case] [number 10]:","    [keyword return] [number 20];","  [keyword default]:",'    [variable printf]([string "foo %c"], [variable x]);',"}");b("def","[variable-3 void] [def foo]() {}","[keyword struct] [def bar]{}","[variable-3 int] [variable-3 *][def baz]() {}");b("double_block","[keyword for] (;;)","  [keyword for] (;;)","    [variable x][operator ++];","[keyword return];");b("preprocessor","[meta #define FOO 3]","[variable-3 int] [variable foo];","[meta #define BAR\\]","[meta 4]","[variable-3 unsigned] [variable-3 int] [variable bar] [operator =] [number 8];","[meta #include <baz> ][comment // comment]");var a=CodeMirror.getMode({indentUnit:2},"text/x-c++src");function d(e){test.mode(e,a,Array.prototype.slice.call(arguments,1))}d("cpp14_literal","[number 10'000];","[number 0b10'000];","[number 0x10'000];","[string '100000'];")})();