document.addEventListener('DOMContentLoaded', function() {
    const navItems = document.querySelectorAll('.nav-item');
    const sidebarGroups = document.querySelectorAll('.sidebar-group');

    navItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            // 1. Reset màu navbar
            navItems.forEach(nav => {
                nav.classList.remove('bg-slate-900', 'text-white', 'shadow-inner');
                nav.classList.add('text-slate-300');
            });

            // 2. Active nút hiện tại
            this.classList.remove('text-slate-300');
            this.classList.add('bg-slate-900', 'text-white', 'shadow-inner');

            // 3. Ẩn tất cả sidebar
            sidebarGroups.forEach(group => {
                group.classList.remove('block');
                group.classList.add('hidden');
            });

            // 4. Hiện sidebar tương ứng
            const targetId = this.getAttribute('data-target');
            const targetElement = document.getElementById(targetId);
            if(targetElement) {
                targetElement.classList.remove('hidden');
                targetElement.classList.add('block');
            }
        });
    });
});