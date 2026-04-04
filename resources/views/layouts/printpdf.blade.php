<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Bản in')</title>

    <style>
        @page {
            size: A4;
            margin: 2cm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            /* Buộc Pandoc không chia nhỏ bảng */
            table-layout: fixed;
        }

        td {
            vertical-align: top;
            padding: 5px;
            word-wrap: break-word;
        }
    </style>

</head>

<body>

    <div class="katex-scan">
        @yield('content')
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            renderMathInElement(document.body, {
                delimiters: [{
                        left: "$$",
                        right: "$$",
                        display: true
                    },
                    {
                        left: "$",
                        right: "$",
                        display: false
                    },
                    {
                        left: "\\(",
                        right: "\\)",
                        display: false
                    },
                    {
                        left: "\\[",
                        right: "\\]",
                        display: true
                    }
                ],
                throwOnError: false
            });
        });
    </script>
</body>

</html>
