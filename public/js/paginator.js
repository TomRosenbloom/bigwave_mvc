class Paginator {
    constructor(page, perPage, total){
        this.page = page;
        this.perPage = perPage;
        this.total = total;
    }
    
    getOffset() {     
        return (this.page - 1) * this.perPage;
    }    
}
