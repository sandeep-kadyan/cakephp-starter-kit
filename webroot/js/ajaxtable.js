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
        
        // Filter state
        filters: {},
        filterDraft: {},
        filterOpen: false,
        filterableColumns: [],
        
        // Settings state
        settingsOpen: false,
        visibleColumns: [],
        
        // History state
        historyOpen: false,
        historyEntries: [],
        lastHistoryKey: '',

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

            // Initialize visible columns (main + extra, excluding actions)
            this.visibleColumns = [...this.mainColumns, ...this.extraColumns].filter(f => f !== 'actions');
            // Initialize filterable columns (skip FK/belongsTo columns and actions)
            this.filterableColumns = this.columns.filter(c => c.field !== 'actions' && c.type !== 'belongsTo');
            // Restore filters and column visibility from sessionStorage
            if (state.filters && typeof state.filters === 'object') {
                this.filters = state.filters;
            }
            if (Array.isArray(state.visibleColumns) && state.visibleColumns.length) {
                this.visibleColumns = state.visibleColumns;
            }

            // Reflect persisted column visibility in the server-rendered header
            const thead = this.$root.querySelector('thead');
            if (thead) {
                thead.querySelectorAll('th[data-field]').forEach((th) => {
                    const field = th.getAttribute('data-field');
                    if (field === '__select__' || field === '__expand__' || field === 'actions') return;
                    th.classList.toggle('hidden', !this.visibleColumns.includes(field));
                });
            }

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
                    pageSize: this.pageSize,
                    filters: this.filters
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
                // Count columns: checkbox + (expand if any) + visible main columns + actions
                const tr = document.createElement('tr');
                const td = document.createElement('td');
                td.colSpan = this.mainRowCellCount();
                td.className = 'text-center text-sm py-3 text-muted-foreground';
                td.textContent = 'No data available';
                tr.appendChild(td);
                tbody.appendChild(tr);
                return;
            }
            this.displayedData.forEach((item, rowIdx) => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-accent/50 cursor-pointer';
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
                checkbox.className = 'appearance-none w-4 h-4 border border-border rounded bg-background checked:bg-primary checked:border-primary focus:outline-none transition-all duration-150 align-middle';
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
                    tdExpand.innerHTML = `<button type="button" class="rounded-md align-middle text-xl cursor-pointer hover:bg-accent focus:bg-accent active:bg-accent p-2 text-muted-foreground hover:text-foreground" aria-label="Expand" data-expand-key="${expandKey}"><i data-lucide="${this.expandedRows[expandKey] ? 'chevron-down' : 'chevron-right'}"></i></button>`;
                    tr.appendChild(tdExpand);
                }
                // Main columns only (exclude extra columns and hidden columns)
                for (const field of this.mainColumns) {
                    if (field === 'actions') continue;
                    if (this.extraColumns.includes(field)) continue; // skip extra columns in main row
                    if (!this.visibleColumns.includes(field)) continue; // skip hidden columns
                    const col = this.columns.find(c => c.field === field);
                    const td = document.createElement('td');
                    td.className = 'px-6 py-1 whitespace-nowrap text-sm text-foreground';
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
                                <button type="button" data-dropdown-for="${dropdownId}" aria-haspopup="true" aria-expanded="false" class="rounded-md align-middle text-xl cursor-pointer hover:bg-accent focus:bg-accent active:bg-accent p-2 text-muted-foreground hover:text-foreground" onclick="toggleActionsDropdown('${dropdownId}', this)"><i data-lucide="ellipsis"></i></button>
                                <div id="${dropdownId}" class="hidden absolute z-50 w-36 rounded-md border border-border shadow-lg bg-popover text-popover-foreground focus:outline-none" role="menu">
                                    <div class="p-1">
                                        <a href="${this.actionUrl}/view/${item.id}" class="px-2 lg:py-1.5 py-2 w-full flex items-center gap-2 rounded-md transition-colors text-left text-foreground hover:bg-accent hover:text-accent-foreground focus-visible:bg-accent disabled:opacity-50 disabled:cursor-not-allowed" role="menuitem"><i data-lucide="file-text" class="text-sm"></i>View</a>
                                        <a href="${this.actionUrl}/edit/${item.id}" class="px-2 lg:py-1.5 py-2 w-full flex items-center gap-2 rounded-md transition-colors text-left text-foreground hover:bg-accent hover:text-accent-foreground focus-visible:bg-accent disabled:opacity-50 disabled:cursor-not-allowed" role="menuitem"><i data-lucide="pencil" class="text-sm"></i>Edit</a>
                                        <button type="button" onclick="deleteItem('${this.actionUrl}', '${item.id}', '${this.csrfToken}')" class="px-2 lg:py-1.5 py-2 w-full rounded-md text-left text-foreground hover:bg-destructive hover:text-destructive-foreground focus-visible:bg-destructive focus-visible:text-destructive-foreground flex items-center align-middle gap-2" role="menuitem"><i data-lucide="trash-2" class="text-[14px]"></i>Delete</button>
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
                    trNested.className = 'bg-muted/50';
                    const tdNested = document.createElement('td');
                    const nestedColCount = this.mainRowCellCount();
                    tdNested.colSpan = nestedColCount;
                    tdNested.className = 'py-6 px-8';
                    let nestedHtml = '<dl class="grid grid-cols-1 gap-x-8 gap-y-3 md:grid-cols-2">';
                    for (const field of this.extraColumns) {
                        if (!this.visibleColumns.includes(field)) continue;
                        const col = this.columns.find(c => c.field === field);
                        let title = field;
                        let value = '';
                        let isHtml = false;
                        if (col) {
                            title = col.title || field;
                            if (col.render && typeof window[col.render] === 'function') {
                                value = window[col.render](item);
                                isHtml = true;
                            } else if (col.type === 'belongsTo') {
                                // Handle foreign key relationships in nested rows
                                value = this.renderForeignKeyLink(item, field, col);
                                isHtml = true;
                            } else {
                                value = this.getNestedValue(item, field);
                            }
                        } else {
                            value = this.getNestedValue(item, field);
                        }
                        const displayValue = value !== null && value !== undefined ? String(value) : '';
                        const safeValue = isHtml ? displayValue : this.escapeHtml(displayValue);
                        nestedHtml += `<div class="flex items-start gap-3"><dt class="w-36 shrink-0 text-sm font-semibold text-muted-foreground">${title}</dt><dd class="min-w-0 flex-1 text-sm text-foreground break-words">${safeValue}</dd></div>`;
                    }
                    nestedHtml += '</dl>';
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
            if (typeof window.createLucideIcons === 'function') {
                window.createLucideIcons();
            }
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
            this.recordHistory('Sorted by ' + this.sortField + ' ' + this.sortDirection);
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
            this.recordHistory('Page size: ' + this.pageSize);
            this.savePaginationState();
            this.loadData();
        },

        // Number of table cells in a visible main row
        mainRowCellCount() {
            let count = 1; // checkbox
            if (this.hasExtraColumns) count += 1; // expand/collapse
            count += this.mainColumns.filter(f => f !== 'actions' && this.visibleColumns.includes(f)).length;
            if (this.options.showActions !== false) count += 1; // actions
            return count;
        },

        // Number of active column filters
        activeFilterCount() {
            return Object.values(this.filters).filter(v => v !== null && v !== undefined && String(v).trim() !== '').length;
        },

        // Safe filter value getter/setter (avoids x-model on dynamic keys)
        filterValue(field) {
            return this.filterDraft[field] || '';
        },
        setFilterValue(field, value) {
            this.filterDraft[field] = value;
        },

        // Open the filter panel seeded with the current filters
        toggleFilterPanel() {
            this.filterDraft = { ...this.filters };
            this.filterOpen = !this.filterOpen;
        },
        applyFilters() {
            const draft = {};
            for (const [field, value] of Object.entries(this.filterDraft)) {
                if (value !== null && value !== undefined && String(value).trim() !== '') {
                    draft[field] = value;
                }
            }
            this.filters = draft;
            this.filterOpen = false;
            this.currentPage = 1;
            this.recordHistory('Filters applied');
            this.savePaginationState();
            this.loadData();
        },
        clearFilters() {
            this.filters = {};
            this.filterDraft = {};
            this.filterOpen = false;
            this.currentPage = 1;
            this.recordHistory('Filters cleared');
            this.savePaginationState();
            this.loadData();
        },

        // Column visibility toggles (settings panel)
        toggleColumn(field) {
            const idx = this.visibleColumns.indexOf(field);
            if (idx === -1) {
                this.visibleColumns.push(field);
            } else {
                this.visibleColumns.splice(idx, 1);
            }
        },
        applyColumnVisibility(record = true) {
            const thead = this.$root.querySelector('thead');
            if (thead) {
                thead.querySelectorAll('th[data-field]').forEach((th) => {
                    const field = th.getAttribute('data-field');
                    if (field === '__select__' || field === '__expand__' || field === 'actions') return;
                    th.classList.toggle('hidden', !this.visibleColumns.includes(field));
                });
            }
            this.renderTableBody();
            if (record) this.recordHistory('Columns updated');
            this.savePaginationState();
        },
        resetSettings() {
            this.visibleColumns = [...this.mainColumns, ...this.extraColumns].filter(f => f !== 'actions');
            this.pageSize = 10;
            this.currentPage = 1;
            this.applyColumnVisibility(false);
            this.recordHistory('Settings reset');
            this.savePaginationState();
            this.loadData();
        },

        // Action history
        recordHistory(label) {
            const key = label;
            if (key === this.lastHistoryKey) return;
            this.lastHistoryKey = key;
            const entry = {
                label,
                timestamp: new Date().toLocaleString(),
                state: {
                    searchTerm: this.searchTerm,
                    sortField: this.sortField,
                    sortDirection: this.sortDirection,
                    pageSize: this.pageSize,
                    currentPage: this.currentPage,
                    filters: { ...this.filters },
                    visibleColumns: [...this.visibleColumns]
                }
            };
            this.historyEntries.unshift(entry);
            if (this.historyEntries.length > 50) this.historyEntries.pop();
        },
        restoreHistory(entry) {
            if (!entry || !entry.state) return;
            const s = entry.state;
            this.searchTerm = s.searchTerm || '';
            this.sortField = s.sortField || 'id';
            this.sortDirection = s.sortDirection || 'asc';
            this.pageSize = s.pageSize || this.pageSize;
            this.currentPage = s.currentPage || 1;
            this.filters = s.filters || {};
            this.filterDraft = { ...this.filters };
            if (Array.isArray(s.visibleColumns) && s.visibleColumns.length) {
                this.visibleColumns = s.visibleColumns;
            }
            this.applyColumnVisibility(false);
            this.historyOpen = false;
            this.savePaginationState();
            this.loadData();
        },
        clearHistory() {
            this.historyEntries = [];
            this.lastHistoryKey = '';
        },

        // Search input handler (debounced)
        onSearchInput() {
            this.currentPage = 1;
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

        // Escape plain text values before injecting them into innerHTML
        escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
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
                return `<a href="${relatedUrl}" class="text-primary underline hover:text-primary/80">${displayValue}</a>`;
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
                th.className = 'px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider';
                th.setAttribute('data-field', field);
                th.textContent = col.title || (typeof col === 'string' ? col : field);
                tr.appendChild(th);
            }
            // Actions column header (always last, always visible)
            if (this.options.showActions !== false) {
                const th = document.createElement('th');
                th.className = 'px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider table-cell';
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
                sortDirection: this.sortDirection,
                filters: this.filters,
                visibleColumns: this.visibleColumns
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

// Global dropdown logic for the action dots menu. The menu is positioned with
// position:fixed, centered under the dots button, so it stays attached to it
// and is never clipped by the table's overflow-auto scroll container. It flips
// upward when it would otherwise overflow the bottom of the viewport.
window.toggleActionsDropdown = function(dropdownId, btn) {
    const dropdown = document.getElementById(dropdownId);
    if (!dropdown) return;

    // Close every other open ajax table dropdown
    document.querySelectorAll('.ajaxtable-dropdown-open').forEach((el) => {
        if (el !== dropdown) window.closeActionsDropdown(el);
    });

    if (!dropdown.classList.contains('hidden')) {
        window.closeActionsDropdown(dropdown);
        return;
    }

    dropdown.classList.remove('hidden');
    dropdown.classList.add('ajaxtable-dropdown-open');
    if (btn) {
        const btnRect = btn.getBoundingClientRect();
        const menuWidth = dropdown.offsetWidth || 144;
        const menuHeight = dropdown.offsetHeight || 140;
        // Center the menu under the dots button so it stays attached to it.
        let left = btnRect.left + btnRect.width / 2 - menuWidth / 2;
        let top = btnRect.bottom + 4;
        // Keep the menu fully inside the viewport.
        left = Math.max(8, Math.min(left, window.innerWidth - menuWidth - 8));
        if (top + menuHeight > window.innerHeight - 8) {
            top = btnRect.top - menuHeight - 4;
            if (top < 8) top = btnRect.bottom + 4;
        }
        dropdown.style.position = 'fixed';
        dropdown.style.left = Math.round(left) + 'px';
        dropdown.style.top = Math.round(top) + 'px';
    }

    // Close on outside click, scroll or resize
    const close = (e) => {
        if (!dropdown.classList.contains('ajaxtable-dropdown-open')) return;
        if (e && dropdown.contains(e.target)) return;
        if (e && e.target.closest && e.target.closest('button[data-dropdown-for]')) return;
        window.closeActionsDropdown(dropdown);
    };
    dropdown._closeHandler = close;
    setTimeout(() => document.addEventListener('mousedown', close, true), 0);
    document.addEventListener('scroll', close, true);
    window.addEventListener('resize', close);
};

window.closeActionsDropdown = function(dropdown) {
    dropdown.classList.add('hidden');
    dropdown.classList.remove('ajaxtable-dropdown-open');
    dropdown.style.position = '';
    dropdown.style.left = '';
    dropdown.style.top = '';
    if (dropdown._closeHandler) {
        document.removeEventListener('mousedown', dropdown._closeHandler, true);
        document.removeEventListener('scroll', dropdown._closeHandler, true);
        window.removeEventListener('resize', dropdown._closeHandler);
        delete dropdown._closeHandler;
    }
};

// Make ajaxTable function globally available for Alpine.js
window.ajaxTable = ajaxTable;
