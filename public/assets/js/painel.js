(function () {
    'use strict';

    function setupSidebar() {
        const shell = document.querySelector('.panel-shell');
        const sidebar = document.getElementById('panel-sidebar');
        const toggle = document.getElementById('sidebar-toggle');

        if (!shell || !sidebar || !toggle) {
            return;
        }

        const storageKey = 'crm.panel.sidebar.collapsed';
        const accordionItems = sidebar.querySelectorAll('.panel-accordion .accordion-item');

        function closeAccordions() {
            accordionItems.forEach(function (item) {
                item.open = false;
            });
        }

        function saveCollapsed(collapsed) {
            try {
                window.localStorage.setItem(storageKey, String(collapsed));
            } catch (error) {
                // O controle continua funcionando quando o armazenamento não está disponível.
            }
        }

        function setCollapsed(collapsed) {
            const label = collapsed ? 'Expandir menu lateral' : 'Recolher menu lateral';
            shell.classList.toggle('is-sidebar-collapsed', collapsed);
            toggle.setAttribute('aria-expanded', String(!collapsed));
            toggle.setAttribute('aria-label', label);
            toggle.setAttribute('title', label);

            if (collapsed) {
                closeAccordions();
            }
        }

        try {
            setCollapsed(window.localStorage.getItem(storageKey) === 'true');
        } catch (error) {
            setCollapsed(false);
        }

        toggle.addEventListener('click', function () {
            const collapsed = !shell.classList.contains('is-sidebar-collapsed');
            setCollapsed(collapsed);
            saveCollapsed(collapsed);
        });

        accordionItems.forEach(function (item) {
            const summary = item.querySelector('.accordion-header');

            if (!summary) {
                return;
            }

            const label = summary.querySelector('span');
            const name = label ? label.textContent.trim().toLocaleLowerCase('pt-BR') : 'menu';

            function updateTitle() {
                summary.setAttribute('title', (item.open ? 'Fechar ' : 'Abrir ') + name);
            }

            updateTitle();
            item.addEventListener('toggle', updateTitle);

            summary.addEventListener('click', function () {
                if (shell.classList.contains('is-sidebar-collapsed')) {
                    setCollapsed(false);
                    saveCollapsed(false);
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupSidebar, { once: true });
    } else {
        setupSidebar();
    }
}());
