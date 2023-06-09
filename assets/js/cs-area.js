export default class Area {
    
    constructor(data) {
        this.name = data.title.rendered;
        this.overview = data.acf.overview;
        this.careers = data.acf.careers;
        this.featuredImageUrl = null;
        this.faculty = null;
        this.courses = null;

        if (data._embedded && data._embedded["wp:featuredmedia"] && data._embedded["wp:featuredmedia"].length > 0) {
            this.featuredImageUrl = data._embedded["wp:featuredmedia"][0].media_details.sizes.medium.source_url;
        }
        if (data.acf.associated_faculty) {
            this.faculty = data._embedded["acf:post"]
                .filter(item => data.acf.associated_faculty.includes(item.id))
                .map(item => item.title.rendered)
        }

        if (data.acf.course_offerings) {
            this.courses = data._embedded["acf:post"]
            .filter(item => data.acf.course_offerings.includes(item.id))
            .map(item => `${item.title.rendered}: ${item.acf.title}`);
        }
    }

    getTemplate(data) {
        console.log(data);
        let html = `
            <h2 class="person-header">${ this.name }</h2>
            ${ this.getFeaturedImage() }
            <h3>Overview</h3>
            ${ this.overview }
            <h3>Careers</h3>
            ${ this.careers }
            ${ this.showFaculty() }
            ${ this.showCourses() }
        `;
        return html;
    }
    
    showFaculty() {
        if (!this.faculty) {
            return '';
        }

        return `
            <h3>Associated Faculty</h3>
            <ul><li>${this.faculty.join('</li><li>')}</li></ul>
        `;
    }
    
    showCourses() {
        if (!this.courses) {
            return '';
        }
        return `
            <h3>Course Offerings</h3>
            <ul><li>${this.courses.join('</li><li>')}</li></ul>
        `;
    }
    
    getFeaturedImage() {
        return this.featuredImageUrl ?
            `<img class="people-thumb" src="${this.featuredImageUrl}" />`
            : '';
    }
    
}