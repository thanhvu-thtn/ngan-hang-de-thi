<style>
    /* CSS hỗ trợ highlight khi checkbox được chọn */
    .highlight-active {
        background-color: #eff6ff !important; /* bg-blue-50 */
        border-color: #93c5fd !important; /* border-blue-300 */
    }
    .highlight-text {
        color: #2563eb !important; /* text-blue-600 */
        font-weight: 600;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Hàm xử lý đóng/mở các nhánh cây
    function setupToggle(triggerSelector, targetSelector) {
        document.querySelectorAll(triggerSelector).forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                // Không kích hoạt nếu bấm nhầm vào checkbox
                if (e.target.type === 'checkbox') return; 
                
                const parent = this.parentElement;
                const target = parent.querySelector(targetSelector);
                const icon = this.querySelector('.fa-caret-right');

                if (target) {
                    const isHidden = target.classList.contains('hidden');
                    
                    // Toggle nội dung
                    target.classList.toggle('hidden');
                    
                    // Xoay icon mũi tên
                    if (icon) {
                        icon.style.transform = isHidden ? 'rotate(90deg)' : 'rotate(0deg)';
                    }
                }
            });
        });
    }

    // Gắn sự kiện click cho các cấp
    setupToggle('.grade-header', '.topic-list');
    setupToggle('.topic-header', '.content-list');
    setupToggle('.content-toggle', '.objective-list');

    // 2. Hàm xử lý highlight các cấp cha khi chọn mục tiêu (checkbox)
    const checkboxes = document.querySelectorAll('.obj-checkbox');

    function updateGenealogyHighlight() {
        // Reset toàn bộ highlight trước
        document.querySelectorAll('.highlight-active').forEach(el => el.classList.remove('highlight-active'));
        document.querySelectorAll('.highlight-text').forEach(el => el.classList.remove('highlight-text'));

        // Quét các checkbox đang được check
        document.querySelectorAll('.obj-checkbox:checked').forEach(checkedBox => {
            // Highlight cấp 3 (Nội dung)
            const contentNode = checkedBox.closest('.content-node')?.querySelector('.content-toggle');
            if (contentNode) contentNode.classList.add('highlight-text');

            // Highlight cấp 2 (Chuyên đề)
            const topicNode = checkedBox.closest('.topic-node')?.querySelector('.topic-header');
            if (topicNode) topicNode.classList.add('highlight-text');

            // Highlight cấp 1 (Khối)
            const gradeNode = checkedBox.closest('.grade-node')?.querySelector('.grade-header');
            if (gradeNode) gradeNode.classList.add('highlight-active');
        });
    }
    
    // Lắng nghe sự thay đổi của các checkbox
    checkboxes.forEach(box => box.addEventListener('change', updateGenealogyHighlight));

    // Chạy thử 1 lần lúc load trang để highlight sẵn nếu có checkbox đã được check (phục vụ cho edit hoặc giữ state khi lỗi validation)
    updateGenealogyHighlight();
});
</script>