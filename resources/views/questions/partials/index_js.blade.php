<script>
    document.addEventListener('DOMContentLoaded', function() {
        const treeContainer = document.getElementById('tree-view-container');

        // Hàm Render Math (Chỉ chạy KaTeX cho màn hình này)
        function refreshMath() {
            if (typeof renderMathInElement === 'function' && treeContainer) {
                renderMathInElement(treeContainer, {
                    delimiters: [
                        {left: '$$', right: '$$', display: true},
                        {left: '$', right: '$', display: false},
                        {left: '\\(', right: '\\)', display: false}
                    ],
                    throwOnError: false
                });
            }
        }

        // 1. Nút Hiện/Ẩn bộ lọc chính
        const toggleBtn = document.getElementById('toggle-tree-btn');
        const btnText = document.getElementById('toggle-btn-text');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const isHidden = treeContainer.classList.contains('hidden');
                treeContainer.classList.toggle('hidden');
                btnText.innerText = isHidden ? 'Ẩn bộ lọc chuyên đề' : 'Hiện bộ lọc chuyên đề';
                if (isHidden) refreshMath();
            });
        }

        // 2. Lệnh đóng cho nút X
        document.getElementById('close-tree-btn')?.addEventListener('click', () => toggleBtn.click());

        refreshMath();
    });
</script>