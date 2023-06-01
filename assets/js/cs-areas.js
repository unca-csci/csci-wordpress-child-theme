// delete window.showLightbox;
delete window.showLightboxPeople;
delete window.hideLightbox;


window.showLightbox = async postID => {
    const lightboxEl = document.querySelector("#lightbox");
    const html = await fetch(`/wp-json/wp/v2/cs-areas/${postID}?_embed`)
        .then(response => response.json());
    lightboxEl.querySelector(".content").innerHTML = showInfo(html);
    lightboxEl.classList.add("show");
    document.body.style.overflowY = "hidden";
    lightboxEl.querySelector("#close").focus();
    lightboxEl.classList.remove("people-detail");
};

window.showLightboxPeople = async fileName => {
    await window.showLightbox(fileName);
    const lightboxEl = document.querySelector("#lightbox");
    lightboxEl.classList.add("people-detail");
};

window.hideLightbox = ev => {
    const classList = ev.target.classList;
    let doClose = false;
    classList.forEach(className => {
        if (["fa-times", "close", "close-icon", "show"].includes(className)) {
            doClose = true;
            return;
        }
    })
    if (!doClose) {return};
    const lightboxEl = document.querySelector("#lightbox");
    lightboxEl.classList.remove("show");
    document.body.style.overflowY = "scroll";
};

function showInfo(data) {
    console.log(data);
    let html = `
        <h2 class="person-header">${data.title.rendered}</h2>
        ${getFeaturedImage(data)}
        <h3>Overview</h3>
        <p>${data.acf.overview}</p>
        <h3>Careers</h3>
        <p>${data.acf.careers}</p>
        ${showFaculty(data)}
        ${showCourses(data)}
    `;
    return html;
}

function showFaculty(data) {
    if (!data.acf.associated_faculty) {
        return '';
    }
    const names = data._embedded["acf:post"]
        .filter(item => data.acf.associated_faculty.includes(item.id))
        .map(item => item.title.rendered);
    
    return `
        <h3>Associated Faculty</h3>
        <ul><li>${names.join('</li><li>')}</li></ul>
    `;
}

function showCourses(data) {
    if (!data.acf.course_offerings) {
        return '';
    }
    const names = data._embedded["acf:post"]
        .filter(item => data.acf.course_offerings.includes(item.id))
        .map(item => `${item.title.rendered}: ${item.acf.title}`);
    
    return `
        <h3>Course Offerings</h3>
        <ul><li>${names.join('</li><li>')}</li></ul>
    `;
}

function getFeaturedImage(data) {
    if (data._embedded && data._embedded["wp:featuredmedia"] && data._embedded["wp:featuredmedia"].length > 0) {
        return `<img class="" src="${data._embedded["wp:featuredmedia"][0].media_details.sizes.medium.source_url}" />`;
    }
    return "";
}

