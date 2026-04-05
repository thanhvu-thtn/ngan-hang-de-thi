<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Word</title>
</head>
<body>

    <h1 style="text-align: center;">DANH SÁCH MÃ CÁC YÊU CẦU CẦN ĐẠT</h1>
    <h2 style="text-align: center; text-transform: uppercase;">
        {{ mb_strtoupper($subject->name) }} – KHỐI {{ $grade }} – {{ mb_strtoupper($topicType->name) }}
    </h2>

    @foreach($topics as $tIndex => $topic)
        <h3>{{ $tIndex + 1 }}. Chuyên đề: {{ $topic->name }}</h3>
        
        @foreach($topic->contents as $cIndex => $content)
            <h4>{{ $tIndex + 1 }}.{{ $cIndex + 1 }} Nội dung: {{ $content->name }}</h4>
            
            <table custom-style="Table Grid" border="1" style="border-collapse: collapse; width: 100%;">
                <colgroup>
                    <col style="width: 10%;" />
                    <col style="width: 60%;" />
                    <col style="width: 30%;" />
                </colgroup>
                
                <thead>
                    <tr>
                        <th style="text-align: center; width: 10%;">STT</th>
                        <th style="text-align: center; width: 60%;">Yêu cầu cần đạt</th>
                        <th style="text-align: center; width: 30%;">Mã định danh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($content->objectives as $oIndex => $objective)
                        <tr>
                            <td style="text-align: center; vertical-align: top;">{{ $oIndex + 1 }}</td>
                            <td style="vertical-align: top;">{!! $objective->description !!}</td>
                            <td style="vertical-align: top;">{{ $objective->tag_name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; font-style: italic;">Chưa có yêu cầu cần đạt nào cho nội dung này.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <br>
        @endforeach
    @endforeach

</body>
</html>