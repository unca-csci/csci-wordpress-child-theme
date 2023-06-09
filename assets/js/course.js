export default class Course {
    
    constructor(data) {
        console.log(data);
        this.code = data.title.rendered;
        this.name = data.acf.title;
        this.description = data.acf.description;
        this.prerequisites = null;
        this.cs_areas = null;
        this.requirements = null;
        if (data.acf.prerequisites) {
            this.prerequisites = data._embedded["acf:post"]
                .filter(item => data.acf.prerequisites.includes(item.id))
                .map(item => `${item.title.rendered}: ${item.acf.title}`);
        }
        if (data.acf.cs_areas) {
            this.cs_areas = data._embedded["acf:post"]
                .filter(item => data.acf.cs_areas.includes(item.id))
                .map(item => {
                    return { "id": item.id, "name": item.title.rendered }
                });
        }
        if (data.acf.major_minor_requirements && data.acf.major_minor_requirements.length > 0) {
            this.requirements = data.acf.major_minor_requirements;
        }

    }

    getTemplate() {
        let html = `
            <h2 class="person-header">${ this.code }: ${ this.name }</h2>
            <p>${ this.description }</p>
            ${ this.getPrereqs() }
            ${ this.getRequirements() }
            ${ this.getAreas() }
        `;
        return html;
    }

    getPrereqs() {
        if (!this.prerequisites) {
            return '<h3>Prerequisites</h3><p>None</p>';
        }
        return `
            <h3>Prerequisites</h3>
            <ul><li>${this.prerequisites.join('</li><li>')}</li></ul>
        `;
    }

    getAreas() {
        if (!this.cs_areas) {
            return '';
        }
        return '<div>' + 
            this.cs_areas.map(item => {
                return `<span class="tag">${ item.name }</span>`;
            }).join('') + 
        '</div>';
    }

    getRequirements() {
        if (!this.requirements) {
            return '';
        }
        return `
            <h3>Required for:</h3>
            <ul><li>${this.requirements.join('</li><li>')}</li></ul>
        `;
    }
    
}