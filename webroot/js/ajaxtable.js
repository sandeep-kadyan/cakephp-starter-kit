/**
 * Alpine.js AjaxTable Component
 */
function ajaxTable() {
    return {
        // Data properties
        allData: [],
        filteredData: [],
        displayedData: [],
        
        // Search and sort properties
        searchTerm: '',
        sortField: 'id',
        sortDirection: 'asc',
        
        // Pagination properties
        currentPage: 1,
        pageSize: 10,
        totalPages: 1,
        startRecord: 1,
        endRecord: 10,
        
        // UI state
        loading: false,
        expandedRows: {}, // for nested/overflow columns
        selectedRows: [], // for row selection
        allVisibleChecked: false, // for header checkbox
        
        // Configuration
        options: {},
        columns: [],
        apiUrl: '',
        actionUrl: '',
        tableId: '',
        totalRecords: 0,
        csrfToken:'',
        mainColumns: [],
        extraColumns: [],
        hasExtraColumns: false,

        // Initialize the component
        init() {
            const wrapper = this.$root;
            this.apiUrl = wrapper.getAttribute('data-api');
            this.actionUrl = wrapper.getAttribute('data-action');
            let columnsRaw = wrapper.getAttribute('data-columns');
            try {
                let parsed = JSON.parse(columnsRaw);
                // Always convert to array of objects with field property
                if (Array.isArray(parsed)) {
                    this.columns = parsed.map(col => typeof col === 'string' ? { field: col, title: col } : col);
                } else if (typeof parsed === 'object' && parsed !== null) {
                    this.columns = Object.values(parsed).map(col => typeof col === 'string' ? { field: col, title: col } : col);
                } else {
                    this.columns = [];
                }
            } catch (e) {
                this.columns = [];
            }
            this.tableId = wrapper.getAttribute('data-table-id');
            this.csrfToken = document.querySelector('meta[name="csrfToken"]')?.getAttribute('content') || '';
            // Restore pagination state from sessionStorage
            const state = JSON.parse(sessionStorage.getItem(this.tableId) || '{}');
            // Default sort from data attributes
            const defaultSortField = wrapper.getAttribute('data-default-sort-field');
            const defaultSortDirection = wrapper.getAttribute('data-default-sort-direction');
            if (state.pageSize) this.pageSize = state.pageSize;
            else this.pageSize = parseInt(wrapper.getAttribute('data-page-size')) || this.pageSize;
            if (state.currentPage) this.currentPage = state.currentPage;
            else this.currentPage = 1;
            if (state.sortField) this.sortField = state.sortField;
            else if (defaultSortField) this.sortField = defaultSortField;
            else this.sortField = 'id';
            if (state.sortDirection) this.sortDirection = state.sortDirection;
            else if (defaultSortDirection) this.sortDirection = defaultSortDirection;
            else this.sortDirection = 'asc';
            // Save default state on page load

            // Get mainColumns and extraColumns from data attributes
            this.mainColumns = JSON.parse(wrapper.getAttribute('data-main-columns') || '[]');
            this.extraColumns = JSON.parse(wrapper.getAttribute('data-extra-columns') || '[]');
            this.hasExtraColumns = wrapper.getAttribute('data-has-extra-columns') === '1';

            // Bind expand/collapse event delegation ONCE
            const tbody = this.$root.querySelector('tbody');
            if (tbody) {
                tbody.addEventListener('click', (e) => {
                    const btn = e.target.closest('button[data-expand-key]');
                    if (btn) {
                        const expandKey = btn.getAttribute('data-expand-key');
                        // Toggle: if already open, close it; otherwise, open only this one
                        if (this.expandedRows[expandKey]) {
                            this.expandedRows = {};
                        } else {
                            this.expandedRows = {};
                            this.expandedRows[expandKey] = true;
                        }
                        this.renderTableBody();
                    }
                });
            }
            this.loadData();
            this.testAjaxTable();
        },

        // Clear state and reset (only sort, page, etc.)
        clearState() {
            // Remove sessionStorage for this table
            sessionStorage.removeItem(this.tableId);
            this.sortField = 'id';
            this.sortDirection = 'asc';
            this.pageSize = 10;
            this.currentPage = 1;
            this.loadData();
        },

        // Fetch data from server
        async loadData() {
            this.loading = true;
            try {
                const postData = {
                    search: this.searchTerm,
                    sort: this.sortField,
                    direction: this.sortDirection,
                    page: this.currentPage,
                    pageSize: this.pageSize
                };
                const response = await fetch(`${this.apiUrl}`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': this.csrfToken
                    },
                    body: JSON.stringify(postData)
                });
                if (!response.ok) throw new Error('Failed to fetch data');
                const data = await response.json();
                this.allData = data.results || [];
                this.totalRecords = data.totalRecords || this.allData.length;
                this.displayedData = this.allData;
                this.totalPages = Math.ceil(this.totalRecords / this.pageSize);
                this.startRecord = data.startRecord;
                this.endRecord = data.endRecord;
                this.savePaginationState();
            } catch (error) {
                console.log(error);
                this.allData = [];
                this.displayedData = [];
                this.totalRecords = 0;
                this.savePaginationState();
            } finally {
                this.renderTableBody();
                this.loading = false;
            }
        },

        // Render table body using columns, with overflow/nested row support and selection/expand
        renderTableBody() {
            const tbody = this.$root.querySelector('tbody');
            if (!tbody) return;
            tbody.innerHTML = '';
            //tbody.style.minHeight = '100px';
            if (this.displayedData.length === 0) {
                // Count columns: checkbox + (expand if any) + mainColumns + actions
                let colCount = 1 + (this.hasExtraColumns ? 1 : 0) + this.mainColumns.length + (this.options.showActions !== false ? 1 : 0);
                const tr = document.createElement('tr');
                const td = document.createElement('td');
                td.colSpan = colCount;
                td.className = 'text-center text-sm py-3 text-neutral-800 dark:text-neutral-100';
                td.textContent = 'No data available';
                tr.appendChild(td);
                tbody.appendChild(tr);
                return;
            }
            this.displayedData.forEach((item, rowIdx) => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-neutral-50 dark:hover:bg-white/10 cursor-pointer';
                // Add row click handler to open the view page, but ignore clicks on checkboxes or actions
                tr.addEventListener('click', (e) => {
                    // Ignore clicks on checkboxes or action buttons/links
                    if (
                        e.target.closest('td[data-field="__select__"]') ||
                        e.target.closest('td[data-field="actions"]') ||
                        e.target.closest('button') ||
                        e.target.closest('a')
                    ) {
                        return;
                    }
                    window.location.href = `${this.actionUrl}/view/${item.id}`;
                });
                // Checkbox column
                const tdCheckbox = document.createElement('td');
                tdCheckbox.className = 'px-4 py-1 text-center';
                tdCheckbox.setAttribute('data-field', '__select__');
                // Custom styled checkbox with SVG checkmark
                const label = document.createElement('label');
                label.className = 'relative inline-flex items-center cursor-pointer';
                const checkbox = document.createElement('input');
                checkbox.name = 'checkbox';
                checkbox.id = item.id;
                checkbox.type = 'checkbox';
                checkbox.autocomplete = true;
                checkbox.value = item.id;
                checkbox.checked = this.selectedRows.includes(item.id);
                checkbox.className = 'appearance-none w-4 h-4 border border-neutral-300 rounded checked:bg-black dark:checked:bg-white checked:border-black dark:checked:border-white focus:outline-none transition-all duration-150 align-middle';
                checkbox.addEventListener('change', () => this.toggleRowSelect(item.id));
                label.appendChild(checkbox);
                // SVG checkmark
                const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                svg.setAttribute('class', 'absolute w-3 h-3 pointer-events-none left-0 top-0 m-0.5');
                svg.setAttribute('fill', 'none');
                svg.setAttribute('stroke', 'currentColor');
                svg.setAttribute('stroke-width', '3');
                svg.setAttribute('viewBox', '0 0 24 24');
                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('d', 'M5 13l4 4L19 7');
                // Dynamically set stroke color based on theme
                path.setAttribute('stroke', document.documentElement.classList.contains('dark') ? 'black' : 'white');
                svg.appendChild(path);
                // Show SVG only if checked
                if (checkbox.checked) {
                    svg.style.display = '';
                } else {
                    svg.style.display = 'none';
                }
                checkbox.addEventListener('change', () => {
                    svg.style.display = checkbox.checked ? '' : 'none';
                });
                label.appendChild(svg);
                tdCheckbox.appendChild(label);
                tr.appendChild(tdCheckbox);
                // Expand/collapse icon column (only if extra columns)
                let expandKey = null;
                if (this.hasExtraColumns) {
                    const tdExpand = document.createElement('td');
                    tdExpand.className = 'px-2 py-1 text-center';
                    tdExpand.setAttribute('data-field', '__expand__');
                    expandKey = 'row_' + item.id;
                    tdExpand.innerHTML = `<button type="button" class="material-icons rounded-md align-middle text-xl cursor-pointer hover:bg-neutral-100 dark:hover:bg-white/10 dark:focus:bg-white/10 dark:active:bg-white/10 focus:bg-neutral-100 active:bg-neutral-100 p-2 hover:text-neutral-700 dark:hover:text-white" aria-label="Expand" data-expand-key="${expandKey}">${this.expandedRows[expandKey] ? 'expand_more' : 'chevron_right'}</button>`;
                    tr.appendChild(tdExpand);
                }
                // Main columns only (exclude extra columns)
                for (const field of this.mainColumns) {
                    if (field === 'actions') continue;
                    if (this.extraColumns.includes(field)) continue; // skip extra columns in main row
                    const col = this.columns.find(c => c.field === field);
                    const td = document.createElement('td');
                    td.className = 'px-6 py-1 whitespace-nowrap text-sm text-neutral-800 dark:text-neutral-100';
                    td.setAttribute('data-field', field);
                    let value = '';
                    if (col && col.render && typeof window[col.render] === 'function') {
                        value = window[col.render](item);
                        td.innerHTML = value;
                    } else if (col && col.type === 'belongsTo') {
                        // Handle foreign key relationships
                        value = this.renderForeignKeyLink(item, field, col);
                        td.innerHTML = value;
                    } else {
                        value = this.getNestedValue(item, field);
                        td.textContent = value !== null && value !== undefined ? value : '';
                    }
                    tr.appendChild(td);
                }
                // Actions column (always last, always visible)
                if (this.options.showActions !== false) {
                    const td = document.createElement('td');
                    td.className = 'px-6 py-1 whitespace-nowrap text-sm font-medium table-cell text-right';
                    td.setAttribute('data-field', 'actions');
                    // Dropdown logic: unique id for each row
                    const dropdownId = `dropdown-actions-${item.id}`;
                    td.innerHTML = `
                        <div class="relative inline-block text-left">
                            <button type="button" class="material-icons rounded-md align-middle text-xl cursor-pointer hover:bg-neutral-100 dark:hover:bg-white/10 focus:bg-neutral-100 dark:focus:bg-white/10 active:bg-neutral-100 dark:active:bg-white/10 p-2 hover:text-neutral-700 dark:hover:text-white" onclick="toggleActionsDropdown('${dropdownId}')">more_vert</button>
                            <div id="${dropdownId}" class="hidden origin-top-right absolute z-20 right-10 top-0 w-36 rounded-md shadow-lg bg-white dark:bg-neutral-800 dark:text-white ring-1 ring-black dark:ring-neutral-700 ring-opacity-5 focus:outline-none">
                                <div class="p-1">
                                    <a href="${this.actionUrl}/view/${item.id}" class="px-2 lg:py-1.5 py-2 w-full flex items-center gap-2 rounded-md transition-colors text-left text-gray-800 hover:bg-gray-50 focus-visible:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"><span class="material-icons text-sm">article</span>View</a>
                                    <a href="${this.actionUrl}/edit/${item.id}" class="px-2 lg:py-1.5 py-2 w-full flex items-center gap-2 rounded-md transition-colors text-left text-gray-800 hover:bg-gray-50 focus-visible:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"><span class="material-icons text-sm">edit</span>Edit</a>
                                    <button onclick="deleteItem('${this.actionUrl}', '${item.id}', '${this.csrfToken}')" class="px-2 lg:py-1.5 py-2 w-full rounded-md text-left text-gray-800 hover:bg-red-50 hover:text-red-600 focus-visible:bg-red-50 focus-visible:text-red-600 flex items-center align-middle gap-2"><span class="material-icons text-[14px]">delete</span>Delete</button>
                                </div>
                            </div>
                        </div>
                    `;
                    tr.appendChild(td);
                }
                tbody.appendChild(tr);
                // Nested row for extra columns (only if extra columns)
                if (this.hasExtraColumns && expandKey && this.expandedRows[expandKey]) {
                    const trNested = document.createElement('tr');
                    trNested.className = 'bg-neutral-50 dark:bg-white/20';
                    const tdNested = document.createElement('td');
                    tdNested.colSpan = 2 + this.mainColumns.length + 1; // checkbox + expand + main + actions
                    tdNested.className = 'py-6 px-8';
                    let nestedHtml = '<div class="grid grid-cols-1 gap-2 md:grid-cols-2">';
                    for (const field of this.extraColumns) {
                        const col = this.columns.find(c => c.field === field);
                        let title = field;
                        let value = '';
                        if (col) {
                            title = col.title || field;
                            if (col.render && typeof window[col.render] === 'function') {
                                value = window[col.render](item);
                            } else if (col.type === 'belongsTo') {
                                // Handle foreign key relationships in nested rows
                                value = this.renderForeignKeyLink(item, field, col);
                            } else {
                                value = this.getNestedValue(item, field);
                            }
                        } else {
                            value = this.getNestedValue(item, field);
                        }
                        nestedHtml += `<div class=\"grid grid-cols-2 w-full\"><span class=\"font-semibold text-sm text-left pr-2 w-full\">${title}</span><span class=\"text-left w-full flex\"><span>:</span><span class="pl-4 overflow-auto break-keep">${value !== null && value !== undefined ? value : ''}</span></span></div>`;
                    }
                    nestedHtml += '</div>';
                    tdNested.innerHTML = nestedHtml;
                    trNested.appendChild(tdNested);
                    // Insert nested row immediately after the main row
                    if (tr.nextSibling) {
                        tbody.insertBefore(trNested, tr.nextSibling);
                    } else {
                        tbody.appendChild(trNested);
                    }
                }
            });
            this.updateAllVisibleChecked();
            this.testAjaxTable();
        },

        // Search functionality
        search() {
            this.currentPage = 1; // Always reset to first page on search
            this.savePaginationState();
            this.loadData();
        },

        // Sort functionality
        sort(field) {
            this.currentPage = 1; // Always reset to first page on sort
            this.savePaginationState();
            if (this.sortField === field) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortField = field;
                this.sortDirection = 'asc';
            }
            this.loadData();
        },

        // Pagination methods
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.savePaginationState();
                this.loadData();
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.savePaginationState();
                this.loadData();
            }
        },

        firstPage() {
            console.log('firstPage clicked', this.currentPage);
            if (this.currentPage !== 1) {
                this.currentPage = 1;
                this.savePaginationState();
                this.loadData();
            }
        },

        lastPage() {
            console.log('lastPage clicked', this.currentPage, this.totalPages);
            if (this.currentPage !== this.totalPages) {
                this.currentPage = this.totalPages;
                this.savePaginationState();
                this.loadData();
            }
        },

        // Watcher for pageSize (call this in @change handler)
        onPageSizeChange() {
            this.currentPage = 1; // Reset to first page on page size change
            this.savePaginationState();
            this.loadData();
        },

        // Utility function to get nested object values
        getNestedValue(obj, path) {
            if (!path) return '';
            if (typeof path === 'string') {
                return path.split('.').reduce((current, key) => {
                    return current && current[key] !== undefined ? current[key] : null;
                }, obj);
            }
            return obj[path] !== undefined ? obj[path] : '';
        },

        // Delete item functionality (calls global deleteItem)
        deleteItem(id) {
            window.deleteItem(this.actionUrl, id);
        },

        // Format helpers (optional, can be used in render)
        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString();
        },

        formatDateTime(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleString();
        },

        truncateText(text, length = 50) {
            if (!text) return '';
            return text.length > length ? text.substring(0, length) + '...' : text;
        },

        renderForeignKeyLink(item, field, col) {
            // Extract the related model name from the foreign key field
            const relatedModel = field.replace('_id', '');

            // Check if the related data exists in the item
            const relatedData = item[relatedModel];

            if (relatedData && relatedData.id) {
                // Get the display field (name, title, etc.) or fallback to id
                let displayValue = relatedData.name || relatedData.title || relatedData.username || relatedData.email || relatedData.id;
                
                // Create the URL for the related model's view page
                const relatedUrl = `${col.route}/${relatedData.id}`;
                
                // Return the HTML link
                return `<a href="${relatedUrl}" class="text-neutral-800 hover:text-neutral-900 dark:text-neutral-100 dark:hover:text-neutral-200 underline">${displayValue}</a>`;
            } else {
                // If no related data, show the foreign key value or empty
                const fkValue = this.getNestedValue(item, field);
                return fkValue !== null && fkValue !== undefined ? fkValue : '-';
            }
        },

        // Selection logic
        toggleRowSelect(rowId) {
            const idx = this.selectedRows.indexOf(rowId);
            if (idx === -1) {
                this.selectedRows.push(rowId);
            } else {
                this.selectedRows.splice(idx, 1);
            }
            this.updateAllVisibleChecked();
        },
        toggleSelectAll(event) {
            if (event.target.checked) {
                this.selectedRows = Array.from(new Set([...this.selectedRows, ...this.displayedData.map(item => item.id)]));
            } else {
                this.selectedRows = this.selectedRows.filter(id => !this.displayedData.some(item => item.id === id));
            }
            this.updateAllVisibleChecked();
            this.renderTableBody();
        },
        selectAllRecords() {
            this.selectedRows = this.allData.map(item => item.id);
            this.updateAllVisibleChecked();
            this.renderTableBody();
        },
        selectAllVisibleRecords() {
            this.selectedRows = Array.from(new Set([...this.selectedRows, ...this.displayedData.map(item => item.id)]));
            this.updateAllVisibleChecked();
            this.renderTableBody();
        },
        updateAllVisibleChecked() {
            this.allVisibleChecked = this.displayedData.length > 0 && this.displayedData.every(item => this.selectedRows.includes(item.id));
        },
        deleteSelected() {
            if (this.selectedRows.length === 0) {
                alert('No records selected.');
                return;
            }
            if (!confirm('Are you sure you want to delete the selected records?')) return;
            // Example: POST to /controller/bulk-delete with {ids: [...]}
            fetch(`${this.actionUrl}/bulk-delete`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': this.csrfToken
                },
                body: JSON.stringify({ids: this.selectedRows})
            }).then(response => {
                if (response.ok) {
                   window.location.reload();
                } else {
                    alert('Error deleting records');
                }
            }).catch(error => {
                alert('Error deleting records');
            });

            this.currentPage = 1; // Always reset to first page on search
            this.savePaginationState();
        },
        testAjaxTable() {
            // Log visible columns and DOM structure for header and body
            const thead = this.$root.querySelector('thead');
            const tbody = this.$root.querySelector('tbody');
            const headerFields = Array.from(thead.querySelectorAll('th')).map(th => th.getAttribute('data-field'));
            const bodyFields = Array.from(tbody.querySelectorAll('tr')[0]?.querySelectorAll('td') || []).map(td => td.getAttribute('data-field'));
        },
        renderTableHeader() {
            const thead = this.$root.querySelector('thead');
            if (!thead) return;
            thead.innerHTML = '';
            const tr = document.createElement('tr');
            // Checkbox header
            const thCheckbox = document.createElement('th');
            thCheckbox.className = 'px-4 py-3';
            thCheckbox.setAttribute('data-field', '__select__');
            const selectAll = document.createElement('input');
            selectAll.type = 'checkbox';
            selectAll.checked = this.allVisibleChecked;
            selectAll.addEventListener('change', (e) => this.toggleSelectAll(e));
            thCheckbox.appendChild(selectAll);
            tr.appendChild(thCheckbox);
            // Expand/collapse header (empty)
            const thExpand = document.createElement('th');
            thExpand.className = 'px-2 py-3';
            thExpand.setAttribute('data-field', '__expand__');
            tr.appendChild(thExpand);
            // Data columns (iterate in original order, only visible columns, always before Actions)
            for (const col of this.columns) {
                const field = String(typeof col === 'string' ? col : (col.field || col));
                if (field === 'actions') continue;
                if (!this.visibleColumns.includes(field)) continue;
                const th = document.createElement('th');
                th.className = 'px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider';
                th.setAttribute('data-field', field);
                th.textContent = col.title || (typeof col === 'string' ? col : field);
                tr.appendChild(th);
            }
            // Actions column header (always last, always visible)
            if (this.options.showActions !== false) {
                const th = document.createElement('th');
                th.className = 'px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider table-cell';
                th.setAttribute('data-field', 'actions');
                th.textContent = 'Actions';
                tr.appendChild(th);
            }
            thead.appendChild(tr);
        },
        deleteAllRecords() {
            this.selectedRows = this.allData.map(item => item.id);
            this.updateAllVisibleChecked();
            this.deleteSelected();
            
        },
        deleteAllSelectedRecords() {
            this.deleteSelected();
        },
        // Save pagination state to sessionStorage
        savePaginationState() {
            sessionStorage.setItem(this.tableId, JSON.stringify({
                pageSize: this.pageSize,
                currentPage: this.currentPage,
                sortField: this.sortField,
                sortDirection: this.sortDirection
            }));
        },
    };
}

// Global function for delete action (fallback)
window.deleteItem = function(controller, id, csrfToken) {
    if (confirm('Are you sure you want to delete ' +id + ' item?')) {
        fetch(`${controller}/delete/${id}`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            }
        }).then(response => {
            if (response.ok) {
                // Reload the current page to refresh the ajaxtable
                window.location.reload();
            } else {
                alert('Error deleting item');
            }
        }).catch(error => {
            alert('Error deleting item');
        });
    }
};

// Global utility functions for column rendering
window.formatDate = function(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString();
};

window.formatDateTime = function(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleString();
};

// Add global toggleActionsDropdown for dropdown logic
window.toggleActionsDropdown = function(dropdownId) {
    // Hide all other dropdowns
    document.querySelectorAll('.origin-top-right.absolute').forEach(el => {
        if (el.id !== dropdownId) el.classList.add('hidden');
    });
    const dropdown = document.getElementById(dropdownId);
    if (dropdown) {
        dropdown.classList.toggle('hidden');
        // Hide dropdown on click outside
        const handler = (event) => {
            if (!dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
                document.removeEventListener('mousedown', handler);
            }
        };
        setTimeout(() => {
            document.addEventListener('mousedown', handler);
        }, 0);
    }
};

// Make ajaxTable function globally available for Alpine.js
window.ajaxTable = ajaxTable;
