<script>
    tinymce.init({
        selector: '#editor-vattu',
        license_key: 'gpl',
        menubar: false, // Ẩn hoàn toàn thanh menu như bạn muốn
        plugins: 'table lists link image',
        toolbar: 'undo redo | bold italic | table | bullist numlist',
        setup: function (editor) {
            editor.on('KeyUp Change NodeChange', function () {
                if (typeof updatePreview === "function") {
                    updatePreview();
                }
            });
        }
    });
</script>