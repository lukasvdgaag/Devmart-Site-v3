class Pagination {
    queryParam = 'page';
    totalPages = 1;
    pageSize = 6;
    clickFunction = '';
    totalRecords = 0;

    constructor(queryParam, totalRecords, pageSize, clickFunction) {
        this.queryParam = queryParam;
        this.pageSize = pageSize;
        this.setTotalRecords(totalRecords);
        this.clickFunction = clickFunction;

        this.reload();
    }

    setTotalRecords(number) {
        this.totalRecords = number;
        this.totalPages = Math.max(Math.ceil(number / this.pageSize), 1);
    }

    getCurrentPage() {
        return Number.parseInt(getURLParam(this.queryParam) ?? '1');
    }

    addPageToArray(array, page) {
        if (!array.includes(page)) array.push(page);
    }

    calculatePages() {
        let pages = [];
        if (this.totalRecords <= this.pageSize) return;

        const currentPage = this.getCurrentPage();

        this.addPageToArray(pages, 1);

        for (let i = 2; i >= 1; i--) {
            if (currentPage - i <= 1) break;

            this.addPageToArray(pages, currentPage - i);
        }

        this.addPageToArray(pages, currentPage);
        for (let i = 1; i <= 2; i++) {
            if (currentPage + 1 >= this.totalPages) break;

            this.addPageToArray(pages, currentPage + i);
        }

        this.addPageToArray(pages, this.totalPages);
        return pages;
    }

    reload() {
        let currentPage = this.getCurrentPage();
        if (currentPage > this.totalPages) {
            setURLParam(this.queryParam, this.totalPages);
        } else if (currentPage < 1) {
            setURLParam(this.queryParam, 1);
        }

        let pages = this.calculatePages();

        const pagination = document.querySelector(`.pagination`);
        pagination.innerHTML = "";

        if (this.totalPages > 1) {
            if (currentPage > 1) {
                pagination.append(this.createPageElement('previous'));
            }
            for (let page of pages) {
                pagination.append(this.createPageElement(page));
            }
            if (currentPage < this.totalPages) {
                pagination.append(this.createPageElement('next'));
            }
        }

        for (let el of document.querySelectorAll('.pagination .page-item')) {
            if (el.getAttribute('value') == currentPage) {
                el.classList.add('active');
                break;
            }
        }
    }

    createPageElement(value) {
        let element = document.createElement('div');
        element.classList.add('flex', 'align-center', 'page-item');
        element.setAttribute('value', value);
        element.innerHTML = value;
        element.onclick = () => {
            this.handleClick(element);
        };
        return element;
    }

    handleClick(element) {
        console.log("click")
        const value = element.getAttribute('value', '1');
        const page = this.getCurrentPage();

        let newPage = 1;
        if (value == "previous") {
            newPage = Math.max(page - 1, 1);
        } else if (value == "next") {
            newPage = Math.min(page + 1, this.totalPages);
        } else if (!isNaN(parseInt(value))) {
            newPage = value;
        }

        setURLParam(this.queryParam, newPage);
        window[this.clickFunction]();
    }
}


