// delete window.showLightbox;
delete window.showLightboxPeople;
delete window.hideLightbox;


window.showLightbox = async postID => {
    const lightboxEl = document.querySelector("#lightbox");
    const html = await fetch(`/wp-json/wp/v2/people/${postID}?_embed`)
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
        <h3>${data.acf.title}</h3>
        ${getContactInfo(data)}
        ${data.acf.bio ? `<h3>Bio</h3>${data.acf.bio.replaceAll("\n","<br>")}` : "" }
        ${data.acf.education ? `<h3>Education</h3>${data.acf.education}` : "" }
        ${data.acf.interests ? `<h3>Research & Professional Interests</h3>${data.acf.interests}` : "" }
        ${data.acf.website ? `<h3>Website</h3><a href="${data.acf.website}" target="_blank">${data.acf.website}</a>` : "" }
    `;
    return html;
}

function getFeaturedImage(data) {
    if (data._embedded && data._embedded["wp:featuredmedia"] && data._embedded["wp:featuredmedia"].length > 0) {
        return `<img class="people-thumb" src="${data._embedded["wp:featuredmedia"][0].media_details.sizes.medium.source_url}" />`;
    }
    return "";
}

function getContactInfo(data) {
    let html = `<div class="contact-info">`;
    if (data.acf.phone_number) {
        html += `
            <div class="meta">
                <i class="fa-solid fa-phone"></i>
                ${data.acf.phone_number}
            </div>`;
    } 
    if (data.acf.email) {
        html += `
            <div class="meta">
                <i class="fa-regular fa-envelope"></i>
                ${data.acf.email}
            </div>`;
    } 
    if (data.acf.address) {
        html += `
            <div class="meta">
            <i class="fa-solid fa-location-dot"></i>
                ${data.acf.address}
            </div>`;
    } 
    return html + "</div>";
}