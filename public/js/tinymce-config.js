function initTinyMCE(selector, callback) {
    tinymce.init({
        selector: selector,
        license_key: 'gpl', // Chìa khóa để chạy bản local không báo lỗi key
        language: 'vi',      // Nếu bạn đã tải gói ngôn ngữ tiếng Việt
        menubar: 'edit insert format table tools', 
        plugins: 'table lists link image charmap anchor codesample',
        toolbar: 'undo redo | blocks | bold italic | table | bullist numlist | removeformat',
        height: 300,
        setup: function (editor) {
            editor.on('KeyUp Change NodeChange', function () {
                if (callback) callback(); // Gọi hàm updatePreview khi có thay đổi
            });
        }
    });
}